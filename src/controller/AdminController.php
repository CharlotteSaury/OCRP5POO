<?php

namespace src\controller;

use src\controller\Controller;
use src\controller\HomeController;
use src\controller\UserController;
use config\Request;
use config\Parameter;
use config\File;
use Exception;

class AdminController extends Controller

{
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

	public function adminPostsView($message = null, Parameter $get = null)
	{
		$totalPostsNb = $this->postManager->getPostsNb();
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$unpublishedPostsNb = $totalPostsNb - $publishedPostsNb;
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		if ($get != null)
		{
			if ($get->get('sort') || $get->get('date'))
			{
				$sorting = $this->getSortingResults($get, 'unpublished');

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
			}
			else
			{
				$contentTitle = 'Tous les articles';
				$posts = $this->postManager->getPosts($status = null, $first_post = null, $postsPerPage = null);
			}
		}
		else
		{
			$contentTitle = 'Tous les articles';
			$posts = $this->postManager->getPosts($status = null, $first_post = null, $postsPerPage = null);
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
		$errors = $this->validation->exists('postId', $postId);

		if (!$errors)
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
		throw new Exception('Identifiant de post non valide');

		
	}

	public function adminNewPostView($message = null, $errors = null)
	{
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'newPostInfosView', ['unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession(),
			'errors' => $errors]);
	}

	public function newPostInfos(Parameter $post, File $file = null)
	{
		$errors = $this->validation->validate($post, 'Post');

		if (!$errors)
		{
			if ($file->get('picture') && $file->get('picture', 'error') != 4)
			{
				$infos = new HomeController();
				$uploadResults = $infos->pictureUpload($namePicture = 'picture');
				if (strrpos($uploadResults, 'uploads') === false)
				{
					$message = $uploadResults;
					$this->adminNewPostView($message);
				}
				else
				{
					$mainImage = $uploadResults;
					$postId = $this->postManager->newPostInfos($post, $mainImage);
					$this->editPostView($postId);
				}
			}
			else
			{
				$postId = $this->postManager->newPostInfos($post, $mainImage = null);
				$this->editPostView($postId);
			}
		}
		else
		{
			$this->adminNewPostView($message = null, $errors);
		}
		
	}

	public function publishPost(Parameter $get)
	{
		$this->postManager->publishPost($get);
		$message = "Statut du post modifié ! ";

		if ($get->get('action') === 'publishPostDashboard')
		{
			$this->dashboardView($message);
		}
		else
		{
			$this->adminPostsView($message);
		}
	}

	public function editPostView($postId, $message = null, $errors = null)
	{
		$errorExists = $this->validation->exists('postId', $postId);

		if (!$errorExists)
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
				'session' => $this->request->getSession(),
				'errors' => $errors]);
		}
		throw new Exception('Identifiant de billet non valide.');
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

	public function editPostPicture(Parameter $post)
	{
		foreach ($_FILES AS $key => $value)
		{
			if ($value['name'] !='')
			{
				$contentId = substr($key, 7);
			}
		}

		$homeController = new HomeController();
		$uploadResults = $homeController->pictureUpload($namePicture = 'picture' . $contentId);

		if (strrpos($uploadResults, 'uploads') === false)
		{
			$message = $uploadResults;
		}
		else
		{
			$content = $this->contentManager->getContent($contentId);
			$oldImgUrl = $content->content();
			$this->contentManager->updatePostPicture($contentId, $uploadResults);
			$this->postManager->dateUpdate($post->get('postId'));
			unlink($oldImgUrl);

			$message = 'Image modifiée !';
		}

		$this->editPostView($post->get('postId'), $message);

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

	public function editParagraph(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Content');

		if (!$errors)
		{
			$contentId = $post->get('editContent');
			$this->contentManager->editParagraph($contentId, $post->get($contentId));
			$this->postManager->dateUpdate($post->get('postId'));
			$message = 'Bloc paragraphe enregistré ! ';
		}
		else
		{
			$message = '';
			foreach($errors as $key => $value)
			{
				$message .= '<p>' . $value . '</p>';
			}
		}
		$this->editPostView($post->get('postId'), $message);
	}

	public function addPicture(Parameter $post)
	{
		$homeController = new HomeController();
		$uploadResults = $homeController->pictureUpload($namePicture = 'picture');

		if (strrpos($uploadResults, 'uploads') === false)
		{
			$message = $uploadResults;
		}
		else
		{
			$this->contentManager->addContent($post->get('postId'), $uploadResults);
			$this->postManager->dateUpdate($post->get('postId'));
			$message = 'Image ajoutée !';
		}

		$this->editPostView($post->get('postId'), $message);
	}

	public function addCategory(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Category');

		if (!$errors)
		{
			$message = $this->postManager->addCategory($post->get('postId'), $post->get('categoryName'));
			$this->postManager->dateUpdate($post->get('postId'));
		}
		else
		{
			$message = $errors['categoryName'];
		}
		$this->editPostView($post->get('postId'), $message);
		
	}

	public function deleteCategory(Parameter $get)
	{
		$this->postManager->deleteCategory($get);
		$this->postManager->dateUpdate($get->get('id'));
		$message = 'Catégorie supprimée ! ';
		$this->editPostView($get->get('id'), $message);
	}

	public function editPostInfos(Parameter $post)
	{
		$file = $this->request->getFile();
		$homeController = new HomeController();

		if ($file->get('MainPicture') && $file->get('MainPicture', 'error') != 4)
		{
			$uploadResults = $homeController->pictureUpload($namePicture = 'MainPicture');

			if (strrpos($uploadResults, 'uploads') === false)
			{
				$message = "Information(s) modifiée(s) \n 
				/!\ Erreur de téléchargement de l'image principale : " . $uploadResults;
			}
			else
			{
				$post->set('main_image', $uploadResults);
				$message = "Information(s) modifiée(s) ! ";
			}
		}
		else
		{
			$message = "Information(s) modifiée(s) ! ";
		}

		$errors = $this->validation->validate($post, 'Post');

		if (!$errors)
		{
			$this->postManager->editPostInfos($post);
			$this->postManager->dateUpdate($post->get('postId'));
			$this->editPostView($post->get('postId'), $message);
		}
		else
		{
			$message = '';
			foreach($errors as $key => $value)
			{
				$message .= '<p>' . $value . '</p>';
			}
			$this->editPostView($post->get('postId'), $message);
		}

			
		
	}

	public function deletePost(Parameter $get, $dashboard = null)
	{
		$this->postManager->deletePost($get->get('id'));
		$this->commentManager->deleteCommentsFromPost($get->get('id'));
		$this->postManager->deleteCategory($get);
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

	public function adminCommentsView($message = null, Parameter $get = null)
	{
		$totalCommentsNb = $this->commentManager->getCommentsNb();
		$approvedCommentsNb = $this->commentManager->getCommentsNb(1);
		$unapprovedCommentsNb = $totalCommentsNb - $approvedCommentsNb;
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		if ($get != null)
		{
			if ($get->get('sort') || $get->get('date'))
			{
				$sorting = $this->getSortingResults($get, 'unapproved');

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
			}
			else
			{
				$contentTitle = 'Tous les Commentaires';
				$allComments = $this->commentManager->getComments($commentsNb = null, $status = null);
			}
		}
		else
		{
			$contentTitle = 'Tous les Commentaires';
				$allComments = $this->commentManager->getComments($commentsNb = null, $status = null);
		}
		
		
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

	public function approveComment(Parameter $get)
	{
		$this->commentManager->approveComment($get->get('id'));
		$message = "Commentaire approuvé ! ";

		if ($get->get('action') == 'approveCommentDashboard')
		{
			$this->dashboardView($message);
		}
		elseif ($get->get('action') == 'approveCommentView')
		{
			$this->adminPostView($get->get('post'), $message);
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
			elseif ($userRoleId == 3)
			{
				$contentTitle = 'Super Admin';
			}
			else
			{
				throw new Exception("La page que vous recherchez n'existe pas. ");
			}
		}
		else
		{
			$contentTitle = 'Tous les Utilisateurs';
		}

		$allUsersNb = $this->userManager->getUserNb();
		$superAdminNb = $this->userManager->getUserNb(3);
		$adminNb = $this->userManager->getUserNb(1);
		$usersNb = $this->userManager->getUserNb(2);

		
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
		$errorExists = $this->validation->exists('userId', $userId);

		if (!$errorExists)
		{
			$user = $this->userManager->getUser($userId);
			$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

			return $this->view->render('backend', 'profileUserView', ['user' => $user,
				'unreadContactsNb' => $unreadContactsNb,
				'userId' => $userId,
				'message' => $message,
				'session' => $this->request->getSession()]);
		}
		throw new Exception('Le profil demandé n\'existe pas');	
	}

	public function editUserView($userId, $message = null, $errors = null)
	{
		$user = $this->userManager->getUser($userId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'editUserView', ['user' => $user,
			'unreadContactsNb' => $unreadContactsNb,
			'userId' => $userId,
			'message' => $message,
			'session' => $this->request->getSession(),
			'errors' => $errors]);
	}

	public function editUser($userId)
	{
		$errorExists = $this->validation->exists('userId', $userId);
		$userController = new UserController;

		if(!$errorExists)
		{
			$currentUserId = $this->request->getSession()->get('id');
			
			if ($currentUserId == $userId || $userController->adminAccess())
			{
				$this->editUserView($userId);
			}
			else
			{
				throw new Exception('Vous n\'avez pas accès à cette page');
			}
		}
		else
		{
			throw new Exception('Le profil demandé n\'existe pas');
		}
	}

	public function editUserInfos(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'User');
		$userController = new UserController;

		if (!$errors)
		{
			if (!$post->get('user_role_id'))
			{
				$post->set('user_role_id', $this->request->getSession()->get('role'));
			}

			if (empty($post->get('birth_date')))
			{
				$this->deleteBirthDate($post->get('id'));
			}
			else
			{
				$checkDate = explode('-', $post->get('birth_date'));		
				$post->set('birth_date', $checkDate[2] . '-' . $checkDate[1] . '-' . $checkDate[0]);
			}

			$currentUserId = $this->request->getSession()->get('id');
			$this->userManager->editUserInfos($post);
			$message = "Profil modifié ! ";
			$this->profileUserView($post->get('id'), $message);

			// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

			if ($currentUserId == $post->get('id'))
			{
				$userController->newUserSession($post->get('email'));
			}
		}
		else
		{
			$message = 'Le profil n\'a pas pu être modifié.';
			$this->editUserView($post->get('id'), $message, $errors);
		}
		
	}

	public function deleteBirthDate($userId)
	{
		$this->userManager->deleteBirthDate($userId);
	}

	public function updateProfilePicture($userId)
	{
		$homeController = new HomeController();
		$uploadResults = $homeController->pictureUpload($namePicture = 'picture');

		if (strrpos($uploadResults, 'uploads') === false)
		{
			$message = $uploadResults;
			$this->profileUserView($userId, $message);
		}
		else
		{
			$user = $this->userManager->getUser($userId);
			$oldAvatarUrl = $user->avatar();
			if ($oldAvatarUrl != 'public/images/profile.png')
			{
				unlink($oldAvatarUrl);
			}

			$this->userManager->updateProfilePicture($userId, $uploadResults);
			$message = 'Photo de profil modifiée !';

			$this->profileUserView($userId, $message);

			// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

			$currentUserId = $this->request->getSession()->get('id');	
			if ($currentUserId == $userId)
			{
				$this->request->getSession()->set('avatar', $uploadResults);
			}
		}
	}

	public function adminContactsView($message = null, Parameter $get = null)
	{
		$allContactsNb = $this->contactManager->getTotalContactsNb();
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		if ($get != null)
		{
			if ($get->get('sort') || $get->get('date'))
			{
				$sorting = $this->getSortingResults($get, 'unread');

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
			}
			else
			{
				$contentTitle = 'Tous les contacts';
				$allContacts = $this->contactManager->getContacts($contactId = null, $status = null);
			}
		}
		else
		{
			$contentTitle = 'Tous les contacts';
			$allContacts = $this->contactManager->getContacts($contactId = null, $status = null);
		}
		
		return $this->view->render('backend', 'adminContactsView', ['allContactsNb' => $allContactsNb,
			'unreadContactsNb' => $unreadContactsNb,
			'contentTitle' => $contentTitle,
			'allContacts' => $allContacts,
			'message' => $message,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	public function adminContactView($contactId, $message = null, $errors = null)
	{
		$errorExists = $this->validation->exists('contactId', $contactId);

		if (!$errorExists)
		{
			$contact = $this->contactManager->getContacts($contactId);

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
				'session' => $this->request->getSession(),
				'errors' => $errors]);
		}
		else
		{
			throw new Exception('L\'identifiant du contact n\'est pas valide.');
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
		$contact = $this->contactManager->getContacts($post->get('contactId'));

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
		$errors = $this->validation->validate($post, 'Answer');

		if(!$errors)
		{
			$contact = $this->contactManager->getContacts($post->get('contactId'));
			$this->contactManager->updateStatus($post->get('contactId'), 3);   
			$this->contactManager->addAnswer($post);
			$this->adminAnswerEmail($post);
			$message = "La réponse a bien été envoyée.";
			$this->adminContactView($post->get('contactId'), $message);  
		}
		else
		{
			if (isset($errors['contactId']))
			{
				throw new Exception($errors['contactId']);
			}
			elseif (isset($errors['email']))
			{
				throw new Exception($errors['email']);
			}
			$this->adminContactView($post->get('contactId'), $message = null, $errors);
		}
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


