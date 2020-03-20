<?php

namespace Src\Controller;

use Src\Controller\Controller;
use Src\Constraint\Validation;
use Src\Config\Parameter;
use Exception;

/**
 * Class PostController
 * Manage router to appropriate manager redirection associated with post actions
 */
class PostController extends Controller

{
	/**
	 * Get sorting information from url used in posts list view
	 * @param  Parameter $get [optional page number, optional postsPerPage number]
	 * @return array [current page and posts per page number]
	 */
	public function listPostSorting(Parameter $get)
	{
		if ($get->get('page')) {

			if ($get->get('page') > 0) {
				$current_page = $get->get('page');
			
			} else {
				throw new Exception('Numéro de page erroné');
			}
		
		} else {

			$current_page = 1;
		}

		if ($get->get('postsPerPage')) {

			if (in_array($get->get('postsPerPage'), ['3', '5', '10'])) {
				$postsPerPage = $get->get('postsPerPage');
			
			} else {
				throw new Exception('Le nombre de posts par page demandé n\'est pas pris en charge.');
			}
		
		} else {
			$postsPerPage = 3;
		}

		return [$current_page, $postsPerPage];
	}

	/**
	 * Generate posts list page
	 * @param  Parameter $get [optional sorting informations]
	 * @return void
	 */
	public function listPostView(Parameter $get = null)
	{
		$sorting = $this->listPostSorting($get);
		$current_page = $sorting[0];
		$postsPerPage = $sorting[1];
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$page_number = ceil((int)$publishedPostsNb/$postsPerPage);
		$first_post = $current_page*$postsPerPage-$postsPerPage;
		$posts = $this->postManager->getPosts(2, $first_post, $postsPerPage);

		$allPostsCategories = $this->postManager->getPostsCategories();

		foreach ($allPostsCategories as $key => $value) {
			foreach ($posts as $post) {
				if ($post->getId() == $key) {
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

	/**
	 * Generate single post page
	 * @param  int $postId
	 * @return void
	 */
	public function postView($postId)
	{
		$errorExists = $this->validation->exists('postId', $postId);

		if (!$errorExists) {

			$post = $this->postManager->getPostInfos($postId);
			$post->setCategories($this->postManager->getPostsCategories($postId));
			$contents = $this->contentManager->getContents($postId);
			$postComments = $this->commentManager->getpostComments($postId, 2);
			$recentPosts = $this->postManager->getRecentPosts(2);

			return $this->view->render('frontend', 'postView',
				['postId' => $postId,
				'post' => $post,
				'contents' => $contents,
				'postComments' => $postComments,
				'recentPosts' => $recentPosts,
				'session' => $this->request->getSession()]);
		
		} else {
			throw new Exception('Identifiant de billet non valide.');
		}
		
	}

	/**
	 * Check validity of comment form input
	 * @param Parameter $post [comment content]
	 * @param int $userId
	 * @return  void [redirect to postView]
	 */
	public function addComment(Parameter $post, $userId)
	{
		$errors = $this->validation->validate($post, 'Comment');

		if (!$errors) {

			$this->commentManager->addComment($post, $userId);
			$this->request->getSession()->set('message', 'Votre commentaire a bien été envoyé, et est en attente de validation.');
			header('Location: index.php?action=postView&id=' . $post->get('postId'));
		
		} else {
			
			if (isset($errors['postId'])) {
				throw new Exception($errors['postId']);
			
			} else {
				$this->request->getSession()->set('errors', $errors);
				header('Location: index.php?action=postView&id=' . $post->get('postId'));
			}
		}
	}
}






