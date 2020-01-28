<?php

require_once('controller\Controller.php');
require_once('controller\PostController.php');
require_once('controller\CommentController.php');
require_once('controller\UserController.php');

use controller\Controller;
use controller\PostController;
use controller\CommentController;
use controller\UserController;

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
					$infos = new PostController();
					$infos->postView();
				}
				else 
				{
					throw new Exception('Aucun identifiant de post envoyé');
				}	
			}
			
			elseif ($_GET['action'] == 'home') 
			{
				$infos = new Controller();
				$infos->indexView();
			}
			else 
			{
				throw new Exception('Action inconnue');
			}
		}
		else
		{
			$infos = new Controller();
			$infos->indexView();
		}
	} 

	else 
	{
		$infos = new Controller();
		$infos->indexView();
	}

}
catch(Exception $e)
{
	$errorMessage = $e->getMessage();
	require('view/errorView.php');
}



