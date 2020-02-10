<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');
require_once('./model/UserManager.php');

use model\PostManager;
use model\CommentManager;
use model\UserManager;

class AdminController

{
	private $_postManager;
	private $_commentManager;
	private $_userManager;

	public function __construct()
	{
		$this->_postManager = new PostManager();
		$this->_commentManager = new CommentManager();
		$this->_userManager = new UserManager();
	}

	public function dashboardView($message = null)
	{
		$publishedPostsNb = $this->_postManager->getPublishedPostsNb();
		$totalPostsNb = $this->_postManager->getTotalPostsNb();
		$approvedCommentsNb = $this->_commentManager->getApprovedCommentsNb();
		$totalCommentsNb = $this->_commentManager->getTotalCommentsNb();
		$usersNb = $this->_userManager->getUserNb();
		$recentPosts = $this->_postManager->getRecentPosts();
		$recentComments = $this->_commentManager->getComments(5);
		$recentUsers = $this->_userManager->getUsers(5);

		require('./view/backend/dashboardView.php');
	}

	public function adminPostsView($message = null)
	{
		$allPosts = $this->_postManager->getPosts($first_post = null, $postsPerPage = null, $nbComments = 1);
		$allPostsCategories = $this->_postManager->getAllPostsCategories();
		require('./view/backend/adminPostsView.php');
	}

	public function adminPostView($postId, $message = null)
	{
		$postInfos = $this->_postManager->getPostInfos($postId);
		$postContents = $this->_postManager->getPostContents($postId);
		$postComments = $this->_commentManager->getpostComments($postId);
		$postCategories = $this->_postManager->getPostCategories($postId);

		require('./view/backend/adminPostView.php');
	}

	public function adminNewPostView($message = null)
	{
		require('./view/backend/newPostInfosView.php');
	}

	public function newPostInfos($title, $chapo, $userId, $mainImage)
	{
		$postId = $this->_postManager->newPostInfos($title, $chapo, $userId, $mainImage);
				var_dump($postId);
		$this->editPostView($postId);
	}

	public function publishPost($postId, $status, $dashboard = null)
	{
		$this->_postManager->publishPost($postId, $status);
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
		$postInfos = $this->_postManager->getPostInfos($postId);
		$postContents = $this->_postManager->getPostContents($postId);
		$postCategories = $this->_postManager->getPostCategories($postId);
		require('./view/backend/editPostView.php');
	}

	public function editMainPostPicture($postId, $url)
	{
		$this->_postManager->updateMainPostPicture($postId, $url);
		$this->_postManager->dateUpdate($postId);
		$message = 'Photo modifiée ! ';
		$this->editPostView($postId, $message);
	}

	public function deleteMainPostPicture($postId)
	{
		$this->_postManager->deleteMainPostPicture($postId);
		$this->_postManager->dateUpdate($postId);
		$message = 'Photo supprimée ! ';
		$this->editPostView($postId, $message);
	}

	public function editPostPicture($postId, $contentId, $url)
	{
		$this->_postManager->updatePostPicture($contentId, $url);
		$this->_postManager->dateUpdate($postId);
		$message = 'Photo modifiée ! ';
		$this->editPostView($postId, $message);
	}

	public function deleteContent($postId, $contentId)
	{
		$this->_postManager->deleteContent($contentId);
		$this->_postManager->dateUpdate($postId);
		$message = 'Contenu supprimé ! ';
		$this->editPostView($postId, $message);
	}

	public function addParagraph($postId)
	{
		$this->_postManager->addParagraph($postId);
		$this->_postManager->dateUpdate($postId);
		$message = 'Bloc paragraphe ajouté ! ';
		$this->editPostView($postId, $message);
	}

	public function editParagraph($postId, $newParagraphs)
	{
		$this->_postManager->editParagraph($newParagraphs);
		$this->_postManager->dateUpdate($postId);
		$message = 'Bloc paragraphe enregistré ! ';
		$this->editPostView($postId, $message);
	}

	public function addPicture($postId, $content)
	{
		$this->_postManager->addPicture($postId, $content);
		$this->_postManager->dateUpdate($postId);
		$message = 'Image ajoutée ! ';
		$this->editPostView($postId, $message);
	}

	public function addCategory($postId, $category)
	{
		$message = $this->_postManager->addCategory($postId, $category);
		$this->_postManager->dateUpdate($postId);
		$this->editPostView($postId, $message);
	}

	public function deleteCategory($postId, $categoryId)
	{
		$this->_postManager->deleteCategory($postId, $categoryId);
		$this->_postManager->dateUpdate($postId);
		$message = 'Catégorie supprimée ! ';
		$this->editPostView($postId, $message);
	}

	public function editPostInfos($newPostInfos)
	{
		$postId = $newPostInfos['id'];
		$this->_postManager->editPostInfos($newPostInfos, $postId);
		$this->_postManager->dateUpdate($postId);
		$message = "Information(s) modifiée(s) ! ";
		$this->editPostView($postId, $message);
	}

	public function deletePost($postId, $dashboard = null)
	{
		$this->_postManager->deletePost($postId);
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

	public function adminCommentsView($message = null)
	{
		$allComments = $this->_commentManager->getComments();
		require('./view/backend/adminCommentsView.php');
	}

	public function approveComment($commentId, $dashboard = null)
	{
		$this->_commentManager->approveComment($commentId);
		$message = "Commentaire approuvé ! ";

		if ($dashboard != null)
		{
			$this->dashboardView($message);
		}
		else
		{
			$this->adminCommentsView($message);
		}
	}

	public function deleteComment($commentId, $dashboard = null)
	{
		$this->_commentManager->deleteComment($commentId);
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

	public function adminUsersView()
	{
		$usersActivity = 1;
		$allUsers = $this->_userManager->getUsers(null, $usersActivity);
		require('./view/backend/adminUsersView.php');
	}

	public function profileUserView($userId, $message = null)
	{
		$userInfos = $this->_userManager->getUserInfos($userId);
		$userPostsNb = $this->_userManager->getUserPostsNb($userId);
		$userCommentsNb = $this->_userManager->getUserCommentsNb($userId);
		require('./view/backend/profileUserView.php');
	}

	public function editUserView($userId)
	{
		$userInfos = $this->_userManager->getUserInfos($userId);
		require('./view/backend/editUserView.php');
	}

	public function editUserInfos($newUserInfos)
	{
		$userId = $newUserInfos['id'];
		$this->_userManager->editUserInfos($newUserInfos);
		$message = "Profil modifié ! ";
		$this->profileUserView($userId, $message);
	}

	public function updateProfilePicture($userId, $avatarUrl)
	{
		$this->_userManager->updateProfilePicture($userId, $avatarUrl);
		$message = 'Photo de profil modifiée';
		$this->profileUserView($userId, $message);
	}

	
}


