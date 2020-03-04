<?php

namespace src\controller;

use src\controller\HomeController;
use src\controller\PostController;
use src\controller\AdminController;
use src\controller\UserController;
use src\controller\ErrorController;
use config\Request;

use Exception;


class Router 
{
	private $_homeController,
	$_postController,
	$_userController,
	$_adminController,
	$_errorController,
	$request;

	public function __construct()
	{
		$this->_homeController = new HomeController();
		$this->_postController = new PostController();
		$this->_userController = new UserController();
		$this->_adminController = new AdminController();
		$this->_errorController = new ErrorController();
		$this->request = new Request();
	}

	public function connexionAuto($email)
	{
		$this->_userController->newUserSession($email);
		$this->routerRequest();
	}

	public function routerRequest()
	{
		$action = $this->request->getGet()->get('action');
		try
		{
			if (isset($action))
			{
				if ($action == 'listPosts') 
				{
					$get = $this->request->getGet();
					$sorting = $this->_postController->listPostSorting($get);
					$this->_postController->listPostView($sorting, $get);
				} 

				elseif ($action == 'postView') 
				{
					$this->_postController->postView($this->request->getGet()->get('id'));
				}

				elseif ($action == "addComment") 
				{
					$post = $this->request->getPost();
					$userId = $this->request->getSession()->get('id');
					$this->_postController->addComment($post, $userId);
				}

				elseif ($action == 'legalView') 
				{
					$this->_homeController->legalView();
				}

				elseif ($action == 'confidentiality') 
				{
					$this->_homeController->confidentialityView();
				}

				elseif ($action == 'inscriptionView')
				{
					$this->_userController->inscriptionView();
				}

				elseif ($action == 'inscription')
				{
					$post = $this->request->getPost();
					$this->_userController->inscription($post);
				}

				elseif ($action == 'activation')
				{
					$get = $this->request->getGet();
					$this->_userController->userActivation($get);
				}

				elseif ($action == 'connexionView')
				{
					$this->_userController->connexionView();
				}

				elseif ($action == 'connexion')
				{
					$post = $this->request->getPost();
					$this->_userController->connexion($post);
				}

				elseif ($action == 'deconnexion')
				{
					$session = $this->request->getSession();

					if ($session->get('id'))
					{
						$session->remove('id');
						$session->remove('pseudo');
						$session->remove('role');
						$session->remove('avatar');
						$session->remove('email');
						$session->stop();

						setcookie('auth', '', time()-3600, null, null, false, true);
					}
					$this->_homeController->indexView();
				}

				elseif ($action == 'forgotPassView')
				{
					$this->_userController->forgotPassView();
				}

				elseif ($action == 'forgotPassMail')
				{
					$email = $this->request->getPost()->get('email');
					$this->_userController->newPassCode($email);
				}

				elseif ($action == 'newPassView')
				{
					$get = $this->request->getGet();
					$this->_userController->checkReinitCode($get);
				}

				elseif ($action == 'newPass')
				{
					$post = $this->request->getPost();
					$email = $this->request->getSession()->get('email');
					$this->_userController->newPass($post, $email);
				}

				elseif ($action == 'admin' && $this->_userController->adminAccess())
				{
					$this->_adminController->dashboardView();
				}

				elseif ($action == 'adminPosts' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminPostsView($message = null, $this->request->getGet());
				}

				elseif ($action == 'adminPostView' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminPostView($this->request->getGet()->get('id'));
				}

				elseif ($action == 'adminNewPost' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminNewPostView();
				}

				elseif ($action == 'newPostInfos' && $this->_userController->adminAccess())
				{
					$post = $this->request->getPost();
					$post->set('userId', $this->request->getSession()->get('id'));
					$file = $this->request->getFile();

					$this->_adminController->newPostInfos($post, $file);
				}

				elseif ($action == 'editPostView' && $this->_userController->adminAccess())
				{
					$this->_adminController->editPostView($this->request->getGet()->get('id'));
				}

				elseif ($action == 'editPost' && $this->_userController->adminAccess())
				{
					$post = $this->request->getPost();

					if ($post->get('deleteMainPicture'))
					{
						$this->_adminController->deleteMainPostPicture($post->get('postId'));
					}

					elseif ($post->get('addParagraph'))
					{
						$this->_adminController->addParagraph($post->get('postId'));
					}

					elseif ($post->get('addCategory'))
					{
						$this->_adminController->addCategory($post);
					}

					elseif ($post->get('updatePostInfos'))
					{
						$this->_adminController->editPostInfos($post);
					}

					elseif ($post->get('editContent'))
					{
						$post = $this->request->getPost();
						$this->_adminController->editParagraph($post);		
					}

					elseif ($post->get('addPicture'))
					{
						$this->_adminController->addPicture($post);
					}

					elseif ($post->get('updatePicture'))
					{
						$this->_adminController->editPostPicture($post);
					}
				}

				elseif ($action == 'deleteContent' && $this->_userController->adminAccess())
				{
					$this->_adminController->deleteContent($this->request->getGet());
				}

				elseif ($action == 'deleteCategory' && $this->_userController->adminAccess())
				{
					$this->_adminController->deleteCategory($this->request->getGet());
				}

				elseif ($action == 'publishPost' || ($action == 'publishPostDashboard') && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();
					$this->_adminController->publishPost($get);
				}

				elseif (($action == 'deletePost') || ($action == 'deletePostDashboard') && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();
					$dashboard = ($action == 'deletePostDashboard') ? 1 : null;
					$this->_adminController->deletePost($get, $dashboard);	
				}

				elseif ($action == 'adminComments' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminCommentsView($message = null, $this->request->getGet());
				}

				elseif (($action == 'approveComment') || ($action == 'approveCommentDashboard') || ($action == 'approveCommentView') && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();
					$this->_adminController->approveComment($get);

				}

				elseif (($action == 'deleteComment') || ($action == 'deleteCommentDashboard') && $this->_userController->adminAccess())
				{
					$dashboard = ($action == 'deleteCommentDashboard') ? 1 : null;
					$this->_adminController->deleteComment($this->request->getGet()->get('id'), $dashboard);		
				}

				elseif ($action == 'adminUsers' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminUsersView( $this->request->getGet()->get('sort'));					
				}

				elseif ($action == 'profileUser')
				{
					$this->_adminController->profileUserView($this->request->getGet()->get('id'));
				}

				elseif ($action == 'editUser')
				{
					$this->_adminController->editUser($this->request->getGet()->get('id'));
				}

				elseif ($action == 'editUserInfos')
				{
					$post = $this->request->getPost();
					var_dump($post);
					$this->_adminController->editUserInfos($post);
				}

				elseif ($action == 'updateProfilePicture')
				{
					$userId = $this->request->getGet()->get('id');
					$this->_adminController->updateProfilePicture($userId);
				}

				elseif ($action == 'adminContacts' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminContactsView($message = null, $this->request->getGet());
				}

				elseif ($action == 'contactForm')
				{
					$this->_homeController->newContactForm($this->request->getPost());
				}

				elseif ($action == 'contactView' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminContactView($this->request->getGet()->get('id'));				
				}

				elseif (($action == 'deleteContact') && $this->_userController->adminAccess())
				{
					$this->_adminController->deleteContact($this->request->getGet()->get('id'));				
				}

				elseif (($action == 'answer') && $this->_userController->adminAccess())
				{
					$this->_adminController->addAnswer($this->request->getPost());
				}

				else 
				{
					throw new Exception('La page demandée n\'existe pas');
				}

			}
			else
			{
				if (isset($_GET) && !empty($_GET))
				{
					throw new Exception("La page demandée n'existe pas.");
				}
				else
				{
					$this->_homeController->indexView();
				}
			}

		}
		catch(Exception $e)
		{
			$errorMessage = $e->getMessage();
			$this->_errorController->errorView($errorMessage);
		}
	}
}




