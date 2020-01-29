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
		$recentUsers = $this->_userManager->getRecentUsers();

		require('./view/backend/dashboardView.php');
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
}


