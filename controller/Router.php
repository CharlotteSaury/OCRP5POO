<?php

namespace controller;

require_once('controller\HomeController.php');
require_once('controller\PostController.php');
require_once('controller\CommentController.php');

use controller\HomeController;
use controller\PostController;
use controller\CommentController;


class Router 
{
	private $_homeController,
			$_postController,
			$_commentController;

	public function __construct()
	{
		$this->_homeController = new HomeController();
		$this->_postController = new PostController();
		$this->_commentController = new CommentController();
	}

	public function routerRequest()
	{
		try
		{
			if (isset($_GET['action']))
			{
				if ($_GET['action'] !='')
				{
					if ($_GET['action'] == 'listPosts') 
					{
						if (isset($_GET['page']))
						{
							if ($_GET['page']>0)
							{
								$current_page = htmlspecialchars($_GET['page']);
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

						if (isset($_GET['postsNb']))
						{
							$postsNb = htmlspecialchars($_GET['postsNb']);
						}
						else
						{
							$postsNb = 3;
						}

						$infos = new PostController();
						$infos->listPostView($current_page, $postsNb);
					} 

					elseif (($_GET['action'] == 'postView')) 
					{
						if ((isset($_GET['id'])) && ($_GET['id'])>0) 
						{
							$postId = $_GET['id'];
							$infos = new PostController();
							$infos->postView($postId);
						}
						else 
						{
							throw new Exception('Aucun identifiant de post envoyé');
						}	
					}

					elseif ($_GET['action'] == 'home') 
					{
						$infos = new HomeController();
						$infos->indexView();
					}
					else 
					{
						throw new Exception('Action inconnue');
					}
				}
				else
				{
					$infos = new HomeController();
					$infos->indexView();
				}
			} 

			else 
			{
				$infos = new HomeController();
				$infos->indexView();
			}

		}
		catch(Exception $e)
		{
			$errorMessage = $e->getMessage();
			require('view/errorView.php');
		}
	}
}




