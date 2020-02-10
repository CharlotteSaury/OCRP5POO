<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');

use model\Manager;
use model\PostManager;
use model\CommentManager;

class PostController

{
	private $_postManager;
	private $_commentManager;

	public function __construct()
	{
		$this->_postManager = new PostManager();
		$this->_commentManager = new CommentManager();
	}

	public function listPostView($current_page, $postsPerPage)
	{
		$publishedPostsNb = $this->_postManager->getPublishedPostsNb();
		$page_number = $this->_postManager->getPagination($postsPerPage, $publishedPostsNb);
		$first_post = $this->_postManager->getFirstPost($current_page, $postsPerPage);
		$posts = $this->_postManager->getPosts($first_post, $postsPerPage);
		$recentPosts = $this->_postManager->getRecentPosts(1);
		$categories = $this->_postManager->getCategories();
		require('./view/frontend/postListView.php');
	}

	public function postView($postId)
	{
		$postInfos = $this->_postManager->getPostInfos($postId);
		$postContents = $this->_postManager->getPostContents($postId);
		$postComments = $this->_commentManager->getpostComments($postId, 1);
		$postCategories = $this->_postManager->getPostCategories($postId);
		$recentPosts = $this->_postManager->getRecentPosts(1);
		require('./view/frontend/postView.php');
	}

	public function addComment($postId, $email, $content)
	{	
		$this->_commentManager->addComment($postId, $email, $content);
		$this->postView($postId);
	}

}






