<?php

namespace controller;

require_once './model/Manager.php';
require_once './model/PostManager.php';
require_once './model/CommentManager.php';

use model\Manager;
use model\PostManager;
use model\CommentManager;
use view\View;

class PostController

{
	private $_postManager,
			$_commentManager,
			$_view;

	public function __construct()
	{
		$this->_postManager = new PostManager();
		$this->_commentManager = new CommentManager();
		$this->_view = new View();
	}

	public function listPostView($current_page, $postsPerPage)
	{
		$publishedPostsNb = $this->_postManager->getPublishedPostsNb();
		$page_number = $this->_postManager->getPagination($postsPerPage, $publishedPostsNb);
		$first_post = $this->_postManager->getFirstPost($current_page, $postsPerPage);
		$posts = $this->_postManager->getPosts($first_post, $postsPerPage);
		$recentPosts = $this->_postManager->getRecentPosts(1);
		$categories = $this->_postManager->getCategories();
		
		return $this->_view->render('frontend', 'postListView', 
			['posts' => $posts, 
			'postsPerPage' => $postsPerPage,
			'page_number' => $page_number,
			'recentPosts' => $recentPosts, 
			'categories' => $categories]);
	}

	public function postView($postId, $messageComment = null)
	{
		$postInfos = $this->_postManager->getPostInfos($postId);
		$postContents = $this->_postManager->getPostContents($postId);
		$postComments = $this->_commentManager->getpostComments($postId, 1);
		$postCategories = $this->_postManager->getPostCategories($postId);
		$recentPosts = $this->_postManager->getRecentPosts(1);
		
		return $this->_view->render('frontend', 'postView', ['postId' => $postId,
			'postInfos' => $postInfos,
			'postContents' => $postContents,
			'postComments' => $postComments,
			'postCategories' => $postCategories,
			'recentPosts' => $recentPosts,
			'messageComment' => $messageComment]
			);
	}

	public function addComment($postId, $userId, $content)
	{	
		$this->_commentManager->addComment($postId, $userId, $content);
		$messageComment = 'Votre commentaire a bien été envoyé, et est en attente de validation.';
		$this->postView($postId, $messageComment);
	}

}






