<?php

namespace controller;

require_once('controller\HomeController.php');
require_once('controller\PostController.php');
require_once('controller\CommentController.php');
require_once('controller\AdminController.php');

use controller\HomeController;
use controller\PostController;
use controller\CommentController;
use controller\AdminController;
use Exception;


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

	private function getParameter($table, $name)
	{
		if (isset($table[$name]))
		{
			return htmlspecialchars($table[$name]);
		}
		else
		{
			throw new Exception ("Paramètre " . $name . " absent.");
		}
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
								$current_page =htmlspecialchars($_GET['page']);
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

						if (isset($_GET['postsPerPage']))
						{
							$postsNb = htmlspecialchars($_GET['postsPerPage']);
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
						$postId = $this->getParameter($_GET, 'id');
						if ($postId>0) 
						{
							$infos = new PostController();
							$infos->postView($postId);
						}
						else 
						{
							throw new Exception('Identifiant de billet non valide');
						}	
					}

					elseif ($_GET['action'] == "addComment") 
					{
						$postId = $this->getParameter($_POST, 'postId');
						$email = $this->getParameter($_POST, 'email');
						$content = $this->getParameter($_POST, 'content');

						$infos = new PostController();
						$infos->addComment($postId, $email, $content);
						
					}

					elseif ($_GET['action'] == 'home') 
					{
						$infos = new HomeController();
						$infos->indexView();
					}

					elseif ($_GET['action'] == 'admin')
					{
						$infos = new AdminController();
						$infos->dashboardView();
					}

					elseif ($_GET['action'] == 'adminPosts')
					{
						$infos = new AdminController();
						$infos->adminPostsView();
					}

					elseif ($_GET['action'] == 'adminPostView')
					{
						$postId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();
						$infos->adminPostView($postId);
					}

					elseif ($_GET['action'] == 'adminNewPost')
					{
						$infos = new AdminController();
						$infos->adminNewPostView();
					}

					elseif ($_GET['action'] == 'newPostInfos')
					{
						$title = $this->getParameter($_POST, 'title');
						$chapo = $this->getParameter($_POST, 'chapo');
						$userId = $this->getParameter($_POST, 'user_id');
						$mainImage = $this->getParameter($_POST, 'main_image');

						$infos = new AdminController();
						$infos->NewPostInfos($title, $chapo, $userId, $mainImage);
					}

					elseif ($_GET['action'] == 'editPostView')
					{
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->editPostView($postId);
					}

					elseif ($_GET['action'] == 'editPost')
					{
						if (isset($_POST['updateMainPicture']))
						{
							$postId = $this->getParameter($_POST, 'postId');
							$url = $this->getParameter($_POST, 'main_image');
							$infos = new AdminController();
							$infos->editMainPostPicture($postId, $url);
						}

						elseif (isset($_POST['deleteMainPicture']))
						{
							$postId = $this->getParameter($_POST, 'postId');
							$infos = new AdminController();
							$infos->deleteMainPostPicture($postId);
						}
						

						elseif (isset($_POST['addParagraph']))
						{
							$postId = $this->getParameter($_POST, 'postId');
							$infos = new AdminController();
							$infos->addParagraph($postId);
						}

						elseif (isset($_POST['addPicture']))
						{
							$postId = $this->getParameter($_POST, 'postId');
							$content = $this->getParameter($_POST, 'image_url');
							var_dump($postId);
							$infos = new AdminController();
							$infos->addPicture($postId, $content);
						}

						elseif (isset($_POST['updatePicture']))
						{
							$postId = $this->getParameter($_POST, 'postId');
							$contentId = $this->getParameter($_POST, 'contentId');
							$url = $this->getParameter($_POST, 'content');
							var_dump($postId, $contentId, $url);
							$infos = new AdminController();
							$infos->editPostPicture($postId, $contentId, $url);
						}
						
						elseif (isset($_POST['addCategory']))
						{
							$category = $this->getParameter($_POST, 'categoryName');
							$postId = $this->getParameter($_POST, 'postId');
							$infos = new AdminController();
							$infos->addCategory($postId, $category);
						}

						elseif (isset($_POST['updatePostInfos']))
						{
							$newPostInfos = [];

							foreach ($_POST as $key => $value)
							{
								if ($_POST[$key] != '')
								{
									$newPostInfos[$key] = $value;
								}
							}

							$infos = new AdminController();
							$infos->editPostInfos($newPostInfos);
						}

						elseif (isset($_POST['editContent']))
						{
							$newPostcontents = [];
							var_dump($_POST);

							foreach ($_POST as $key => $value)
							{
								if (!is_numeric($_POST[$key]))
								{
									$newPostcontents[$key] = $value;
								}
							}
							var_dump($newPostcontents);
							/*
							$infos = new AdminController();
							$infos->editPost($newPostcontents);
							*/
						}

						
					}

					elseif ($_GET['action'] == 'deleteContent')
					{
						$contentId = $this->getParameter($_GET, 'content');
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->deleteContent($postId, $contentId);
					}

					elseif ($_GET['action'] == 'deleteCategory')
					{
						$categoryId = $this->getParameter($_GET, 'cat');
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->deleteCategory($postId, $categoryId);
					}

					elseif ($_GET['action'] == 'publishPost' || ($_GET['action'] == 'publishPostDashboard'))
					{
						$postId = $this->getParameter($_GET, 'id');
						$status = $this->getParameter($_GET, 'status');
						$infos = new AdminController();

						if ($_GET['action'] == 'publishPostDashboard')
						{
							$dashboard = 1;
							$infos->publishPost($postId, $status, $dashboard);
						}
						else
						{
							$infos->publishPost($postId, $status);
						}
						
					}

					elseif (($_GET['action'] == 'deletePost') || ($_GET['action'] == 'deletePostDashboard'))
					{
						$postId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();						
						if ($_GET['action'] == 'deletePostDashboard')
						{
							$dashboard = 1;
							$infos->deletePost($postId, $dashboard);
						}
						else
						{
							$infos->deletePost($postId);
						}					
					}

					elseif ($_GET['action'] == 'adminComments')
					{
						$infos = new AdminController();
						$infos->adminCommentsView();
					}

					elseif (($_GET['action'] == 'approveComment') || ($_GET['action'] == 'approveCommentDashboard'))
					{
						$commentId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();						
						if ($_GET['action'] == 'approveCommentDashboard')
						{
							$dashboard = 1;
							$infos->approveComment($commentId, $dashboard);
						}
						else
						{
							$infos->approveComment($commentId);
						}					
					}

					elseif (($_GET['action'] == 'deleteComment') || ($_GET['action'] == 'deleteCommentDashboard'))
					{
						$commentId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();						
						if ($_GET['action'] == 'deleteCommentDashboard')
						{
							$dashboard = 1;
							$infos->deleteComment($commentId, $dashboard);
						}
						else
						{
							$infos->deleteComment($commentId);
						}					
					}

					elseif ($_GET['action'] == 'adminUsers')
					{
						$infos = new AdminController();
						$infos->adminUsersView();
					}

					elseif ($_GET['action'] == 'profileUser')
					{
						$userId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->profileUserView($userId);
					}

					elseif ($_GET['action'] == 'editUser')
					{
						$userId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->editUserView($userId);
					}

					elseif ($_GET['action'] == 'editUserInfos')
					{
						$newUserInfos = [];

						foreach ($_POST as $key => $value)
						{
							if ($_POST[$key] != '')
							{
								$newUserInfos[$key] = $value;
							}
						}
												
						$infos = new AdminController();
						$infos->editUserInfos($newUserInfos);
					}

					elseif ($_GET['action'] == 'updateProfilePicture')
					{
						$userId = $this->getParameter($_GET, 'id');
						$avatarUrl = $this->getParameter($_POST, 'avatar');
						$infos = new AdminController();
						$infos->updateProfilePicture($userId, $avatarUrl);
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




