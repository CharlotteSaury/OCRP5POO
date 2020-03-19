<?php

namespace Src\Controller;

use Src\Controller\HomeController;
use Src\Controller\PostController;
use Src\Controller\AdminController;
use Src\Controller\UserController;
use Src\Controller\ErrorController;
use Src\Config\Request;

use Exception;

/**
 * Class Router
 * Redirect user request to corresponding controller
 */
class Router 
{
	/**
	 * @var HomeController
	 */
	private $homeController;

	/**
	 * @var PostController
	 */
	private $postController;
	
	/**
	 * @var UserController
	 */
	private $userController;

	/**
	 * @var AdminController
	 */
	private $adminController;

	/**
	 * @var ErrorController
	 */
	private $errorController;

	/**
	 * @var Request
	 */
	private $request;

	public function __construct()
	{
		$this->homeController = new HomeController();
		$this->postController = new PostController();
		$this->userController = new UserController();
		$this->adminController = new AdminController();
		$this->errorController = new ErrorController();
		$this->request = new Request();
	}

	/**
	 * Create new user session with email provided by cookies if user prealably checked "remember me " input of connexion form
	 * @param  sring $email
	 * @return void [redirect to routerRequest method]
	 */
	public function connexionAuto($email)
	{
		$this->userController->newUserSession($email);
		$this->routerRequest();
	}

	/**
	 * Get action asked by user in url and redirect to appropriate controller
	 * @return void [redirect to appropriate controller]
	 */
	public function routerRequest()
	{
		$this->request->getSession()->remove('message');
		$this->adminController->getUnreadContactsNb();
		$action = $this->request->getGet()->get('action');
		
		try {
			if (isset($action)) {

				/*****************************************************/ 
				/*********** Actions related to frontend *************/
				/*****************************************************/
				
				if ($action === 'listPosts') {
					$this->postController->listPostView($this->request->getGet());
				
				} elseif ($action === 'postView') {

					$this->postController->postView($this->request->getGet()->get('id'));
				
				} elseif ($action === "addComment" && $this->userController->checkCsrfToken()) {

					$post = $this->request->getPost();
					$userId = $this->request->getSession()->get('id');
					$this->postController->addComment($post, $userId);
				
				} elseif ($action === 'legalView') {

					$this->homeController->legalView();
				
				} elseif ($action === 'confidentiality') {

					$this->homeController->confidentialityView();
				
				} elseif ($action === 'inscriptionView') {

					$this->userController->inscriptionView();
				
				} elseif ($action === 'inscription') {

					$post = $this->request->getPost();
					$this->userController->inscription($post);
				
				} elseif ($action === 'activation') {

					$get = $this->request->getGet();
					$this->userController->userActivation($get);

				} elseif ($action === 'connexionView') {

					$this->userController->connexionView();

				} elseif ($action === 'connexion') {

					$post = $this->request->getPost();
					$this->userController->connexion($post);

				} elseif ($action === 'deconnexion')	{

					$session = $this->request->getSession();

					if ($session->get('id')) {

						$session->removeAll();
						$session->stop();
						setcookie('auth', '', time()-3600, null, null, false, true);
					}
					$this->homeController->indexView();

				} elseif ($action === 'forgotPassView') {

					$this->userController->forgotPassView();

				} elseif ($action === 'forgotPassMail') {

					$email = $this->request->getPost()->get('email');
					$this->userController->newPassCode($email);

				} elseif ($action === 'newPassView') {

					$get = $this->request->getGet();
					$this->userController->checkReinitCode($get);

				} elseif ($action === 'newPass') {

					$post = $this->request->getPost();
					$email = $this->request->getSession()->get('email');
					$this->userController->newPass($post, $email);
				
				/*****************************************************/ 
				/*********** Actions related to backend **************/
				/*****************************************************/

				} elseif ($action === 'admin' && $this->userController->adminAccess()) {

					$this->adminController->dashboardView();


				// Actions related to Post managment
				
				} elseif ($action === 'adminPosts' && $this->userController->adminAccess()) {

					$this->adminController->adminPostsView($this->request->getGet());
				
				} elseif ($action === 'adminPostView' && $this->userController->adminAccess()) {

					$this->adminController->adminPostView($this->request->getGet()->get('id'));
				
				} elseif ($action === 'adminNewPost' && $this->userController->adminAccess()) {

					$this->adminController->adminNewPostView();
				
				} elseif ($action === 'newPostInfos' && $this->userController->adminAccess() && $this->userController->checkCsrfToken()) {

					$post = $this->request->getPost();
					$post->set('userId', $this->request->getSession()->get('id'));
					$file = $this->request->getFile();
					$this->adminController->newPostInfos($post, $file);
				
				} elseif ($action === 'editPostView' && $this->userController->adminAccess() && $this->userController->checkCsrfToken()) {

					$this->adminController->editPostView($this->request->getGet()->get('id'));

				} elseif ($action === 'editPost' && $this->userController->adminAccess() && $this->userController->checkCsrfToken()) {

					$post = $this->request->getPost();

					if ($post->get('deleteMainPicture')) {

						$this->adminController->deleteMainPostPicture($post->get('postId'));
					
					} elseif ($post->get('addParagraph')) {

						$this->adminController->addParagraph($post->get('postId'));
					
					} elseif ($post->get('addCategory')) {
						$this->adminController->addCategory($post);
					
					} elseif ($post->get('updatePostInfos')) {
						$this->adminController->editPostInfos($post);
					
					} elseif ($post->get('editContent')) {

						$post = $this->request->getPost();
						$this->adminController->editParagraph($post);		
					
					} elseif ($post->get('addPicture')) {

						$this->adminController->addPicture($post);
					
					} elseif ($post->get('updatePicture')) {

						$this->adminController->editPostPicture($post);
					}
				
				} elseif ($action === 'deleteContent' && $this->userController->adminAccess() && $this->userController->checkCsrfToken()) {

					$this->adminController->deleteContent($this->request->getGet() && $this->userController->checkCsrfToken());
				
				} elseif ($action === 'deleteCategory' && $this->userController->adminAccess() && $this->userController->checkCsrfToken()) {

					$this->adminController->deleteCategory($this->request->getGet());
				
				} elseif (
					($action === 'publishPost' 
					|| $action === 'publishPostDashboard')
					&& $this->userController->adminAccess() 
					&& $this->userController->checkCsrfToken()
				) {

					$get = $this->request->getGet();
					$this->adminController->publishPost($get);
				
				} elseif (
					($action === 'deletePost' 
					|| $action === 'deletePostDashboard') 
					&& $this->userController->adminAccess()
					&& $this->userController->checkCsrfToken()
				) {

					$get = $this->request->getGet();
					$dashboard = ($action === 'deletePostDashboard') ? 1 : null;
					$this->adminController->deletePost($get, $dashboard);	
				

				// Actions related to Comments managment
				
				} elseif ($action === 'adminComments' && $this->userController->adminAccess()) {

					$get = $this->request->getGet();
					$this->adminController->adminCommentsView($get);
				
				} elseif (
					($action === 'approveComment'
					|| $action === 'approveCommentDashboard'
					|| $action === 'approveCommentView') 
					&& $this->userController->adminAccess()
					&& $this->userController->checkCsrfToken()
				) {

					$get = $this->request->getGet();
					$this->adminController->approveComment($get);
				
				} elseif (
					($action === 'deleteComment'
					|| $action === 'deleteCommentDashboard')
					&& $this->userController->adminAccess() 
					&& $this->userController->checkCsrfToken()
				) {

					$dashboard = ($action === 'deleteCommentDashboard') ? 1 : null;
					$this->adminController->deleteComment($this->request->getGet()->get('id'), $dashboard);		
				

				// Actions related to Users managment
				
				} elseif ($action === 'adminUsers' && $this->userController->adminAccess()) {

					$this->adminController->adminUsersView( $this->request->getGet()->get('sort'));					
				
				} elseif ($action === 'profileUser') {

					$this->adminController->profileUserView($this->request->getGet()->get('id'));
				
				} elseif ($action === 'editUser' && $this->userController->checkCsrfToken()) {

					$this->adminController->editUser($this->request->getGet()->get('id'));
				
				} elseif ($action === 'editUserInfos' && $this->userController->checkCsrfToken()) {

					$post = $this->request->getPost();
					$this->adminController->editUserInfos($post);
				
				} elseif ($action === 'updateProfilePicture' && $this->userController->checkCsrfToken()) {

					$userId = $this->request->getGet()->get('id');
					$this->adminController->updateProfilePicture($userId);


				// Actions related to Contacts managment
				
				} elseif ($action === 'adminContacts' && $this->userController->adminAccess(3)) {

					$this->adminController->adminContactsView($this->request->getGet());
				
				} elseif ($action === 'contactForm') {

					$this->homeController->newContactForm($this->request->getPost());
				
				} elseif ($action === 'contactView' && $this->userController->adminAccess(3)) {

					$this->adminController->adminContactView($this->request->getGet()->get('id'));				
				
				} elseif (($action === 'deleteContact') && $this->userController->adminAccess(3) && $this->userController->checkCsrfToken()) {

					$this->adminController->deleteContact($this->request->getGet()->get('id') && $this->userController->checkCsrfToken());				
				
				} elseif (($action === 'answer') && $this->userController->adminAccess(3) && $this->userController->checkCsrfToken()) {

					$this->adminController->addAnswer($this->request->getPost());
				
				} else {

					throw new Exception('La page demandée n\'existe pas');
				}

			} else {

				if (isset($_GET) && !empty($_GET)) {
					throw new Exception("La page demandée n'existe pas.");
				} else {
					$this->homeController->indexView();
				}
			}

		} catch(Exception $e) {
			$errorMessage = $e->getMessage();
			$this->errorController->errorView($errorMessage);
		}
	}

}




