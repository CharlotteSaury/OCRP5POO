<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');

use model\Manager;
use model\PostManager;
use model\CommentManager;

class PostController extends controller

{
	public function listPostView($current_page, $postsNb)
	{
		$postManager = new PostManager();

		$page_number = $postManager->getPagination($postsNb);
		$first_post = $postManager->getFirstPost($current_page, $postsNb);
		$posts = $postManager->getPosts($first_post, $postsNb);
		$recentPosts = $postManager->getRecentPosts();
		$categories = $postManager->getCategories();
		require('./view/frontend/postListView.php');
	}

	public function postView()
	{
		$postManager = new PostManager();
		$commentManager = new CommentManager();

		$postInfos = $postManager->getPostInfos($_GET['id']);
		$postContents = $postManager->getPostContents($_GET['id']);
		$postComments = $commentManager->getpostComments($_GET['id']);
		$postCategories = $postManager->getPostCategories($_GET['id']);
		$recentPosts = $postManager->getRecentPosts();
		require('./view/frontend/postView.php');
	}
}






