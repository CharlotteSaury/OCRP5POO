<?php

namespace controller;

require_once('controller\HomeController.php');
require_once('controller\PostController.php');
require_once('controller\CommentController.php');
require_once('controller\AdminController.php');
require_once('controller\UserController.php');

use controller\HomeController;
use controller\PostController;
use controller\CommentController;
use controller\AdminController;
use controller\UserController;
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

	private function adminAccess()
	{
		if (isset($_SESSION['id']))
		{
			$userId = htmlspecialchars($_SESSION['id']);
			$infos = new UserController();

			if ($infos->isAdmin($userId))
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
		$infos = new UserController();
		$infos->newUserSession($email);
		$this->routerRequest();
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
						$userId = $this->getParameter($_POST, 'userId');
						$content = $this->getParameter($_POST, 'content');

						$infos = new PostController();
						$infos->addComment($postId, $userId, $content);
						
					}

					elseif ($_GET['action'] == 'home') 
					{
						$infos = new HomeController();
						$infos->indexView();
					}

					elseif ($_GET['action'] == 'inscriptionView')
					{
						$infos = new UserController();
						$infos->inscriptionView();
					}

					elseif ($_GET['action'] == 'inscription')
					{
						$pseudo = $this->getParameter($_POST, 'pseudo');
						$pass1 = $this->getParameter($_POST, 'pass1');
						$pass2 = $this->getParameter($_POST, 'pass2');
						$email = $this->getParameter($_POST, 'email');

						$infos = new UserController();

						if (strlen($pseudo) < 25)
						{
							if (filter_var($email, FILTER_VALIDATE_EMAIL))
							{
								if ($pass1 == $pass2)
								{
									$pass = password_hash($pass1, PASSWORD_DEFAULT);

									if (($infos->checkEmail($email)) || ($infos->checkPseudo($pseudo)) )
									{
										if ($infos->checkEmail($email))
										{
											$message = "Cet email est déjà associé à un compte. Essayez de vous connecter !";
											$infos->connexionView($message);
										}
										else
										{
											$message = "Ce pseudo n'est pas disponible. Merci d'en choisir un nouveau.";
											$infos->inscriptionView($message);
										}								
									}
									else
									{
										$activation_code = $infos->newUser($pseudo, $pass, $email);
										$infos->sendEmailActivation($email, $pseudo, $activation_code);
										$message = 'Merci pour votre inscription ! Un email de confirmation vous a été envoyé afin de confirmer votre adresse email. Merci de vous reporter à cet email pour activer votre compte ! ';
										$infos->inscriptionView($message);
									}
								}
								else
								{
									$message = 'Les mots de passe saisis sont différents ! ';
									$infos->inscriptionView($message);
								}
							}
							else
							{
								$message = 'L\'adresse email n\'est pas valide !';
								$infos->inscriptionView($message);
							}							
						}
						else
						{
							$message = 'Le pseudo ne doit pas dépasser 25 caractères ! ';
							$infos->inscriptionView($message);
						}				

					}


					elseif ($_GET['action'] == 'activation')
					{
						$email = $this->getParameter($_GET, 'email');
						$key = $this->getParameter($_GET, 'key');
						$infos = new UserController();

						if ($infos->userActivated($email))
						{
							$message = 'Votre compte est déjà activé, vous pouvez vous connecter ! ';
							$infos->connexionView($message);
						}
						else
						{
							$message = $infos->userActivation($email, $key);
							$infos->connexionView($message);
						}
						
					}

					elseif ($_GET['action'] == 'connexionView')
					{
						$infos = new UserController();
						$infos->connexionView();
					}

					elseif ($_GET['action'] == 'connexion')
					{
						$email = $this->getParameter($_POST, 'email');
						$pass = $this->getParameter($_POST, 'pass');

						$infos = new UserController();

						if ($infos->checkEmail($email))
						{
							if (!$infos->userActivated($email))
							{
								$message = 'Votre compte n\'est pas activé. Veuillez cliquer sur le lien d\'activation qui vous a été envoyé sur votre adresse email lors de votre inscription.';
								$infos->connexionView($message);
							}
							else
							{
								$user_pass = $infos->getPassword($email);

								if (password_verify($pass, $user_pass))
								{
									if (isset($_POST['rememberme']))
									{
										setcookie('email', $email, time() + 365*24*3600, null, null, false, true);
										setcookie('auth', $email . '-----' . password_hash($_SERVER['REMOTE_ADDR'], PASSWORD_DEFAULT), time() + 365*24*3600, null, null, false, true);
									}

									$infos->newUserSession($email);

									$infos = new HomeController();
									$infos->indexView();
								}
								else
								{
									$message = "L'identifiant et/ou le mot de passe sont erronés.";
									$infos->connexionView($message);
								}
							}							
						}				
						else
						{
							$message = "L'adresse email saisie est inconnue";
							$infos->connexionView($message);
						}
						
					}

					elseif ($_GET['action'] == 'deconnexion')
					{
						if (isset($_SESSION['id']))
						{
							$_SESSION = array();
							session_destroy();
							setcookie('auth', '', time()-3600, null, null, false, true);

							$infos = new HomeController();
							$infos->indexView();
						}
						else
						{
							$infos = new HomeController();
							$infos->indexView();
						}
						
					}

					elseif ($_GET['action'] == 'forgotPassView')
					{
						$infos = new UserController();
						$infos->forgotPassView();
					}

					elseif ($_GET['action'] == 'forgotPassMail')
					{
						$email = $this->getParameter($_POST, 'email');
						$infos = new UserController();

						$reinitialization_code = $infos->newPassCode($email);
						$infos->forgotPassMail($email, $reinitialization_code);

						$message = "Un email contenant un lien de réinitialisation de mot de passe a été envoyé à votre adresse email.";
						$infos->forgotPassView($message);
					}

					elseif ($_GET['action'] == 'newPassView')
					{
						$email = $this->getParameter($_GET, 'email');
						$reinitialization_code = $this->getParameter($_GET, 'key');
						
						$infos = new UserController();

						$user_reinitialization_code = $infos->getNewPassCode($email);

						if ($reinitialization_code != $user_reinitialization_code)
						{
							$message = 'La clé de réinitialization n\'est pas bonne, veuillez retourner sur votre mail.';
							$infos->newPassView($email, $message, false);
						}
						else
						{
							$message = 'Veuillez entrer votre nouveau mot de passe';
							$infos->newPassView($email, $message, true);
						}
					}

					elseif ($_GET['action'] == 'newPass')
					{
						$email = $this->getParameter($_POST, 'email');
						$pass1 = $this->getParameter($_POST, 'pass1');
						$pass2 = $this->getParameter($_POST, 'pass2');
						
						$infos = new UserController();

						if ($pass1 == $pass2)
						{
							$newPass = password_hash($pass1, PASSWORD_DEFAULT);
							
							$infos->newUserPass($email, $newPass);
							$message = 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter ! ';
							$infos->connexionView($message);
						}
						else
						{
							$message = 'Les mots de passe saisis sont différents ! ';
							$infos->newPassView($email, $message, true);
						}
					}

					elseif ($_GET['action'] == 'admin' && $this->adminAccess())
					{
						$infos = new AdminController();
						$infos->dashboardView();
					}

					elseif ($_GET['action'] == 'adminPosts' && $this->adminAccess())
					{
						$infos = new AdminController();
						$infos->adminPostsView();
					}

					elseif ($_GET['action'] == 'adminPostView' && $this->adminAccess())
					{
						$postId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();
						$infos->adminPostView($postId);
					}

					elseif ($_GET['action'] == 'adminNewPost' && $this->adminAccess())
					{
						$infos = new AdminController();
						$infos->adminNewPostView();
					}

					elseif ($_GET['action'] == 'newPostInfos' && $this->adminAccess())
					{
						$title = $this->getParameter($_POST, 'title');
						$chapo = $this->getParameter($_POST, 'chapo');
						$userId = $this->getParameter($_POST, 'user_id');
						$mainImage = $this->getParameter($_POST, 'main_image');

						$infos = new AdminController();
						$infos->NewPostInfos($title, $chapo, $userId, $mainImage);
					}

					elseif ($_GET['action'] == 'editPostView' && $this->adminAccess())
					{
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->editPostView($postId);
					}

					elseif ($_GET['action'] == 'editPost' && $this->adminAccess())
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
							$infos = new AdminController();
							$infos->addPicture($postId, $content);
						}

						elseif (isset($_POST['updatePicture']))
						{
							$postId = $this->getParameter($_POST, 'postId');
							$contentId = substr($this->getParameter($_POST, 'action') , 12);
							$url = $this->getParameter($_POST, 'content' . $contentId);

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
							$postId = $this->getParameter($_POST, 'postId');
							$newParagraphs = [];

							foreach ($_POST as $key => $value)
							{
								if (is_numeric($key))
								{
									$newParagraphs[$key] = $value;
								}
							}

							$infos = new AdminController();
							$infos->editParagraph($postId, $newParagraphs);
						}
					}

					

					elseif ($_GET['action'] == 'deleteContent' && $this->adminAccess())
					{
						$contentId = $this->getParameter($_GET, 'content');
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->deleteContent($postId, $contentId);
					}

					elseif ($_GET['action'] == 'deleteCategory' && $this->adminAccess())
					{
						$categoryId = $this->getParameter($_GET, 'cat');
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->deleteCategory($postId, $categoryId);
					}

					elseif ($_GET['action'] == 'publishPost' || ($_GET['action'] == 'publishPostDashboard') && $this->adminAccess())
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

					elseif (($_GET['action'] == 'deletePost') || ($_GET['action'] == 'deletePostDashboard') && $this->adminAccess())
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

					elseif ($_GET['action'] == 'adminComments' && $this->adminAccess())
					{
						$infos = new AdminController();
						$infos->adminCommentsView();
					}

					elseif (($_GET['action'] == 'approveComment') || ($_GET['action'] == 'approveCommentDashboard') && $this->adminAccess())
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

					elseif (($_GET['action'] == 'deleteComment') || ($_GET['action'] == 'deleteCommentDashboard') && $this->adminAccess())
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

					elseif ($_GET['action'] == 'adminUsers' && $this->adminAccess())
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
						$currentUserId = $this->getParameter($_SESSION, 'id');

						if ($currentUserId == $userId || $this->adminAccess())
						{
							$infos = new AdminController();
							$infos->editUserView($userId);
						}
						else
						{
							throw new Exception('Vous n\'avez pas accès à cette page');
						}
						
					}

					elseif ($_GET['action'] == 'editUserInfos')
					{
						$newUserInfos = [];

						foreach ($_POST as $key => $value)
						{
							$newUserInfos[$key] = $value;
						}

						$infos = new UserController();

						if (strlen($newUserInfos['pseudo']) < 25)
						{
							if (filter_var($newUserInfos['email'], FILTER_VALIDATE_EMAIL))
							{
								if (($infos->checkEmail($newUserInfos['email'], $newUserInfos['id'])) || ($infos->checkPseudo($newUserInfos['pseudo'], $newUserInfos['id'])))
								{
									if ($infos->checkEmail($newUserInfos['email'], $newUserInfos['id']))
									{
										$message = "Cet email est déjà associé à un compte.";

										$infos = new AdminController();
										$infos->editUserView($newUserInfos['id'], $message);
									}
									else
									{
										$message = "Ce pseudo n'est pas disponible. Merci d'en choisir un nouveau.";
										$infos = new AdminController();
										$infos->editUserView($newUserInfos['id'], $message);
									}								
								}
								else
								{
									if ($newUserInfos['birth_date'] == '')
									{
										unset($newUserInfos['birth_date']);
									}

									if (!isset($newUserInfos['user_role_id']))
									{
										$role = $this->getParameter($_SESSION, 'role');
										$newUserInfos['user_role_id'] = $role;
									}

									$email = $newUserInfos['email'];
									$userId = $newUserInfos['id'];
									$currentUserId = $this->getParameter($_SESSION, 'id');

									$infos = new AdminController();
									$infos->editUserInfos($newUserInfos);

									if ($currentUserId == $userId)
									{
										$infos = new UserController();
										$infos->newUserSession($email);
									}

								}

							}
							else
							{
								$message = 'L\'adresse email n\'est pas valide !';
								$infos = new AdminController();
								$infos->editUserView($message);
							}							
						}
						else
						{
							$message = 'Le pseudo ne doit pas dépasser 25 caractères ! ';
							$infos = new AdminController();
							$infos->editUserView($message);
						}	

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
						throw new Exception('Vous n\'avez pas accès à cette page');
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



