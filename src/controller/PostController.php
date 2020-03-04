<?php

namespace src\controller;

use src\controller\Controller;
use src\constraint\Validation;
use config\Parameter;
use Exception;

class PostController extends Controller

{
	public function listPostSorting(Parameter $get)
	{
		if ($get->get('page'))
		{
			if ($get->get('page') > 0)
			{
				$current_page = $get->get('page');
			}
			else
			{
				throw new Exception('Numéro de page erroné');
			}
		}
		else 
		{
			$current_page = 1;
		}

		if ($get->get('postsPerPage'))
		{
			if (in_array($get->get('postsPerPage'), ['3', '5', '10']))
			{
				$postsPerPage = $get->get('postsPerPage');
			}
			else
			{
				throw new Exception('Le nombre de posts par page demandé n\'est pas pris en charge.');
			}
		}
		else 
		{
			$postsPerPage = 3;
		}

		return [$current_page, $postsPerPage];
	}

	public function listPostView($sorting = [], Parameter $get = null)
	{
		$current_page = $sorting[0];
		$postsPerPage = $sorting[1];
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$page_number = $this->postManager->getPagination($postsPerPage, $publishedPostsNb);
		$first_post = $this->postManager->getFirstPost($current_page, $postsPerPage);
		$posts = $this->postManager->getPosts(2, $first_post, $postsPerPage);

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

		$recentPosts = $this->postManager->getRecentPosts(2);
		
		return $this->view->render('frontend', 'postListView', 
			['posts' => $posts, 
			'postsPerPage' => $postsPerPage,
			'page_number' => $page_number,
			'recentPosts' => $recentPosts,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	public function postView($postId, $messageComment = null, $errors = null)
	{
		$errorExists = $this->validation->exists('postId', $postId);

		if (!$errorExists)
		{
			$post = $this->postManager->getPostInfos($postId);
			$post->setCategories($this->postManager->getPostsCategories($postId));
			$contents = $this->contentManager->getPostContents($postId);
			$postComments = $this->commentManager->getpostComments($postId, 1);
			$recentPosts = $this->postManager->getRecentPosts(2);

			return $this->view->render('frontend', 'postView', ['postId' => $postId,
				'post' => $post,
				'contents' => $contents,
				'postComments' => $postComments,
				'recentPosts' => $recentPosts,
				'messageComment' => $messageComment,
				'session' => $this->request->getSession(),
				'errors' => $errors]);
		}
		else
		{
			throw new Exception('Identifiant de billet non valide.');
		}
		
	}

	public function addComment(Parameter $post, $userId)
	{
		$errors = $this->validation->validate($post, 'Comment');
		if (!$errors)
		{
			$this->commentManager->addComment($post, $userId);
			$messageComment = 'Votre commentaire a bien été envoyé, et est en attente de validation.';
			$this->postView($post->get('postId'), $messageComment);
		}
		else
		{
			if (isset($errors['postId']))
			{
				throw new Exception($errors['postId']);
			}
			else
			{
				$this->postView($post->get('postId'), $messageComment = null, $errors);
			}
		}
		
	}

}






