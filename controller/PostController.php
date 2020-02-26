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
		$posts = $this->postManager->getPosts(2, $first_post, $postsPerPage);
		$recentPosts = $this->postManager->getRecentPosts(2);
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
		$post = $this->postManager->getPostInfos($postId);
		$post->setCategories($this->postManager->getPostCategories($postId));
		$contents = $this->contentManager->getPostContents($postId);
		$postComments = $this->commentManager->getpostComments($postId, 1);
		$recentPosts = $this->postManager->getRecentPosts(2);
		
		return $this->view->render('frontend', 'postView', ['postId' => $postId,
			'post' => $post,
			'contents' => $contents,
			'postComments' => $postComments,
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






