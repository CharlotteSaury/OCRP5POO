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
					$postId = $this->request->getGet()->get('id');
					if ($this->_postController->isValid($postId)) 
					{
						$this->_postController->postView($postId);
					}
					else 
					{
						throw new Exception('Identifiant de billet non valide');
					}	
				}

				elseif ($action == "addComment") 
				{
					$post = $this->request->getPost();
					$this->_postController->addComment($post);
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
					$message = $this->_userController->connexion($post);
					if (isset($message))
					{
						$this->_userController->connexionView($message);
					}
					else
					{
						$this->_homeController->indexView();
					}
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

					$reinit_code = $this->_userController->newPassCode($email);
					$message = $this->_userController->forgotPassMail($email, $reinit_code);

					$this->_userController->forgotPassView($message);
				}

				elseif ($action == 'newPassView')
				{
					$get = $this->request->getGet();
					$this->_userController->checkReinitCode($get);
				}

				elseif ($action == 'newPass')
				{
					$post = $this->request->getPost();
					$this->_userController->newPass($post);
				}

				elseif ($action == 'admin' && $this->_userController->adminAccess())
				{
					$this->_adminController->dashboardView();
				}

				elseif ($action == 'adminPosts' && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();

					if ($get->get('sort') || $get->get('date'))
					{
						$sorting = $this->_adminController->getSortingResults($get, 'unpublished');
						$this->_adminController->adminPostsView($message = null, $sorting, $get);	
					}
					else
					{
						$this->_adminController->adminPostsView($message = null, $sorting = null, $get);
					}
				}

				elseif ($action == 'adminPostView' && $this->_userController->adminAccess())
				{
					$postId = $this->request->getGet()->get('id');
					if ($this->_postController->isValid($postId))
					{
						$this->_adminController->adminPostView($postId);
					}
					else
					{
						throw new Exception('Identifiant de post non valide');
					}
				}

				elseif ($action == 'adminNewPost' && $this->_userController->adminAccess())
				{
					$this->_adminController->adminNewPostView();
				}

				elseif ($action == 'newPostInfos' && $this->_userController->adminAccess())
				{
					$post = $this->request->getPost();
					$file = $this->request->getFile();

					if ($file->get('picture') && $file->get('picture', 'error') != 4)
					{
						$uploadResults = $this->_homeController->pictureUpload($namePicture = 'picture');
						if (strrpos($uploadResults, 'uploads') === false)
						{
							$message = $uploadResults;
							$this->_adminController->adminNewPostView($message);
						}
						else
						{
							$mainImage = $uploadResults;
							$this->_adminController->NewPostInfos($post, $mainImage);
						}
					}
					else
					{
						$this->_adminController->NewPostInfos($post, $mainImage = null);
					}

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
						$file = $this->request->getFile();

						if ($file->get('MainPicture') && $file->get('MainPicture', 'error') != 4)
						{
							$uploadResults = $this->_homeController->pictureUpload($namePicture = 'MainPicture');

							if (strrpos($uploadResults, 'uploads') === false)
							{
								$message = "Information(s) modifiée(s) \n 
								/!\ Erreur de téléchargement de l'image principale : " . $uploadResults;
							}
							else
							{
								$post->set('main_image', $uploadResults);
								$message = "Information(s) modifiée(s) ! ";
							}
						}
						else
						{
							$message = "Information(s) modifiée(s) ! ";
						}
						$this->_adminController->editPostInfos($post, $message);

					}

					elseif ($post->get('editContent'))
					{
						$newParagraphs = [];

						foreach ($_POST as $key => $value)
						{
							if (is_numeric($key))
							{
								$newParagraphs[$key] = $value;
							}
						}

						$this->_adminController->editParagraph($post->get('postId'), $newParagraphs);
					}

					elseif ($post->get('addPicture'))
					{
						$uploadResults = $this->_homeController->pictureUpload($namePicture = 'picture');

						if (strrpos($uploadResults, 'uploads') === false)
						{
							$message = $uploadResults;
						}
						else
						{
							$this->_adminController->addPicture($post->get('postId'), $uploadResults);
							$message = 'Image ajoutée !';
						}

						$this->_adminController->editPostView($post->get('postId'), $message);
					}

					elseif ($post->get('updatePicture'))
					{
						foreach ($_FILES AS $key => $value)
						{
							if ($value['name'] !='')
							{
								$contentId = substr($key, 7);
							}
						}

						$uploadResults = $this->_homeController->pictureUpload($namePicture = 'picture' . $contentId);

						if (strrpos($uploadResults, 'uploads') === false)
						{
							$message = $uploadResults;
						}
						else
						{
							$this->_adminController->editPostPicture($post->get('postId'), $contentId, $uploadResults);
							$message = 'Image modifiée !';
						}

						$this->_adminController->editPostView($post->get('postId'), $message);
					}
				}

				elseif ($action == 'deleteContent' && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();
					$this->_adminController->deleteContent($get);
				}

				elseif ($action == 'deleteCategory' && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();
					$this->_adminController->deleteCategory($get);
				}

				elseif ($action == 'publishPost' || ($action == 'publishPostDashboard') && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();

					$dashboard = ($action == 'publishPostDashboard') ? 1 : null ;
					$this->_adminController->publishPost($get, $dashboard);
				}

				elseif (($action == 'deletePost') || ($action == 'deletePostDashboard') && $this->_userController->adminAccess())
				{
					$postId = $this->request->getGet()->get('id');

					$dashboard = ($action == 'deletePostDashboard') ? 1 : null;
					$this->_adminController->deletePost($postId, $dashboard);	
				}

				elseif ($action == 'adminComments' && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();

					if ($get->get('sort') || $get->get('date'))
					{
						$sorting = $this->_adminController->getSortingResults($get, 'unapproved');
						$this->_adminController->adminCommentsView($message = null, $sorting, $get);	
					}
					else
					{
						$this->_adminController->adminCommentsView($message = null, $sorting = null, $get);
					}
				}

				elseif (($action == 'approveComment') || ($action == 'approveCommentDashboard') || ($action == 'approveCommentView') && $this->_userController->adminAccess())
				{

					if ($action == 'approveCommentDashboard')
					{
						$view = 1;
						$postId = null;
					}
					elseif ($action == 'approveCommentView')
					{
						$view = 2;
						$postId = $this->request->getGet()->get('post');
					}
					else
					{
						$view = $postId = null;
					}					
					$this->_adminController->approveComment($this->request->getGet()->get('id'), $view, $postId);
				}

				elseif (($action == 'deleteComment') || ($action == 'deleteCommentDashboard') && $this->_userController->adminAccess())
				{
					$dashboard = ($action == 'deleteCommentDashboard') ? 1 : null;
					$this->_adminController->deleteComment($this->request->getGet()->get('id'), $dashboard);		
				}

				elseif ($action == 'adminUsers' && $this->_userController->adminAccess())
				{
					$userRoleId = $this->request->getGet()->get('sort');

					if ($userRoleId != null)
					{
						if (in_array($userRoleId, ['1', '2', '3']))
						{
							$this->_adminController->adminUsersView($userRoleId);
						}
						else
						{
							throw new Exception("La page que vous recherchez n'existe pas. ");
						}
					}
					else
					{
						$this->_adminController->adminUsersView();
					}
					
				}

				elseif ($action == 'profileUser')
				{
					if ($this->_userController->isValid($this->request->getGet()->get('id')))
					{
						$this->_adminController->profileUserView($this->request->getGet()->get('id'));
					}
					else
					{
						throw new Exception('Le profil demandé n\'existe pas');
					}
					
				}

				elseif ($action == 'editUser')
				{
					$userId = $this->request->getGet()->get('id');
					
					if ($this->_userController->isValid($userId))
					{
						$currentUserId = $this->request->getSession()->get('id');

						if ($currentUserId == $userId || $this->_userController->adminAccess())
						{
							$this->_adminController->editUserView($userId);
						}
						else
						{
							throw new Exception('Vous n\'avez pas accès à cette page');
						}
					}
					else
					{
						throw new Exception('Le profil demandé n\'existe pas');
					}
				}

				elseif ($action == 'editUserInfos')
				{
					$newUserInfos = [];

					foreach ($_POST as $key => $value)
					{
						$newUserInfos[$key] = $value;
					}

					if (strlen($newUserInfos['pseudo']) < 25)
					{
						if (filter_var($newUserInfos['email'], FILTER_VALIDATE_EMAIL))
						{
							if (($this->_userController->checkEmail($newUserInfos['email'], $newUserInfos['id'])) || ($this->_userController->checkPseudo($newUserInfos['pseudo'], $newUserInfos['id'])))
							{
								if ($this->_userController->checkEmail($newUserInfos['email'], $newUserInfos['id']))
								{
									$message = "Cet email est déjà associé à un compte.";

									$this->_adminController->editUserView($newUserInfos['id'], $message);
								}
								else
								{
									$message = "Ce pseudo n'est pas disponible. Merci d'en choisir un nouveau.";
									$this->_adminController->editUserView($newUserInfos['id'], $message);
								}								
							}
							else
							{
								if (!isset($newUserInfos['user_role_id']))
								{
									$role = $this->request->getSession()->get('role');
									$newUserInfos['user_role_id'] = $role;
								}

								$date = $newUserInfos['birth_date'];

								if (empty($date))
								{
									unset($newUserInfos['birth_date']);
									$this->_adminController->deleteBirthDate($newUserInfos['id']);

									$email = $newUserInfos['email'];
									$userId = $newUserInfos['id'];
									$currentUserId = $this->request->getSession()->get('id');	

									$this->_adminController->editUserInfos($newUserInfos);

										// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

									if ($currentUserId == $userId)
									{
										$this->_userController->newUserSession($email);
									}
								}
								else
								{
									$checkDate = explode('-', $date);						
									if (!preg_match('#^([0-9]{2})(-)([0-9]{2})(-)([0-9]{4})$#' , $date)) // check date format (DD/MM/AAAA)
									{
										$message = "La date de naissance n'est pas dans le format autorisé.";
										$this->_adminController->editUserView($newUserInfos['id'], $message);
									}
									elseif (!checkdate($checkDate[1], $checkDate[0], $checkDate[2]) || ($checkDate[2] < 1900 )) // check date validity
									{
										$message = "La date de naissance n'est pas valide.";
										$this->_adminController->editUserView($newUserInfos['id'], $message);
									}
									else // date valide
									{
										$newUserInfos['birth_date'] = $checkDate[2] . '-' . $checkDate[1] . '-' . $checkDate[0];
										$email = $newUserInfos['email'];
										$userId = $newUserInfos['id'];
										$currentUserId = $this->request->getSession()->get('id');	

										$this->_adminController->editUserInfos($newUserInfos);

											// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

										if ($currentUserId == $userId)
										{
											$this->_userController->newUserSession($email);
										}

									}
								}
									
							}

						}
						else
						{
							$message = 'L\'adresse email n\'est pas valide !';
							$this->_adminController->editUserView($message);
						}							
					}
					else
					{
						$message = 'Le pseudo ne doit pas dépasser 25 caractères ! ';
						$this->_adminController->editUserView($message);
					}	
				}

				elseif ($action == 'updateProfilePicture')
				{
					$userId = $this->request->getGet()->get('id');
					$uploadResults = $this->_homeController->pictureUpload($namePicture = 'picture');

					if (strrpos($uploadResults, 'uploads') === false)
					{
						$message = $uploadResults;
						$this->_adminController->profileUserView($userId, $message);
					}
					else
					{
						$this->_adminController->updateProfilePicture($userId, $uploadResults);
						$message = 'Photo de profil modifiée !';

						$this->_adminController->profileUserView($userId, $message);

						// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

						$currentUserId = $this->request->getSession()->get('id');	
						if ($currentUserId == $userId)
						{
							$_SESSION['avatar'] = $uploadResults;
						}
					}
				}

				elseif ($action == 'adminContacts' && $this->_userController->adminAccess())
				{
					$get = $this->request->getGet();

					if ($get->get('sort') || $get->get('date'))
					{
						$sorting = $this->_adminController->getSortingResults($get, 'unread');
						$this->_adminController->adminContactsView($message = null, $sorting, $get);	
					}
					else
					{
						$this->_adminController->adminContactsView($message = null, $sorting = null, $get);
					}
				}

				elseif ($action == 'contactForm')
				{
					$post = $this->request->getPost();
					$contactId = $this->_homeController->newContactForm($post);

					$this->_homeController->mailContactForm($post, $contactId);

					$message = "Votre message a bien été envoyé. Nous vous remercions et vous recontacterons dans les plus brefs délais.";
					$this->_homeController->indexView($message);
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
					$post = $this->request->getPost();

					$this->_adminController->addAnswer($post);
					$this->_adminController->adminAnswerEmail($post);

					$message = "La réponse a bien été envoyée.";
					$this->_adminController->adminContactView($post->get('id'), $message);  			
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




