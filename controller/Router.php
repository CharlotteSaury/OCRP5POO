<?php

namespace controller;

require_once('controller\HomeController.php');
require_once('controller\PostController.php');
require_once('controller\AdminController.php');
require_once('controller\UserController.php');

use controller\HomeController;
use controller\PostController;
use controller\AdminController;
use controller\UserController;
use Exception;


class Router 
{
	private $_homeController,
	$_postController;

	public function __construct()
	{
		$this->_homeController = new HomeController();
		$this->_postController = new PostController();
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

			if ($infos->isAdmin($userId) || $infos->isSuperAdmin($userId))
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

	private function pictureUpload($namePicture)
	{
		if (isset($_FILES[$namePicture]))
		{
			if ($_FILES[$namePicture]['error'] == 0)
			{
				if ($_FILES[$namePicture]['size'] <= 2000000)
				{
					$fileInfos = pathinfo($_FILES[$namePicture]['name']);
					$extension_upload = $fileInfos['extension'];
					$allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');

					if (in_array($extension_upload, $allowed_extensions))
					{
						$uploadResults = 'uploads/' . $_SERVER['REQUEST_TIME'] . '_' . basename($_FILES[$namePicture]['name']);

						move_uploaded_file($_FILES[$namePicture]['tmp_name'], $uploadResults);
					}
					else
					{
						$uploadResults = 'L\'extension du fichier n\'est pas acceptée, merci de ne charger que des fichiers .jpg, .jpeg, .gif ou .png';
					}
				}
				else
				{
					$uploadResults = 'Fichier trop volumineux, merci de ne pas dépasser 2Mo.';
				}
			}
			elseif ($_FILES[$namePicture]['error'] == 1 || $_FILES[$namePicture]['error'] == 2)
			{
				$uploadResults = 'Fichier trop volumineux, merci de ne pas dépasser 2Mo.';
			}
			else
			{
				$uploadResults = 'Erreur. Le fichier n\'a pu être téléchargé.';
			}
		}
		else
		{
			$uploadResults = 'Aucun fichier téléchargé.';
		}

		return $uploadResults;
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

					elseif ($_GET['action'] == 'legalView') 
					{
						$infos = new HomeController();
						$infos->legalView();
					}

					elseif ($_GET['action'] == 'confidentiality') 
					{
						$infos = new HomeController();
						$infos->confidentialityView();
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
										setcookie('auth', password_hash($email, PASSWORD_DEFAULT) . '-----' . password_hash($_SERVER['REMOTE_ADDR'], PASSWORD_DEFAULT), time() + 365*24*3600, null, null, false, true);
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
						if (isset($_GET['sort']) || isset($_GET['date']))
						{
							if (isset($_GET['sort']) && !isset($_GET['date']))
							{
								if ($_GET['sort'] == 'unpublished')
								{
									$message = null;
									$sorting = htmlspecialchars($_GET['sort']);
									$sortingDate = null;
								}
								else
								{
									$message = 'Le choix de tri n\'est pas valide';
									$sorting = null;
									$sortingDate = null;
								}
							}
							elseif (!isset($_GET['sort']) && isset($_GET['date']))
							{
								if ($_GET['date'] == 'asc')
								{
									$message = null;
									$sorting = null;
									$sortingDate = htmlspecialchars($_GET['date']);
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
								if ($_GET['sort'] == 'unpublished' && $_GET['date'] == 'asc')
								{
									$message = null;
									$sorting = htmlspecialchars($_GET['sort']);
									$sortingDate = htmlspecialchars($_GET['date']);
								}
								else
								{
									$message = 'Le choix de tri n\'est pas valide';
									$sorting = null;
									$sortingDate = null;
								}
							}
							$infos = new AdminController();
							$infos->adminPostsView($message, $sorting, $sortingDate);	
						}
						else
						{
							$infos = new AdminController();
							$infos->adminPostsView();
						}
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
						$infos = new AdminController();

						$title = $this->getParameter($_POST, 'title');
						$chapo = $this->getParameter($_POST, 'chapo');
						$userId = $this->getParameter($_POST, 'user_id');

						if (isset($_FILES['picture']) && $_FILES['picture']['error'] != 4)
						{
							$uploadResults = $this->pictureUpload($namePicture = 'picture');
							if (strrpos($uploadResults, 'uploads') === false)
							{
								$message = $uploadResults;
								$infos->adminNewPostView($message);
							}
							else
							{
								$mainImage = $uploadResults;
								$infos->NewPostInfos($title, $chapo, $userId, $mainImage);
							}
						}
						else
						{
							$infos->NewPostInfos($title, $chapo, $userId, $mainImage = null);
						}
						
					}

					elseif ($_GET['action'] == 'editPostView' && $this->adminAccess())
					{
						$postId = $this->getParameter($_GET, 'id');
						$infos = new AdminController();
						$infos->editPostView($postId);
					}

					elseif ($_GET['action'] == 'editPost' && $this->adminAccess())
					{
						if (isset($_POST['deleteMainPicture']))
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
						
						elseif (isset($_POST['addCategory']))
						{
							$category = $this->getParameter($_POST, 'categoryName');
							$postId = $this->getParameter($_POST, 'postId');
							$infos = new AdminController();
							$infos->addCategory($postId, $category);
						}

						elseif (isset($_POST['updatePostInfos']))
						{
							$infos = new AdminController();

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
								$uploadResults = $this->pictureUpload($namePicture = 'MainPicture');
								if (strrpos($uploadResults, 'uploads') === false)
								{
									$message = "Information(s) modifiée(s) \n 
										/!\ Erreur de téléchargement de l'image principale : " . $uploadResults;
									$infos->editPostInfos($newPostInfos, $message);
								}
								else
								{
									$newPostInfos['main_image'] = $uploadResults;
									$message = "Information(s) modifiée(s) ! ";
									$infos->editPostInfos($newPostInfos, $message);
								}
							}
							else
							{
								$message = "Information(s) modifiée(s) ! ";
								$infos->editPostInfos($newPostInfos, $message);
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

							$infos = new AdminController();
							$infos->editParagraph($postId, $newParagraphs);
						}

						elseif (isset($_POST['addPicture']))
						{
							$postId = $this->getParameter($_POST, 'postId');

							$uploadResults = $this->pictureUpload($namePicture = 'picture');
							$infos = new AdminController();

							if (strrpos($uploadResults, 'uploads') === false)
							{
								$message = $uploadResults;
								$infos->editPostView($postId, $message);
							}
							else
							{
								$infos->addPicture($postId, $uploadResults);
								$message = 'Image ajoutée !';
								$infos->editPostView($postId, $message);
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

							$uploadResults = $this->pictureUpload($namePicture = 'picture' . $contentId);
							$infos = new AdminController();

							if (strrpos($uploadResults, 'uploads') === false)
							{
								$message = $uploadResults;
								$infos->editPostView($postId, $message);
							}
							else
							{
								$infos->editPostPicture($postId, $contentId, $uploadResults);
								$message = 'Image modifiée !';
								$infos->editPostView($postId, $message);
							}
						}
					}

					elseif ($_GET['action'] == 'deleteContent' && $this->adminAccess())
					{
						$contentId = $this->getParameter($_GET, 'content');
						$postId = $this->getParameter($_GET, 'id');
						$contentType = $this->getParameter($_GET, 'type');

						$infos = new AdminController();
						$infos->deleteContent($postId, $contentId, $contentType);

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
						if (isset($_GET['sort']) || isset($_GET['date']))
						{
							if (isset($_GET['sort']) && !isset($_GET['date']))
							{
								if ($_GET['sort'] == 'unapproved')
								{
									$message = null;
									$sorting = htmlspecialchars($_GET['sort']);
									$sortingDate = null;
								}
								else
								{
									$message = 'Le choix de tri n\'est pas valide';
									$sorting = null;
									$sortingDate = null;
								}
							}
							elseif (!isset($_GET['sort']) && isset($_GET['date']))
							{
								if ($_GET['date'] == 'asc')
								{
									$message = null;
									$sorting = null;
									$sortingDate = htmlspecialchars($_GET['date']);
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
								if ($_GET['sort'] == 'unapproved' && $_GET['date'] == 'asc')
								{
									$message = null;
									$sorting = htmlspecialchars($_GET['sort']);
									$sortingDate = htmlspecialchars($_GET['date']);
								}
								else
								{
									$message = 'Le choix de tri n\'est pas valide';
									$sorting = null;
									$sortingDate = null;
								}
							}
							$infos = new AdminController();
							$infos->adminCommentsView($message, $sorting, $sortingDate);	
						}
						else
						{
							$infos = new AdminController();
							$infos->adminCommentsView();
						}
					}

					elseif (($_GET['action'] == 'approveComment') || ($_GET['action'] == 'approveCommentDashboard') || ($_GET['action'] == 'approveCommentView') && $this->adminAccess())
					{
						$commentId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();						
						if ($_GET['action'] == 'approveCommentDashboard')
						{
							$view = 1;
							$infos->approveComment($commentId, $view);
						}
						elseif ($_GET['action'] == 'approveCommentView')
						{
							$view = 2;
							$postId = $this->getParameter($_GET, 'post');
							$infos->approveComment($commentId, $view, $postId);
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
						if (isset($_GET['sort']))
						{
							if (in_array($_GET['sort'], [1, 2, 3]))
							{
								$userRoleId = htmlspecialchars($_GET['sort']);
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

						$infos = new AdminController();
						$infos->adminUsersView($userRoleId);
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
									if (!isset($newUserInfos['user_role_id']))
									{
										$role = $this->getParameter($_SESSION, 'role');
										$newUserInfos['user_role_id'] = $role;
									}

									$date = $newUserInfos['birth_date'];

									if (empty($date))
									{
										unset($newUserInfos['birth_date']);
										$infos = new AdminController();
										$infos->deleteBirthDate($newUserInfos['id']);

										$email = $newUserInfos['email'];
										$userId = $newUserInfos['id'];
										$currentUserId = $this->getParameter($_SESSION, 'id');	

										$infos = new AdminController();
										$infos->editUserInfos($newUserInfos);

										// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

										if ($currentUserId == $userId)
										{
											$infos = new UserController();
											$infos->newUserSession($email);
										}
									}
									else
									{
										$checkDate = explode('-', $date);						
										if (!preg_match('#^([0-9]{2})(-)([0-9]{2})(-)([0-9]{4})$#' , $date)) // check date format (DD/MM/AAAA)
										{
											$message = "La date de naissance n'est pas dans le format autorisé.";
											$infos = new AdminController();
											$infos->editUserView($newUserInfos['id'], $message);
										}
										elseif (!checkdate($checkDate[1], $checkDate[0], $checkDate[2]) || ($checkDate[2] < 1900 )) // check date validity
										{
											$message = "La date de naissance n'est pas valide.";
											$infos = new AdminController();
											$infos->editUserView($newUserInfos['id'], $message);
										}
										else // date valide
										{
											$newUserInfos['birth_date'] = $checkDate[2] . '-' . $checkDate[1] . '-' . $checkDate[0];


											$email = $newUserInfos['email'];
											$userId = $newUserInfos['id'];
											$currentUserId = $this->getParameter($_SESSION, 'id');	

											$infos = new AdminController();
											$infos->editUserInfos($newUserInfos);

											// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

											if ($currentUserId == $userId)
											{
												$infos = new UserController();
												$infos->newUserSession($email);
											}

										}
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
						$uploadResults = $this->pictureUpload($namePicture = 'picture');
						$infos = new AdminController();
						
						if (strrpos($uploadResults, 'uploads') === false)
						{
							$message = $uploadResults;
							$infos->profileUserView($userId, $message);
						}
						else
						{
							$infos->updateProfilePicture($userId, $uploadResults);
							$message = 'Photo de profil modifiée !';

							$infos->profileUserView($userId, $message);

						// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

							$currentUserId = $this->getParameter($_SESSION, 'id');	
							if ($currentUserId == $userId)
							{
								$_SESSION['avatar'] = $uploadResults;
							}
						}
					}

					elseif ($_GET['action'] == 'adminContacts' && $this->adminAccess())
					{
						if (isset($_GET['sort']) || isset($_GET['date']))
						{
							if (isset($_GET['sort']) && !isset($_GET['date']))
							{
								if ($_GET['sort'] == 'unread')
								{
									$message = null;
									$sorting = htmlspecialchars($_GET['sort']);
									$sortingDate = null;
								}
								else
								{
									$message = 'Le choix de tri n\'est pas valide';
									$sorting = null;
									$sortingDate = null;
								}
							}
							elseif (!isset($_GET['sort']) && isset($_GET['date']))
							{
								if ($_GET['date'] == 'asc')
								{
									$message = null;
									$sorting = null;
									$sortingDate = htmlspecialchars($_GET['date']);
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
								if ($_GET['sort'] == 'unread' && $_GET['date'] == 'asc')
								{
									$message = null;
									$sorting = htmlspecialchars($_GET['sort']);
									$sortingDate = htmlspecialchars($_GET['date']);
								}
								else
								{
									$message = 'Le choix de tri n\'est pas valide';
									$sorting = null;
									$sortingDate = null;
								}
							}
							$infos = new AdminController();
							$infos->adminContactsView($message, $sorting, $sortingDate);	
						}
						else
						{
							$infos = new AdminController();
							$infos->adminContactsView();
						}
					}

					elseif ($_GET['action'] == 'contactForm' && $this->adminAccess())
					{
						$name = $this->getParameter($_POST, 'name');
						$email = $this->getParameter($_POST, 'email');
						$subject = $this->getParameter($_POST, 'subject');
						$content = $this->getParameter($_POST, 'content');

						$infos = new HomeController();

						$contactId = $infos->newContactForm($name, $email, $subject, $content);
						$infos->mailContactForm($name, $email, $subject, $content, $contactId);

						$message = "Votre message a bien été envoyé. Nous vous remercions et vous recontacterons dans les plus brefs délais.";
						$infos->indexView($message);
					}

					elseif ($_GET['action'] == 'contactView' && $this->adminAccess())
					{
						$contactId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();
						$infos->adminContactView($contactId);
					}

					elseif (($_GET['action'] == 'deleteContact') && $this->adminAccess())
					{
						$contactId = $this->getParameter($_GET, 'id');
						
						$infos = new AdminController();						
						$infos->deleteContact($contactId);				
					}

					elseif (($_GET['action'] == 'answer') && $this->adminAccess())
					{
						$contactId = $this->getParameter($_POST, 'id');
						$answerSubject = $this->getParameter($_POST, 'answerSubject');
						$email = $this->getParameter($_POST, 'email');
						$answerContent = $this->getParameter($_POST, 'answerContent');
						
						$infos = new AdminController();
						$infos->addAnswer($contactId, $answerSubject, $answerContent);
						$infos->adminAnswerEmail($contactId, $answerSubject, $answerContent, $email);

						$message = "La réponse a bien été envoyée.";
						$infos->adminContactView($contactId, $message);  			
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




