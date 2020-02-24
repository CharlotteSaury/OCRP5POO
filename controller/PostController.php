<?php

namespace controller;

require_once './controller/Controller.php';
use controller\Controller;

class PostController extends Controller

{
	public function listPostView($current_page, $postsPerPage)
	{
		$publishedPostsNb = $this->postManager->getPublishedPostsNb();
		$page_number = $this->postManager->getPagination($postsPerPage, $publishedPostsNb);
		$first_post = $this->postManager->getFirstPost($current_page, $postsPerPage);
		$posts = $this->postManager->getPosts($first_post, $postsPerPage);
		$recentPosts = $this->postManager->getRecentPosts(1);
		$categories = $this->postManager->getCategories();
		
		return $this->view->render('frontend', 'postListView', 
			['posts' => $posts, 
			'postsPerPage' => $postsPerPage,
			'page_number' => $page_number,
			'recentPosts' => $recentPosts, 
			'categories' => $categories]);
	}

	public function postView($postId, $messageComment = null)
	{
		$postInfos = $this->postManager->getPostInfos($postId);
		$postContents = $this->postManager->getPostContents($postId);
		$postComments = $this->commentManager->getpostComments($postId, 1);
		$postCategories = $this->postManager->getPostCategories($postId);
		$recentPosts = $this->postManager->getRecentPosts(1);
		
		return $this->view->render('frontend', 'postView', ['postId' => $postId,
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
		$this->commentManager->addComment($postId, $userId, $content);
		$messageComment = 'Votre commentaire a bien été envoyé, et est en attente de validation.';
		$this->postView($postId, $messageComment);
	}

}






