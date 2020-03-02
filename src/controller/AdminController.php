<?php

namespace src\controller;

use src\controller\Controller;
use config\Parameter;
use Exception;

class AdminController extends Controller

{
	public function isValid($contactId)
	{
		$contacts = $this->contactManager->getContacts();
		foreach ($contacts as $contact) 
		{
			if ($contact->id() == $contactId)
			{
				return true;
			}
		}
		return false;
	}

	public function dashboardView($message = null)
	{
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$totalPostsNb = $this->postManager->getPostsNb();
		$approvedCommentsNb = $this->commentManager->getCommentsNb(1);
		$totalCommentsNb = $this->commentManager->getCommentsNb();
		$usersNb = $this->userManager->getUserNb();
		$recentPosts = $this->postManager->getRecentPosts();
		$recentComments = $this->commentManager->getComments(5);
		$users = $this->userManager->getUsers(5);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'dashboardView', [
			'message' => $message,
			'publishedPostsNb' => $publishedPostsNb,
			'totalPostsNb' => $totalPostsNb,
			'approvedCommentsNb' => $approvedCommentsNb,
			'totalCommentsNb' => $totalCommentsNb,
			'usersNb' => $usersNb,
			'recentPosts' => $recentPosts,
			'recentComments' => $recentComments,
			'users' => $users,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	public function adminPostsView($message = null, $sorting = null, Parameter $get = null)
	{
		$totalPostsNb = $this->postManager->getPostsNb();
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$unpublishedPostsNb = $totalPostsNb - $publishedPostsNb;

		if ($message == null)
		{
			$message = $sorting[0];
		}		

		if ($sorting[1] != null)
		{
			$contentTitle = 'Articles non publiés';
			$posts = $this->postManager->getPosts(1, $first_post = null, $postsPerPage = null, $sorting[2]);
		}
		else
		{
			$contentTitle = 'Tous les articles';
			$posts = $this->postManager->getPosts($status = null, $first_post = null, $postsPerPage = null, $sorting[2]);
		}
		
		$allPostsCategories = $this->postManager->getPostsCategories();

		foreach ($allPostsCategories as $key => $value)
		{
			foreach ($posts as $post)
			{
				if ($post->id() == $key)
				{
					$post->setCategories($value);
				}
			}
		}

		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'adminPostsView', ['message' => $message,
			'contentTitle' => $contentTitle, 
			'totalPostsNb' => $totalPostsNb,
			'unpublishedPostsNb' => $unpublishedPostsNb,
			'posts' => $posts,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	public function adminPostView($postId, $message = null)
	{
		$post = $this->postManager->getPostInfos($postId);
		$post->setCategories($this->postManager->getPostsCategories($postId));
		$contents = $this->contentManager->getPostContents($postId);
		$postComments = $this->commentManager->getPostComments($postId);	
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'adminPostView', ['postId' => $postId,
			'message' => $message,
			'post' => $post,
			'contents' => $contents,
			'postComments' => $postComments,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	public function adminNewPostView($message = null)
	{
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'newPostInfosView', ['unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	public function newPostInfos(Parameter $post, $mainImage)
	{
		$postId = $this->postManager->newPostInfos($post->get('title'), $post->get('chapo'), $post->get('userId'), $mainImage);
		$this->editPostView($postId);
	}

	public function publishPost(Parameter $get, $dashboard = null)
	{
		$this->postManager->publishPost($get);
		$message = "Statut du post modifié ! ";

		if ($dashboard != null)
		{
			$this->dashboardView($message);
		}
		else
		{
			$this->adminPostsView($message);
		}
	}

	public function editPostView($postId, $message = null)
	{
		$post = $this->postManager->getPostInfos($postId);
		$post->setCategories($this->postManager->getPostsCategories($postId));
		$contents = $this->contentManager->getPostContents($postId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'editPostView', ['message' => $message,
			'postId' => $postId,
			'post' => $post,
			'contents' => $contents,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	public function deleteMainPostPicture($postId)
	{
		$post = $this->postManager->getPostInfos($postId);
		$mainImgUrl = $post->mainImage();
		unlink($mainImgUrl);

		$this->postManager->deleteMainPostPicture($postId);
		$this->postManager->dateUpdate($postId);
		$message = 'Photo supprimée ! ';
		$this->editPostView($postId, $message);
	}

	public function editPostPicture($postId, $contentId, $url)
	{
		$content = $this->contentManager->getContent($contentId);
		$oldImgUrl = $content->content();
		$this->contentManager->updatePostPicture($contentId, $url);
		$this->postManager->dateUpdate($postId);
		unlink($oldImgUrl);
	}

	public function deleteContent(Parameter $get)
	{
		if ($get->get('type') == 1)
		{
			$content = $this->contentManager->getContent($get->get('content'));
			$imgUrl = $content->content();
			unlink($imgUrl);
		}

		$this->contentManager->deleteContent($get->get('content'));
		$this->postManager->dateUpdate($get->get('id'));
		$message = 'Contenu supprimé ! ';
		$this->editPostView($get->get('id'), $message);
	}

	public function addParagraph($postId)
	{
		$this->contentManager->addContent($postId);
		$this->postManager->dateUpdate($postId);
		$message = 'Bloc paragraphe ajouté ! ';
		$this->editPostView($postId, $message);
	}

	public function editParagraph($postId, $newParagraphs)
	{
		$this->contentManager->editParagraph($newParagraphs);
		$this->postManager->dateUpdate($postId);
		$message = 'Bloc paragraphe enregistré ! ';
		$this->editPostView($postId, $message);
	}

	public function addPicture($postId, $content)
	{
		$this->contentManager->addContent($postId, $content);
		$this->postManager->dateUpdate($postId);
	}

	public function addCategory(Parameter $post)
	{
		$message = $this->postManager->addCategory($post->get('postId'), $post->get('categoryName'));
		$this->postManager->dateUpdate($post->get('postId'));
		$this->editPostView($post->get('postId'), $message);
	}

	public function deleteCategory(Parameter $get)
	{
		$this->postManager->deleteCategory($get);
		$this->postManager->dateUpdate($get->get('id'));
		$message = 'Catégorie supprimée ! ';
		$this->editPostView($get->get('id'), $message);
	}

	public function editPostInfos(Parameter $post, $message = null)
	{
		if (empty($post->get('title')) || empty($post->get('chapo')))
		{
			$message = 'Les champs titre et chapo ne peuvent être vides.';
		}
		else
		{
			$this->postManager->editPostInfos($post);
			$this->postManager->dateUpdate($post->get('postId'));
		}
		$this->editPostView($post->get('postId'), $message);
	}

	public function deletePost($postId, $dashboard = null)
	{
		$this->postManager->deletePost($postId);
		$this->commentManager->deleteCommentsFromPost($postId);
		$message = "Post supprimé ! ";

		if ($dashboard != null)
		{
			$this->dashboardView($message);
		}
		else
		{
			$this->adminPostsView($message);
		}
	}

	public function adminCommentsView($message = null, $sorting = null, Parameter $get = null)
	{
		$totalCommentsNb = $this->commentManager->getCommentsNb();
		$approvedCommentsNb = $this->commentManager->getCommentsNb(1);
		$unapprovedCommentsNb = $totalCommentsNb - $approvedCommentsNb;

		if ($message == null)
		{
			$message = $sorting[0];
		}

		if ($sorting[1] != null)
		{
			$contentTitle = 'Commentaires non approuvés';
			$status = 0;
			$allComments = $this->commentManager->getComments($commentsNb = null, $status, $sorting[2]);
		}
		else
		{
			$contentTitle = 'Tous les Commentaires';
			$allComments = $this->commentManager->getComments($commentsNb = null, $status = null, $sorting[2]);
		}

		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'adminCommentsView', ['message' => $message,
			'totalCommentsNb' => $totalCommentsNb,
			'unapprovedCommentsNb' => $unapprovedCommentsNb,
			'contentTitle' => $contentTitle,
			'status' => $status,
			'allComments' => $allComments,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	public function approveComment($commentId, $view = null, $postId = null)
	{
		$this->commentManager->approveComment($commentId);
		$message = "Commentaire approuvé ! ";

		if ($view == 1)
		{
			$this->dashboardView($message);
		}
		elseif ($view == 2)
		{
			$this->adminPostView($postId, $message);
		}
		else
		{
			$this->adminCommentsView($message);
		}
	}

	public function deleteComment($commentId, $dashboard = null)
	{
		$this->commentManager->deleteComment($commentId);
		$message = "Commentaire supprimé ! ";

		if ($dashboard != null)
		{
			$this->dashboardView($message);
		}
		else
		{
			$this->adminCommentsView($message);
		}
	}

	public function adminUsersView($userRoleId = null)
	{
		$allUsersNb = $this->userManager->getUserNb();
		$superAdminNb = $this->userManager->getUserNb(3);
		$adminNb = $this->userManager->getUserNb(1);
		$usersNb = $this->userManager->getUserNb(2);

		if ($userRoleId != null)
		{
			if ($userRoleId == 1)
			{
				$contentTitle = 'Administrateurs';
			}
			elseif ($userRoleId == 2)
			{
				$contentTitle = 'Utilisateurs';
			}
			else
			{
				$contentTitle = 'Super Admin';
			}
		}
		else
		{
			$contentTitle = 'Tous les Utilisateurs';
		}
		
		$users = $this->userManager->getUsers(null, $userRoleId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'adminUsersView', ['allUsersNb' => $allUsersNb,
			'superAdminNb' => $superAdminNb,
			'adminNb' => $adminNb,
			'usersNb' => $usersNb,
			'contentTitle' => $contentTitle,
			'users' => $users,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	public function profileUserView($userId, $message = null)
	{
		$user = $this->userManager->getUser($userId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'profileUserView', ['user' => $user,
			'unreadContactsNb' => $unreadContactsNb,
			'userId' => $userId,
			'message' => $message,
			'session' => $this->request->getSession()]);
	}

	public function editUserView($userId, $message = null)
	{
		$user = $this->userManager->getUser($userId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'editUserView', ['user' => $user,
			'unreadContactsNb' => $unreadContactsNb,
			'userId' => $userId,
			'message' => $message,
			'session' => $this->request->getSession()]);
	}

	public function editUserInfos($newUserInfos)
	{
		$userId = $newUserInfos['id'];
		$this->userManager->editUserInfos($newUserInfos);
		$message = "Profil modifié ! ";
		$this->profileUserView($userId, $message);
	}

	public function deleteBirthDate($userId)
	{
		$this->userManager->deleteBirthDate($userId);
	}

	public function updateProfilePicture($userId, $avatarUrl)
	{
		$user = $this->userManager->getUser($userId);
		$oldAvatarUrl = $user->avatar();
		if ($oldAvatarUrl != 'public/images/profile.png')
		{
			unlink($oldAvatarUrl);
		}

		$this->userManager->updateProfilePicture($userId, $avatarUrl);
	}

	public function adminContactsView($message = null, $sorting = null, Parameter $get = null)
	{
		$allContactsNb = $this->contactManager->getTotalContactsNb();
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		if ($message == null)
		{
			$message = $sorting[0];
		}
		

		if ($sorting[1] != null)
		{
			$contentTitle = 'Contacts non lus';
			$status = 1;
			$allContacts = $this->contactManager->getContacts($contactId = null, $status, $sorting[2]);
		}
		else
		{
			$contentTitle = 'Tous les contacts';
			$allContacts = $this->contactManager->getContacts($contactId = null, $status = null, $sorting[2]);
		}


		return $this->view->render('backend', 'adminContactsView', ['allContactsNb' => $allContactsNb,
			'unreadContactsNb' => $unreadContactsNb,
			'contentTitle' => $contentTitle,
			'allContacts' => $allContacts,
			'message' => $message,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	public function adminContactView($contactId, $message = null)
	{
		$contact = $this->contactManager->getContacts($contactId);

		if ($this->isValid($contactId))
		{
			$currentStatus = $contact->statusId();

			if ($currentStatus != 3 )
			{
				$answer = null;
				$this->contactManager->updateStatus($contactId, 2);
			}
			else
			{
				$answer = $this->contactManager->getAnswer($contactId);
			}

			$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

			return $this->view->render('backend', 'adminContactView', ['contactId' => $contactId,
				'message' => $message,
				'contact' => $contact,
				'currentStatus' => $currentStatus,
				'answer' => $answer,
				'unreadContactsNb' => $unreadContactsNb,
				'session' => $this->request->getSession()]);
		}
		else
		{
			throw new Exception('Le contact n\'existe pas.');
		}
		
	}

	public function deleteContact($contactId, $dashboard = null)
	{
		$this->contactManager->deleteContact($contactId);
		$message = "Message supprimé ! ";
		$this->adminContactsView($message);
		
	}

	public function adminAnswerEmail(Parameter $post)
	{
		$contact = $this->contactManager->getContacts($post->get('id'));

		$subject = $post->get('answerSubject');
		$headers = "From: " . BLOG_AUTHOR . "/r/n";
		$message = $post->get('answerContent') . 
					" /r/n
					----------------/r/n/r/n
					De: " . $contact->name() . " <" . $post->get('email') . ">/r/n
					Le: " . $contact->dateMessage() . "/r/n
					Objet: " . $contact->subject() . "/r/n/r/n"
					. $contact->content();
			

		$message = wordwrap($message, 70, "\r\n");
		mail($post->get('email'), $subject, $message, $headers);	     
	}

	public function addAnswer(Parameter $post)
	{
		$contact = $this->contactManager->getContacts($post->get('id'));
		$this->contactManager->updateStatus($post->get('id'), 3);   
		$this->contactManager->addAnswer($post);
	}

	public function getSortingResults(Parameter $get, $sortingTitle)
	{
		$sort = $get->get('sort');
		$date = $get->get('date');

		if ($sort && !$date)
		{
			if ($sort == $sortingTitle)
			{
				$message = $sortingDate = null;
			}
			else
			{
				$message = 'Le choix de tri n\'est pas valide';
				$sort = $sortingDate = null;
			}
		}
		elseif (!$sort && $date)
		{
			if ($date == 'asc')
			{
				$message = $sort = null;
				$sortingDate = $date;
			}
			else
			{
				$message = 'Le choix de tri n\'est pas valide';
				$sort = $sortingDate = null;
			}
		}
		else
		{
			if ($sort == $sortingTitle && $date == 'asc')
			{
				$message = null;
				$sortingDate = $date;
			}
			else
			{
				$message = 'Le choix de tri n\'est pas valide';
				$sort = $sortingDate = null;
			}
		}

		return [$message, $sort, $sortingDate];
	}
	
}


