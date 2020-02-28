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

	private function adminAccess()
	{
		if (isset($_SESSION['id']))
		{
			$userId = htmlspecialchars($_SESSION['id']);

			if ($this->_userController->isAdmin($userId) || $this->_userController->isSuperAdmin($userId))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
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
				if ($action === 'listPosts') 
				{
					$page = $this->request->getGet()->get('page');
					if (isset($page))
					{
						if ($page > 0)
						{
							$current_page = $this->request->getGet()->get('page');
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

					$postsPerPage = $this->request->getGet()->get('postsPerPage');
					if (isset($postsPerPage))
					{
						$postsNb = $this->request->getGet()->get('postsPerPage');
					}
					else
					{
						$postsNb = 3;
					}

					$this->_postController->listPostView($current_page, $postsNb);
				} 

				elseif ($action == 'postView') 
				{
					$postId = $this->request->getGet()->get('id');
					if ($postId > 0) 
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
					$postId = $this->getParameter($_POST, 'postId');
					$userId = $this->getParameter($_POST, 'userId');
					$content = $this->getParameter($_POST, 'content');

					$this->_postController->addComment($postId, $userId, $content);

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
					$pseudo = $this->getParameter($_POST, 'pseudo');
					$pass1 = $this->getParameter($_POST, 'pass1');
					$pass2 = $this->getParameter($_POST, 'pass2');
					$email = $this->getParameter($_POST, 'email');

					if (strlen($pseudo) < 25)
					{
						if (filter_var($email, FILTER_VALIDATE_EMAIL))
						{
							if ($pass1 == $pass2)
							{
								$pass = password_hash($pass1, PASSWORD_DEFAULT);

								if (($this->_userController->checkEmail($email)) || ($this->_userController->checkPseudo($pseudo)) )
								{
									if ($this->_userController->checkEmail($email))
									{
										$message = "Cet email est déjà associé à un compte. Essayez de vous connecter !";
										$this->_userController->connexionView($message);
									}
									else
									{
										$message = "Ce pseudo n'est pas disponible. Merci d'en choisir un nouveau.";
										$this->_userController->inscriptionView($message);
									}								
								}
								else
								{
									$activation_code = $this->_userController->newUser($pseudo, $pass, $email);
									$this->_userController->sendEmailActivation($email, $pseudo, $activation_code);
									$message = 'Merci pour votre inscription ! Un email de confirmation vous a été envoyé afin de confirmer votre adresse email. Merci de vous reporter à cet email pour activer votre compte ! ';
									$this->_userController->inscriptionView($message);
								}
							}
							else
							{
								$message = 'Les mots de passe saisis sont différents ! ';
								$this->_userController->inscriptionView($message);
							}
						}
						else
						{
							$message = 'L\'adresse email n\'est pas valide !';
							$this->_userController->inscriptionView($message);
						}							
					}
					else
					{
						$message = 'Le pseudo ne doit pas dépasser 25 caractères ! ';
						$this->_userController->inscriptionView($message);
					}				

				}


				elseif ($action == 'activation')
				{
					$email = $this->request->getGet()->get('email');
					$key = $this->$this->request->getGet()->get('key');

					if ($this->_userController->userActivated($email))
					{
						$message = 'Votre compte est déjà activé, vous pouvez vous connecter ! ';
						$this->_userController->connexionView($message);
					}
					else
					{
						$message = $this->_userController->userActivation($email, $key);
						$this->_userController->connexionView($message);
					}

				}

				elseif ($action == 'connexionView')
				{
					$this->_userController->connexionView();
				}

				elseif ($action == 'connexion')
				{
					$email = $this->getParameter($_POST, 'email');
					$pass = $this->getParameter($_POST, 'pass');

					if ($this->_userController->checkEmail($email))
					{
						if (!$this->_userController->userActivated($email))
						{
							$message = 'Votre compte n\'est pas activé. Veuillez cliquer sur le lien d\'activation qui vous a été envoyé sur votre adresse email lors de votre inscription.';
							$this->_userController->connexionView($message);
						}
						else
						{
							$user_pass = $this->_userController->getPassword($email);

							if (password_verify($pass, $user_pass))
							{
								if (isset($_POST['rememberme']))
								{
									setcookie('email', $email, time() + 365*24*3600, null, null, false, true);
									setcookie('auth', password_hash($email, PASSWORD_DEFAULT) . '-----' . password_hash($_SERVER['REMOTE_ADDR'], PASSWORD_DEFAULT), time() + 365*24*3600, null, null, false, true);
								}
								$this->_userController->newUserSession($email);

								$this->_homeController->indexView();
							}
							else
							{
								$message = "L'identifiant et/ou le mot de passe sont erronés.";
								$this->_userController->connexionView($message);
							}
						}							
					}				
					else
					{
						$message = "L'adresse email saisie est inconnue";
						$this->_userController->connexionView($message);
					}

				}

				elseif ($action == 'deconnexion')
				{
					if (isset($_SESSION['id']))
					{
						$_SESSION = array();
						session_destroy();
						setcookie('auth', '', time()-3600, null, null, false, true);

						$this->_homeController->indexView();
					}
					else
					{
						$this->_homeController->indexView();
					}

				}

				elseif ($action == 'forgotPassView')
				{
					$this->_userController->forgotPassView();
				}

				elseif ($action == 'forgotPassMail')
				{
					$email = $this->getParameter($_POST, 'email');

					$reinit_code = $this->_userController->newPassCode($email);
					$this->_userController->forgotPassMail($email, $reinit_code);

					$message = "Un email contenant un lien de réinitialisation de mot de passe a été envoyé à votre adresse email.";
					$this->_userController->forgotPassView($message);
				}

				elseif ($action == 'newPassView')
				{
					$email = $this->request->getGet()->get('email');
					$reinit_code = $this->request->getGet()->get('key');

					$user_reinit_code = $this->_userController->getNewPassCode($email);

					if ($reinit_code != $user_reinit_code)
					{
						$message = 'La clé de réinitialization n\'est pas bonne, veuillez retourner sur votre mail.';
						$this->_userController->newPassView($email, $message, false);
					}
					else
					{
						$message = 'Veuillez entrer votre nouveau mot de passe';
						$this->_userController->newPassView($email, $message, true);
					}
				}

				elseif ($action == 'newPass')
				{
					$email = $this->getParameter($_POST, 'email');
					$pass1 = $this->getParameter($_POST, 'pass1');
					$pass2 = $this->getParameter($_POST, 'pass2');

					if ($pass1 == $pass2)
					{
						$newPass = password_hash($pass1, PASSWORD_DEFAULT);

						$this->_userController->newUserPass($email, $newPass);
						$message = 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter ! ';
						$this->_userController->connexionView($message);
					}
					else
					{
						$message = 'Les mots de passe saisis sont différents ! ';
						$this->_userController->newPassView($email, $message, true);
					}
				}

				elseif ($action == 'admin' && $this->adminAccess())
				{
					$this->_adminController->dashboardView();
				}

				elseif ($action == 'adminPosts' && $this->adminAccess())
				{
					if ($this->request->getGet()->get('sort') || $this->request->getGet()->get('date'))
					{
						if ($this->request->getGet()->get('sort') && !$this->request->getGet()->get('date'))
						{
							if ($this->request->getGet()->get('sort') == 'unpublished')
							{
								$message = null;
								$sorting = $this->request->getGet()->get('sort');
								$sortingDate = null;
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						elseif (!$this->request->getGet()->get('sort') && $this->request->getGet()->get('date'))
						{
							if ($this->request->getGet()->get('date') == 'asc')
							{
								$message = null;
								$sorting = null;
								$sortingDate = $this->request->getGet()->get('date');
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						else
						{
							if ($this->request->getGet()->get('sort') == 'unpublished' && $this->request->getGet()->get('date') == 'asc')
							{
								$message = null;
								$sorting = $this->request->getGet()->get('sort');
								$sortingDate = $this->request->getGet()->get('date');
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						$this->_adminController->adminPostsView($message, $sorting, $sortingDate);	
					}
					else
					{
						$this->_adminController->adminPostsView();
					}
				}

				elseif ($action == 'adminPostView' && $this->adminAccess())
				{
					$postId = $this->request->getGet()->get('id');

					$this->_adminController->adminPostView($postId);
				}

				elseif ($action == 'adminNewPost' && $this->adminAccess())
				{
					$this->_adminController->adminNewPostView();
				}

				elseif ($action == 'newPostInfos' && $this->adminAccess())
				{
					$title = $this->getParameter($_POST, 'title');
					$chapo = $this->getParameter($_POST, 'chapo');
					$userId = $this->getParameter($_POST, 'user_id');

					if (isset($_FILES['picture']) && $_FILES['picture']['error'] != 4)
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
							$this->_adminController->NewPostInfos($title, $chapo, $userId, $mainImage);
						}
					}
					else
					{
						$this->_adminController->NewPostInfos($title, $chapo, $userId, $mainImage = null);
					}

				}

				elseif ($action == 'editPostView' && $this->adminAccess())
				{
					$postId = $this->request->getGet()->get('id');
					$this->_adminController->editPostView($postId);
				}

				elseif ($action == 'editPost' && $this->adminAccess())
				{
					if (isset($_POST['deleteMainPicture']))
					{
						$postId = $this->getParameter($_POST, 'postId');
						$this->_adminController->deleteMainPostPicture($postId);
					}


					elseif (isset($_POST['addParagraph']))
					{
						$postId = $this->getParameter($_POST, 'postId');
						$this->_adminController->addParagraph($postId);
					}

					elseif (isset($_POST['addCategory']))
					{
						$category = $this->getParameter($_POST, 'categoryName');
						$postId = $this->getParameter($_POST, 'postId');
						$this->_adminController->addCategory($postId, $category);
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

						if (isset($_FILES['MainPicture']) && $_FILES['MainPicture']['error'] != 4)
						{
							$uploadResults = $this->_homeController->pictureUpload($namePicture = 'MainPicture');
							if (strrpos($uploadResults, 'uploads') === false)
							{
								$message = "Information(s) modifiée(s) \n 
								/!\ Erreur de téléchargement de l'image principale : " . $uploadResults;
								$this->_adminController->editPostInfos($newPostInfos, $message);
							}
							else
							{
								$newPostInfos['main_image'] = $uploadResults;
								$message = "Information(s) modifiée(s) ! ";
								$this->_adminController->editPostInfos($newPostInfos, $message);
							}
						}
						else
						{
							$message = "Information(s) modifiée(s) ! ";
							$this->_adminController->editPostInfos($newPostInfos, $message);
						}

					}

					elseif (isset($_POST['editContent']))
					{
						$postId = $this->getParameter($_POST, 'postId');
						$newParagraphs = [];

						foreach ($_POST as $key => $value)
						{
							if (is_numeric($key))
							{
								$newParagraphs[$key] = $value;
							}
						}

						$this->_adminController->editParagraph($postId, $newParagraphs);
					}

					elseif (isset($_POST['addPicture']))
					{
						$postId = $this->getParameter($_POST, 'postId');

						$uploadResults = $this->_homeController->pictureUpload($namePicture = 'picture');

						if (strrpos($uploadResults, 'uploads') === false)
						{
							$message = $uploadResults;
							$this->_adminController->editPostView($postId, $message);
						}
						else
						{
							$this->_adminController->addPicture($postId, $uploadResults);
							$message = 'Image ajoutée !';
							$this->_adminController->editPostView($postId, $message);
						}
					}

					elseif (isset($_POST['updatePicture']))
					{
						$postId = $this->getParameter($_POST, 'postId');

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
							$this->_adminController->editPostView($postId, $message);
						}
						else
						{
							$this->_adminController->editPostPicture($postId, $contentId, $uploadResults);
							$message = 'Image modifiée !';
							$this->_adminController->editPostView($postId, $message);
						}
					}
				}

				elseif ($action == 'deleteContent' && $this->adminAccess())
				{
					$contentId = $this->request->getGet()->get('content');
					$postId = $this->request->getGet()->get('id');
					$contentType = $this->request->getGet()->get('type');

					$this->_adminController->deleteContent($postId, $contentId, $contentType);

				}

				elseif ($action == 'deleteCategory' && $this->adminAccess())
				{
					$categoryId = $this->request->getGet()->get('cat');
					$postId = $this->request->getGet()->get('id');
					$this->_adminController->deleteCategory($postId, $categoryId);
				}

				elseif ($action == 'publishPost' || ($action == 'publishPostDashboard') && $this->adminAccess())
				{
					$postId = $this->request->getGet()->get('id');
					$status = $this->request->getGet()->get('status');

					if ($_GET['action'] == 'publishPostDashboard')
					{
						$dashboard = 1;
						$this->_adminController->publishPost($postId, $status, $dashboard);
					}
					else
					{
						$this->_adminController->publishPost($postId, $status);
					}

				}

				elseif (($action == 'deletePost') || ($action == 'deletePostDashboard') && $this->adminAccess())
				{
					$postId = $this->request->getGet()->get('id');

					if ($_GET['action'] == 'deletePostDashboard')
					{
						$dashboard = 1;
						$this->_adminController->deletePost($postId, $dashboard);
					}
					else
					{
						$this->_adminController->deletePost($postId);
					}					
				}

				elseif ($action == 'adminComments' && $this->adminAccess())
				{
					if ($this->request->getGet()->get('sort') || $this->request->getGet()->get('date'))
					{
						if ($this->request->getGet()->get('sort') && !$this->request->getGet()->get('date'))
						{
							if ($_GET['sort'] == 'unapproved')
							{
								$message = null;
								$sorting = $this->request->getGet()->get('sort');
								$sortingDate = null;
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						elseif (!$this->request->getGet()->get('sort') && $this->request->getGet()->get('date'))
						{
							if ($_GET['date'] == 'asc')
							{
								$message = null;
								$sorting = null;
								$sortingDate = $this->request->getGet()->get('date');
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						else
						{
							if ($this->request->getGet()->get('sort') == 'unapproved' && $this->request->getGet()->get('date') == 'asc')
							{
								$message = null;
								$sorting = $this->request->getGet()->get('sort');
								$sortingDate = $this->request->getGet()->get('date');
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						$this->_adminController->adminCommentsView($message, $sorting, $sortingDate);	
					}
					else
					{
						$this->_adminController->adminCommentsView();
					}
				}

				elseif (($action == 'approveComment') || ($action == 'approveCommentDashboard') || ($action == 'approveCommentView') && $this->adminAccess())
				{
					$commentId = $this->request->getGet()->get('id');

					if ($_GET['action'] == 'approveCommentDashboard')
					{
						$view = 1;
						$this->_adminController->approveComment($commentId, $view);
					}
					elseif ($_GET['action'] == 'approveCommentView')
					{
						$view = 2;
						$postId = $this->request->getGet()->get('post');
						$this->_adminController->approveComment($commentId, $view, $postId);
					}

					else
					{
						$this->_adminController->approveComment($commentId);
					}					
				}

				elseif (($action == 'deleteComment') || ($action == 'deleteCommentDashboard') && $this->adminAccess())
				{
					$commentId = $this->request->getGet()->get('id');

					if ($this->request->getGet()->get('action') == 'deleteCommentDashboard')
					{
						$dashboard = 1;
						$this->_adminController->deleteComment($commentId, $dashboard);
					}
					else
					{
						$this->_adminController->deleteComment($commentId);
					}					
				}

				elseif ($action == 'adminUsers' && $this->adminAccess())
				{
					if ($this->request->getGet()->get('sort'))
					{
						if (in_array($this->request->getGet()->get('sort'), [1, 2, 3]))
						{
							$userRoleId = $this->request->getGet()->get('sort');
						}
						else
						{
							throw new Exception("La page que vous recherchez n'existe pas. ");
						}
					}
					else
					{
						$userRoleId = null;
					}

					$this->_adminController->adminUsersView($userRoleId);
				}

				elseif ($action == 'profileUser')
				{
					$userId = $this->request->getGet()->get('id');
					$this->_adminController->profileUserView($userId);
				}

				elseif ($action == 'editUser')
				{
					$userId = $this->request->getGet()->get('id');
					$currentUserId = $this->getParameter($_SESSION, 'id');

					if ($currentUserId == $userId || $this->adminAccess())
					{
						$this->_adminController->editUserView($userId);
					}
					else
					{
						throw new Exception('Vous n\'avez pas accès à cette page');
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
									$role = $this->getParameter($_SESSION, 'role');
									$newUserInfos['user_role_id'] = $role;
								}

								$date = $newUserInfos['birth_date'];

								if (empty($date))
								{
									unset($newUserInfos['birth_date']);
									$this->_adminController->deleteBirthDate($newUserInfos['id']);

									$email = $newUserInfos['email'];
									$userId = $newUserInfos['id'];
									$currentUserId = $this->getParameter($_SESSION, 'id');	

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
										$currentUserId = $this->getParameter($_SESSION, 'id');	

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

						$currentUserId = $this->getParameter($_SESSION, 'id');	
						if ($currentUserId == $userId)
						{
							$_SESSION['avatar'] = $uploadResults;
						}
					}
				}

				elseif ($action == 'adminContacts' && $this->adminAccess())
				{
					if ($this->request->getGet()->get('sort') || $this->request->getGet()->get('date'))
					{
						if ($this->request->getGet()->get('sort') && !$this->request->getGet()->get('date'))
						{
							if ($_GET['sort'] == 'unread')
							{
								$message = null;
								$sorting = $this->request->getGet()->get('sort');
								$sortingDate = null;
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						elseif (!$this->request->getGet()->get('sort') && $this->request->getGet()->get('date'))
						{
							if ($this->request->getGet()->get('date') == 'asc')
							{
								$message = null;
								$sorting = null;
								$sortingDate = $this->request->getGet()->get('date');
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						else
						{
							if ($this->request->getGet()->get('sort') == 'unread' && $this->request->getGet()->get('date') == 'asc')
							{
								$message = null;
								$sorting = $this->request->getGet()->get('sort');
								$sortingDate = $this->request->getGet()->get('date');
							}
							else
							{
								$message = 'Le choix de tri n\'est pas valide';
								$sorting = null;
								$sortingDate = null;
							}
						}
						$this->_adminController->adminContactsView($message, $sorting, $sortingDate);	
					}
					else
					{
						$this->_adminController->adminContactsView();
					}
				}

				elseif ($action == 'contactForm')
				{
					$name = $this->getParameter($_POST, 'name');
					$email = $this->getParameter($_POST, 'email');
					$subject = $this->getParameter($_POST, 'subject');
					$content = $this->getParameter($_POST, 'content');

					$contactId = $this->_homeController->newContactForm($name, $email, $subject, $content);
					$this->_homeController->mailContactForm($name, $email, $subject, $content, $contactId);

					$message = "Votre message a bien été envoyé. Nous vous remercions et vous recontacterons dans les plus brefs délais.";
					$this->_homeController->indexView($message);
				}

				elseif ($action == 'contactView' && $this->adminAccess())
				{
					$contactId = $this->request->getGet()->get('id');

					$this->_adminController->adminContactView($contactId);
				}

				elseif (($action == 'deleteContact') && $this->adminAccess())
				{
					$contactId = $this->request->getGet()->get('id');

					$this->_adminController->deleteContact($contactId);				
				}

				elseif (($action == 'answer') && $this->adminAccess())
				{
					$contactId = $this->getParameter($_POST, 'id');
					$answerSubject = $this->getParameter($_POST, 'answerSubject');
					$email = $this->getParameter($_POST, 'email');
					$answerContent = $this->getParameter($_POST, 'answerContent');

					$this->_adminController->addAnswer($contactId, $answerSubject, $answerContent);
					$this->_adminController->adminAnswerEmail($contactId, $answerSubject, $answerContent, $email);

					$message = "La réponse a bien été envoyée.";
					$this->_adminController->adminContactView($contactId, $message);  			
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




