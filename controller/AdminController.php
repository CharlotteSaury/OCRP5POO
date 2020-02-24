<?php

namespace controller;

require_once './controller/Controller.php';
use controller\Controller;

class AdminController extends Controller

{
	public function dashboardView($message = null)
	{
		$publishedPostsNb = $this->postManager->getPublishedPostsNb();
		$totalPostsNb = $this->postManager->getTotalPostsNb();
		$approvedCommentsNb = $this->commentManager->getApprovedCommentsNb();
		$totalCommentsNb = $this->commentManager->getTotalCommentsNb();
		$usersNb = $this->userManager->getUserNb();
		$recentPosts = $this->postManager->getRecentPosts();
		$recentComments = $this->commentManager->getComments(5);
		$recentUsers = $this->userManager->getUsers(5);
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
			'recentUsers' => $recentUsers,
			'unreadContactsNb' => $unreadContactsNb]);
	}

	public function adminPostsView($message = null, $sorting = null, $sortingDate = null)
	{
		$totalPostsNb = $this->postManager->getTotalPostsNb();
		$publishedPostsNb = $this->postManager->getPublishedPostsNb();
		$unpublishedPostsNb = $totalPostsNb - $publishedPostsNb;

		if ($sorting != null)
		{
			$contentTitle = 'Articles non publiés';
			$allPosts = $this->postManager->getUnpublishedPosts($sortingDate);
		}
		else
		{
			$contentTitle = 'Tous les articles';
			$allPosts = $this->postManager->getPosts($first_post = null, $postsPerPage = null, $nbComments = 1, $sortingDate);
		}
		
		$allPostsCategories = $this->postManager->getAllPostsCategories();
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'adminPostsView', ['message' => $message,
			'contentTitle' => $contentTitle, 
			'totalPostsNb' => $totalPostsNb,
			'unpublishedPostsNb' => $unpublishedPostsNb,
			'allPosts' => $allPosts,
			'allPostsCategories' => $allPostsCategories,
			'unreadContactsNb' => $unreadContactsNb]);
	}

	public function adminPostView($postId, $message = null)
	{
		$postInfos = $this->postManager->getPostInfos($postId);
		$postContents = $this->postManager->getPostContents($postId);
		$postComments = $this->commentManager->getpostComments($postId);
		$postCategories = $this->postManager->getPostCategories($postId);		
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'adminPostView', ['postId' => $postId,
			'message' => $message,
			'postInfos' => $postInfos,
			'postContents' => $postContents,
			'postComments' => $postComments,
			'postCategories' => $postCategories,
			'unreadContactsNb' => $unreadContactsNb]);
	}

	public function adminNewPostView($message = null)
	{
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'newPostInfosView', ['unreadContactsNb' => $unreadContactsNb]);
	}

	public function newPostInfos($title, $chapo, $userId, $mainImage)
	{
		$postId = $this->postManager->newPostInfos($title, $chapo, $userId, $mainImage);
		$this->editPostView($postId);
	}

	public function publishPost($postId, $status, $dashboard = null)
	{
		$this->postManager->publishPost($postId, $status);
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
		$postInfos = $this->postManager->getPostInfos($postId);
		$postContents = $this->postManager->getPostContents($postId);
		$postCategories = $this->postManager->getPostCategories($postId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'editPostView', ['message' => $message,
			'postId' => $postId,
			'postInfos' => $postInfos,
			'postContents' => $postContents,
			'postCategories' => $postCategories,
			'unreadContactsNb' => $unreadContactsNb]);
	}

	public function editMainPostPicture($postId, $url)
	{
		$this->postManager->updateMainPostPicture($postId, $url);
		$this->postManager->dateUpdate($postId);
		$message = 'Photo modifiée ! ';
		$this->editPostView($postId, $message);
	}

	public function deleteMainPostPicture($postId)
	{
		$postInfos = $this->postManager->getPostInfos($postId);
		$mainImgUrl = $postInfos[0]['main_image'];
		unlink($mainImgUrl);

		$this->postManager->deleteMainPostPicture($postId);
		$this->postManager->dateUpdate($postId);
		$message = 'Photo supprimée ! ';
		$this->editPostView($postId, $message);
	}

	public function editPostPicture($postId, $contentId, $url)
	{
		$oldImgUrl = $this->postManager->getImgUrl($contentId);
		$this->postManager->updatePostPicture($contentId, $url);
		$this->postManager->dateUpdate($postId);
		unlink($oldImgUrl);
	}

	public function deleteContent($postId, $contentId, $contentType)
	{
		if ($contentType == 1)
		{
			$imgUrl = $this->postManager->getImgUrl($contentId);
			unlink($imgUrl);
		}

		$this->postManager->deleteContent($contentId);
		$this->postManager->dateUpdate($postId);
		$message = 'Contenu supprimé ! ';
		$this->editPostView($postId, $message);
	}

	public function addParagraph($postId)
	{
		$this->postManager->addParagraph($postId);
		$this->postManager->dateUpdate($postId);
		$message = 'Bloc paragraphe ajouté ! ';
		$this->editPostView($postId, $message);
	}

	public function editParagraph($postId, $newParagraphs)
	{
		$this->postManager->editParagraph($newParagraphs);
		$this->postManager->dateUpdate($postId);
		$message = 'Bloc paragraphe enregistré ! ';
		$this->editPostView($postId, $message);
	}

	public function addPicture($postId, $content)
	{
		$this->postManager->addPicture($postId, $content);
		$this->postManager->dateUpdate($postId);
	}

	public function addCategory($postId, $category)
	{
		$message = $this->postManager->addCategory($postId, $category);
		$this->postManager->dateUpdate($postId);
		$this->editPostView($postId, $message);
	}

	public function deleteCategory($postId, $categoryId)
	{
		$this->postManager->deleteCategory($postId, $categoryId);
		$this->postManager->dateUpdate($postId);
		$message = 'Catégorie supprimée ! ';
		$this->editPostView($postId, $message);
	}

	public function editPostInfos($newPostInfos, $message = null)
	{
		$postId = $newPostInfos['postId'];
		$this->postManager->editPostInfos($newPostInfos, $postId);
		$this->postManager->dateUpdate($postId);
		$this->editPostView($postId, $message);
	}

	public function deletePost($postId, $dashboard = null)
	{
		$this->postManager->deletePost($postId);
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

	public function adminCommentsView($message = null, $sorting = null, $sortingDate = null)
	{
		$totalCommentsNb = $this->commentManager->getTotalCommentsNb();
		$approvedCommentsNb = $this->commentManager->getApprovedCommentsNb();
		$unapprovedCommentsNb = $totalCommentsNb - $approvedCommentsNb;

		if ($sorting != null)
		{
			$contentTitle = 'Commentaires non approuvés';
			$status = 0;
			$allComments = $this->commentManager->getComments($commentsNb = null, $status, $sortingDate);
		}
		else
		{
			$contentTitle = 'Tous les Commentaires';
			$allComments = $this->commentManager->getComments($commentsNb = null, $status = null, $sortingDate);
		}

		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'adminCommentsView', ['message' => $message,
			'totalCommentsNb' => $totalCommentsNb,
			'unapprovedCommentsNb' => $unapprovedCommentsNb,
			'contentTitle' => $contentTitle,
			'status' => $status,
			'allComments' => $allComments,
			'unreadContactsNb' => $unreadContactsNb]);
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
		


		$usersActivity = 1;
		$allUsers = $this->userManager->getUsers(null, $usersActivity, $userRoleId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'adminUsersView', ['allUsersNb' => $allUsersNb,
			'superAdminNb' => $superAdminNb,
			'adminNb' => $adminNb,
			'usersNb' => $usersNb,
			'contentTitle' => $contentTitle,
			'allUsers' => $allUsers,
			'unreadContactsNb' => $unreadContactsNb]);
	}

	public function profileUserView($userId, $message = null)
	{
		$userInfos = $this->userManager->getUserInfos($userId);
		$userPostsNb = $this->userManager->getUserPostsNb($userId);
		$userCommentsNb = $this->userManager->getUserCommentsNb($userId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'profileUserView', ['userInfos' => $userInfos,
			'userPostsNb' => $userPostsNb,
			'userCommentsNb' => $userCommentsNb,
			'unreadContactsNb' => $unreadContactsNb,
			'userId' => $userId,
			'message' => $message]);
	}

	public function editUserView($userId, $message = null)
	{
		$userInfos = $this->userManager->getUserInfos($userId);
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		return $this->view->render('backend', 'editUserView', ['userInfos' => $userInfos,
			'unreadContactsNb' => $unreadContactsNb,
			'userId' => $userId,
			'message' => $message]);
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
		$userInfos = $this->userManager->getUserInfos($userId);
		$oldAvatarUrl = $userInfos[0]['avatar'];
		unlink($oldAvatarUrl);

		$this->userManager->updateProfilePicture($userId, $avatarUrl);
	}

	public function adminContactsView($message = null, $sorting = null, $sortingDate = null)
	{
		$allContactsNb = $this->contactManager->getTotalContactsNb();
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();

		if ($sorting != null)
		{
			$contentTitle = 'Contacts non lus';
			$status = 1;
			$allContacts = $this->contactManager->getContacts($contactId = null, $status, $sortingDate);
		}
		else
		{
			$contentTitle = 'Tous les contacts';
			$allContacts = $this->contactManager->getContacts($contactId = null, $status = null, $sortingDate);
		}


		return $this->view->render('backend', 'adminContactsView', ['allContactsNb' => $allContactsNb,
			'unreadContactsNb' => $unreadContactsNb,
			'contentTitle' => $contentTitle,
			'allContacts' => $allContacts]);
	}

	public function adminContactView($contactId, $message = null)
	{
		$contactInfos = $this->contactManager->getContacts($contactId);
		$currentStatus = $this->contactManager->getContactStatus($contactId);
		
		if ($currentStatus != 3 )
		{
			$answerInfos = null;
			$this->contactManager->updateStatus($contactId, 2);
		}
		else
		{
			$answerInfos = $this->contactManager->getAnswer($contactId);
		}
		
		$unreadContactsNb = $this->contactManager->getUnreadContactsNb();
		
		return $this->view->render('backend', 'adminContactView', ['contactId' => $contactId,
			'message' => $message,
			'contactInfos' => $contactInfos,
			'currentStatus' => $currentStatus,
			'answerInfos' => $answerInfos,
			'unreadContactsNb' => $unreadContactsNb]);
	}

	public function deleteContact($contactId, $dashboard = null)
	{
		$this->contactManager->deleteContact($contactId);
		$message = "Message supprimé ! ";
		$this->adminContactsView($message);
		
	}

	public function adminAnswerEmail($contactId, $answerSubject, $answerContent, $email)
	{
		$contactInfos = $this->contactManager->getContacts($contactId);
		$contactInfos = $contactInfos->fetchAll(\PDO::FETCH_ASSOC);

		$subject = $answerSubject;
		$headers = "From: " . BLOG_AUTHOR . "/r/n";
		$message = $answerContent . 
					" /r/n
					----------------/r/n/r/n
					De: " . $contactInfos[0]['name'] . " <" . $email . ">/r/n
					Le: " . $contactInfos[0]['date_message'] . "/r/n
					Objet: " . $contactInfos[0]['subject'] . "/r/n/r/n"
					. $contactInfos[0]['content'];
			

		$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);	     
	}

	public function addAnswer($contactId, $answerSubject, $answerContent)
	{
		$this->contactManager->updateStatus($contactId, 3);   
		$this->contactManager->addAnswer($contactId, $answerSubject, $answerContent);
	}
	
}


