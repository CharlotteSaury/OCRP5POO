<?php

namespace Src\Controller;

use Src\Controller\Controller;
use Src\Controller\HomeController;
use Src\Controller\UserController;
use Config\Request;
use Config\Parameter;
use Config\File;
use Exception;

/**
 * Class AdminController
 * Manage router to appropriate manager redirection associated with backend actions
 */
class AdminController extends Controller

{
	/**
	 * Generate admin dashboard view
	 * @return void
	 */
	public function dashboardView()
	{
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$totalPostsNb = $this->postManager->getPostsNb();
		$approvedCommentsNb = $this->commentManager->getCommentsNb(2);
		$totalCommentsNb = $this->commentManager->getCommentsNb();
		$usersNb = $this->userManager->getUserNb();
		$recentPosts = $this->postManager->getRecentPosts();
		$recentComments = $this->commentManager->getComments(5);
		$users = $this->userManager->getUsers(5);
		$unreadContactsNb = $this->contactManager->getContactsNb(1);

		return $this->view->render('backend', 'dashboardView', ['publishedPostsNb' => $publishedPostsNb,
			'totalPostsNb' => $totalPostsNb,
			'approvedCommentsNb' => $approvedCommentsNb,
			'totalCommentsNb' => $totalCommentsNb,
			'usersNb' => $usersNb,
			'recentPosts' => $recentPosts,
			'recentComments' => $recentComments,
			'users' => $users,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	/**
	 * Generate admin posts list view
	 * @param  Parameter $get     [optionnal sorting informations (date, published/unpublished)]
	 * @return void 
	 */
	public function adminPostsView(Parameter $get = null)
	{
		$totalPostsNb = $this->postManager->getPostsNb();
		$publishedPostsNb = $this->postManager->getPostsNb(2);
		$unpublishedPostsNb = $totalPostsNb - $publishedPostsNb;
		$unreadContactsNb = $this->contactManager->getContactsNb(1);

		if ($get != null) {
			$sorting = $this->getSortingResults($get, 'Articles');
			$status = $sorting[0];
			$sortingDate = $sorting[1];
			$contentTitle = $sorting[2];
		} else {
			$contentTitle = 'Tous les articles';
			$status = null;
			$sortingDate = null;
		}
		$posts = $this->postManager->getPosts($status, $first_post = null, $postsPerPage = null, $sortingDate);

		$allPostsCategories = $this->postManager->getPostsCategories();

		foreach ($allPostsCategories as $key => $value) {

			foreach ($posts as $post) {

				if ($post->getId() == $key) {

					$post->setCategories($value);
				}
			}
		}

		return $this->view->render('backend', 'adminPostsView',
			['contentTitle' => $contentTitle, 
			'totalPostsNb' => $totalPostsNb,
			'unpublishedPostsNb' => $unpublishedPostsNb,
			'posts' => $posts,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession(),
			'get' => $get]);

	}

	/**
	 * Check validity of postId and generate admin single post page
	 * @param  int $postId 
	 * @return void
	 */
	public function adminPostView($postId)
	{
		$errors = $this->validation->exists('postId', $postId);

		if (!$errors) {

			$post = $this->postManager->getPostInfos($postId);
			$post->setCategories($this->postManager->getPostsCategories($postId));
			$contents = $this->contentManager->getContents($postId);
			$postComments = $this->commentManager->getPostComments($postId);	
			$unreadContactsNb = $this->contactManager->getContactsNb(1);

			return $this->view->render('backend', 'adminPostView', 
				['postId' => $postId,
				'post' => $post,
				'contents' => $contents,
				'postComments' => $postComments,
				'unreadContactsNb' => $unreadContactsNb,
				'session' => $this->request->getSession()]);
		}
		throw new Exception('Identifiant de post non valide');
	}

	/**
	 * Generate admin new post page
	 * @param  array $errors  [optional error messages of form input validity]
	 * @return void 
	 */
	public function adminNewPostView($errors = null)
	{
		$unreadContactsNb = $this->contactManager->getContactsNb(1);
		return $this->view->render('backend', 'newPostInfosView',
			['unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession(),
			'errors' => $errors]);
	}

	/**
	 * Check validity of inputs of new post form and redirect to postManager to add new post infos (chapo, title, main_image) to database
	 * @param  Parameter $post [title, chapo]
	 * @param  File $file [optional file datas for main_image uploading]
	 * @return void
	 */
	public function newPostInfos(Parameter $post, File $file = null)
	{
		$errors = $this->validation->validate($post, 'Post');

		if (!$errors) {

			if ($file->get('picture') && $file->get('picture', 'error') != 4) {

				$infos = new HomeController();
				$uploadResults = $infos->pictureUpload($namePicture = 'picture');

				if (strrpos($uploadResults, 'uploads') === false) {

					$this->request->getSession()->set('message', $uploadResults);
					$this->adminNewPostView();

				} else {

					$mainImage = $uploadResults;
					$postId = $this->postManager->newPostInfos($post, $mainImage);
					$this->editPostView($postId);
				}
			
			} else {

				$postId = $this->postManager->newPostInfos($post, $mainImage = null);
				$this->editPostView($postId);
			}

		} else {

			$this->adminNewPostView($errors);
		}
		
	}

	/**
	 * Redirect to postManager to update post status (published 2, unpublished 1)
	 * @param  Parameter $get [$postId]
	 * @return void [redirect to dashboard view or adminPostsView regarding initial page]
	 */
	public function publishPost(Parameter $get)
	{
		$this->postManager->publishPost($get);
		$this->request->getSession()->set('message', 'Statut du post modifié ! ');

		if ($get->get('action') === 'publishPostDashboard') {

			$this->dashboardView();

		} else {

			$this->adminPostsView();
		}
	}

	/**
	 * Check if required postId exists in database and enerate admin edit post page
	 * @param  int $postId  
	 * @param  array $errors  [optional error messages related to post edition form inputs (content, post infos)]
	 * @return void         
	 */
	public function editPostView($postId, $errors = null)
	{

		$errorExists = $this->validation->exists('postId', $postId);

		if (!$errorExists) {

			$post = $this->postManager->getPostInfos($postId);
			$users = $this->userManager->getUsers();
			$post->setCategories($this->postManager->getPostsCategories($postId));
			$contents = $this->contentManager->getContents($postId);
			$unreadContactsNb = $this->contactManager->getContactsNb(1);

			return $this->view->render('backend', 'editPostView', 
				['postId' => $postId,
				'users' => $users,
				'post' => $post,
				'contents' => $contents,
				'unreadContactsNb' => $unreadContactsNb,
				'session' => $this->request->getSession(),
				'errors' => $errors]);
		}
		throw new Exception('Identifiant de billet non valide.');
	}

	/**
	 * Redirect to postManager to delete main post picture in database and update post last update date
	 * @param  int $postId 
	 * @return void        
	 */
	public function deleteMainPostPicture($postId)
	{
		$post = $this->postManager->getPostInfos($postId);
		$mainImgUrl = $post->getMainImage();
		unlink($mainImgUrl);

		$this->postManager->deleteMainPostPicture($postId);
		$this->postManager->dateUpdate($postId);
		$this->request->getSession()->set('message', 'Photo supprimée ! ');
		$this->editPostView($postId);
	}

	/**
	 * Upload post picture and call contentManager to update post picture url in database
	 * @param  Parameter $post [$postId]
	 * @return void          
	 */
	public function editPostPicture(Parameter $post)
	{
		foreach ($_FILES AS $key => $value) {

			if ($value['name'] !='') {
				$contentId = substr($key, 7);
			}
		}

		$homeController = new HomeController();
		$uploadResults = $homeController->pictureUpload($namePicture = 'picture' . $contentId);

		if (strrpos($uploadResults, 'uploads') === false) {

			$this->request->getSession()->set('message', $uploadResults);

		} else {

			$content = $this->contentManager->getContent($contentId);
			$oldImgUrl = $content->getContent();
			$this->contentManager->editContent($contentId, $uploadResults);
			$this->postManager->dateUpdate($post->get('postId'));
			unlink($oldImgUrl);
			$this->request->getSession()->set('message', 'Image modifiée !');
		}
		$this->editPostView($post->get('postId'));

	}

	/**
	 * Call contenttManager to delete content (picture or paragraph) from database. If picture, delete picture from upload folder.
	 * @param  Parameter $get [type of content, content, id]
	 * @return void         
	 */
	public function deleteContent(Parameter $get)
	{
		if ($get->get('type') == 1) {

			$content = $this->contentManager->getContent($get->get('content'));
			$imgUrl = $content->getContent();
			unlink($imgUrl);
		}

		$this->contentManager->deleteContent($get->get('content'));
		$this->postManager->dateUpdate($get->get('id'));
		$this->request->getSession()->set('message', 'Contenu supprimé ! ');
		$this->editPostView($get->get('id'));
	}

	/**
	 * Call contentManager to add new content to database, and call postManager to update last update date
	 * @param int $postId
	 * @return  void 
	 */
	public function addParagraph($postId)
	{
		$this->contentManager->addContent($postId);
		$this->postManager->dateUpdate($postId);
		$this->request->getSession()->set('message', 'Bloc paragraphe ajouté ! ');
		$this->editPostView($postId);
	}

	/**
	 * Call contentManager to update paragraph content in database after input validity check
	 * @param  Parameter $post [contentId, content, postId]
	 * @return void          
	 */
	public function editParagraph(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Content');

		if (!$errors) {

			$contentId = $post->get('editContent');
			$this->contentManager->editContent($contentId, $post->get($contentId));
			$this->postManager->dateUpdate($post->get('postId'));
			$this->request->getSession()->set('message', 'Bloc paragraphe enregistré ! ');
		
		} else {

			$message = '';
			foreach($errors as $key => $value) {
				$message .= '<p>' . $value . '</p>';
			}
			$this->request->getSession()->set('message', $message);
		}
		$this->editPostView($post->get('postId'));
	}

	/**
	 * Upload picture and call contentManager to add new content to database 
	 * @param Parameter $post [postId]
	 */
	public function addPicture(Parameter $post)
	{
		$homeController = new HomeController();
		$uploadResults = $homeController->pictureUpload($namePicture = 'picture');

		if (strrpos($uploadResults, 'uploads') === false) {

			$this->request->getSession()->set('message', $uploadResults);
		
		} else {
			$this->contentManager->addContent($post->get('postId'), $uploadResults);
			$this->postManager->dateUpdate($post->get('postId'));
			$this->request->getSession()->set('message', 'Image ajoutée !');
		}
		$this->editPostView($post->get('postId'));
	}

	/**
	 * Call postManager to add new category to database after input validity check
	 * @param Parameter $post [$postId, $categoryName]
	 * @return  void
	 */
	public function addCategory(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Category');

		if (!$errors) {

			$this->request->getSession()->set('message', $this->postManager->addCategory($post->get('postId'), $post->get('categoryName')));
			$this->postManager->dateUpdate($post->get('postId'));
		
		} else {

			$this->request->getSession()->set('message', $errors['categoryName']);
		}
		$this->editPostView($post->get('postId'));
		
	}

	/**
	 * Call postManager to delete association of category with post in post_category table
	 * @param  Parameter $get [postId, $categoryId]
	 * @return void         
	 */
	public function deleteCategory(Parameter $get)
	{
		$this->postManager->deleteCategory($get);
		$this->postManager->dateUpdate($get->get('id'));
		$this->request->getSession()->set('message', 'Catégorie supprimée ! ');
		$this->editPostView($get->get('id'));
	}

	/**
	 * Upload main post picture if provided and call postManager to edit post infos in database after input validity check
	 * @param  Parameter $post [title, chapo]
	 * @return void          
	 */
	public function editPostInfos(Parameter $post)
	{
		$file = $this->request->getFile();
		$homeController = new HomeController();

		if ($file->get('MainPicture') && $file->get('MainPicture', 'error') != 4) {

			$uploadResults = $homeController->pictureUpload($namePicture = 'MainPicture');

			if (strrpos($uploadResults, 'uploads') === false) {

				$this->request->getSession()->set('message', "Information(s) modifiée(s) \n 
				/!\ Erreur de téléchargement de l'image principale : " . $uploadResults);
			
			} else {

				$post->set('main_image', $uploadResults);
				$this->request->getSession()->set('message', "Information(s) modifiée(s) ! ");
			}
		
		} else {

			$this->request->getSession()->set('message', "Information(s) modifiée(s) ! ");
		}

		$errors = $this->validation->validate($post, 'Post');

		if (!$errors) {

			$this->postManager->editPostInfos($post);
			$this->postManager->dateUpdate($post->get('postId'));
			$this->editPostView($post->get('postId'));
		
		} else {

			$message = '';
			foreach($errors as $key => $value) {
				$message .= '<p>' . $value . '</p>';
			}
			$this->request->getSession()->set('message', $message);
			$this->editPostView($post->get('postId'));
		}
	}

	/**
	 * Call postManager, commentManager and contentManager to respectively delete post, and associated comments, categories and contents from database
	 * @param  Parameter $get       [postId]
	 * @param  int $dashboard [optional dashboard parameter to redirect to the initial page =1 if dashboard view]
	 * @return void
	 */
	public function deletePost(Parameter $get, $dashboard = null)
	{
		$this->postManager->deletePost($get->get('id'));
		$this->commentManager->deleteCommentsFromPost($get->get('id'));
		$this->contentManager->deleteContentsFromPost($get->get('id'));
		$this->postManager->deleteCategory($get);
		$this->request->getSession()->set('message', "Post supprimé ! ");

		if ($dashboard != null) {
			$this->dashboardView();
		} else {
			$this->adminPostsView();
		}
	}

	/**
	 * Generate admin comments view with optional sorting preferences
	 * @param  Parameter $get     [optional sorting preferences]
	 * @return void
	 */
	public function adminCommentsView(Parameter $get = null)
	{
		$totalCommentsNb = $this->commentManager->getCommentsNb();
		$approvedCommentsNb = $this->commentManager->getCommentsNb(2);
		$unapprovedCommentsNb = $totalCommentsNb - $approvedCommentsNb;
		$unreadContactsNb = $this->contactManager->getContactsNb(1);
		
		if ($get != null) {
			$sorting = $this->getSortingResults($get, 'Commentaires');
			$status = $sorting[0];
			$sortingDate = $sorting[1];
			$contentTitle = $sorting[2];
		} else {
			$contentTitle = 'Tous les Commentaires';
			$status = $sortingDate = null;
		}

		$allComments = $this->commentManager->getComments($commentsNb = null, $status, $sortingDate);
		
		return $this->view->render('backend', 'adminCommentsView', 
			['totalCommentsNb' => $totalCommentsNb,
			'unapprovedCommentsNb' => $unapprovedCommentsNb,
			'contentTitle' => $contentTitle,
			'status' => $status,
			'allComments' => $allComments,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	/**
	 * Call commentManager to update comment status in database
	 * @param  Parameter $get [commentId]
	 * @return void         
	 */
	public function approveComment(Parameter $get)
	{
		$this->commentManager->approveComment($get->get('id'));
		$this->request->getSession()->set('message', "Commentaire approuvé ! ");

		if ($get->get('action') == 'approveCommentDashboard') {
			$this->dashboardView();
		
		} elseif ($get->get('action') == 'approveCommentView') {
			$this->adminPostView($get->get('post'));

		} else {
			$this->adminCommentsView();		
		}		
	}

	/**
	 * Call commentManager to delete comment from database
	 * @param  int $commentId 
	 * @param  int $dashboard [optional dashboard parameter to redirect to the initial page =1 if dashboard view]
	 * @return void            
	 */
	public function deleteComment($commentId, $dashboard = null)
	{
		$this->commentManager->deleteComment($commentId);
		$this->request->getSession()->set('message', "Commentaire supprimé ! ");

		if ($dashboard != null) {
			$this->dashboardView();
		} else {
			$this->adminCommentsView();
		}
	}

	/**
	 * Generate admin users list page with role sorting
	 * @param  int $userRoleId 
	 * @return void             
	 */
	public function adminUsersView($userRoleId = null)
	{
		if ($userRoleId != null) {

			if ($userRoleId == 1) {
				$contentTitle = 'Administrateurs';

			} elseif ($userRoleId == 2) {
				$contentTitle = 'Utilisateurs';

			} elseif ($userRoleId == 3) {
				$contentTitle = 'Super Admin';

			} else {
				throw new Exception("La page que vous recherchez n'existe pas. ");
			}

		} else {
			$contentTitle = 'Tous les Utilisateurs';
		}

		$allUsersNb = $this->userManager->getUserNb();
		$superAdminNb = $this->userManager->getUserNb(3);
		$adminNb = $this->userManager->getUserNb(1);
		$usersNb = $this->userManager->getUserNb(2);

		
		$users = $this->userManager->getUsers(null, $userRoleId);
		$unreadContactsNb = $this->contactManager->getContactsNb(1);
		
		return $this->view->render('backend', 'adminUsersView',
			['allUsersNb' => $allUsersNb,
			'superAdminNb' => $superAdminNb,
			'adminNb' => $adminNb,
			'usersNb' => $usersNb,
			'contentTitle' => $contentTitle,
			'users' => $users,
			'unreadContactsNb' => $unreadContactsNb,
			'session' => $this->request->getSession()]);
	}

	/**
	 * Generate profile user view 
	 * @param  int $userId  
	 * @return void          
	 */
	public function profileUserView($userId)
	{
		$errorExists = $this->validation->exists('userId', $userId);

		if (!$errorExists) {
			$user = $this->userManager->getUser($userId);
			$unreadContactsNb = $this->contactManager->getContactsNb(1);

			return $this->view->render('backend', 'profileUserView',
				['user' => $user,
				'unreadContactsNb' => $unreadContactsNb,
				'userId' => $userId,
				'session' => $this->request->getSession()]);
		}
		throw new Exception('Le profil demandé n\'existe pas');	
	}

	/**
	 * Generate user profile edition page
	 * @param  int $userId  
	 * @param  array $errors  [optional error messages from input validity checking]
	 * @return void
	 */
	public function editUserView($userId, $errors = null)
	{
		$user = $this->userManager->getUser($userId);
		$unreadContactsNb = $this->contactManager->getContactsNb(1);

		return $this->view->render('backend', 'editUserView',
			['user' => $user,
			'unreadContactsNb' => $unreadContactsNb,
			'userId' => $userId,
			'session' => $this->request->getSession(),
			'errors' => $errors]);
	}

	/**
	 * Check if required user exists in database and redirect to edit user view
	 * @param  int $userId 
	 * @return void
	 */
	public function editUser($userId)
	{
		$errorExists = $this->validation->exists('userId', $userId);
		$userController = new UserController;

		if(!$errorExists) {
			$currentUserId = $this->request->getSession()->get('id');
			
			if ($currentUserId == $userId || $userController->adminAccess()) {
				$this->editUserView($userId);
			
			} else {
				throw new Exception('Vous n\'avez pas accès à cette page');
			}

		} else {
			throw new Exception('Le profil demandé n\'existe pas');
		}
	}

	/**
	 * Call userManager to update user informations after input validity check. Update session variables if user updated its own profile
	 * @param  Parameter $post [pseudo, email, first_name, last_name, home, website...]
	 * @return void
	 */
	public function editUserInfos(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'User');
		$userController = new UserController;

		if (!$errors) {

			if (!$post->get('user_role_id')) {
				$post->set('user_role_id', $this->request->getSession()->get('role'));
			}

			if (empty($post->get('birth_date'))) {
				$this->deleteBirthDate($post->get('id'));
			
			} else {
				$checkDate = explode('-', $post->get('birth_date'));		
				$post->set('birth_date', $checkDate[2] . '-' . $checkDate[1] . '-' . $checkDate[0]);
			}

			$currentUserId = $this->request->getSession()->get('id');
			$this->userManager->editUserInfos($post);
			$this->request->getSession()->set('message', "Profil modifié ! ");
			$this->profileUserView($post->get('id'));

			if ($currentUserId == $post->get('id')) {
				$userController->newUserSession($post->get('email'));
			}
		
		} else {
			$this->request->getSession()->set('message', 'Le profil n\'a pas pu être modifié.');
			$this->editUserView($post->get('id'), $errors);
		}
	}

	/**
	 * Call userManager to delete user birth date in database
	 * @param  int $userId 
	 * @return void         
	 */
	public function deleteBirthDate($userId)
	{
		$this->userManager->deleteBirthDate($userId);
	}

	/**
	 * Upload new profile picture and delete old one from upload folder and call userManager to update user profile picture in database
	 * @param  int $userId
	 * @return void       
	 */
	public function updateProfilePicture($userId)
	{
		$homeController = new HomeController();
		$uploadResults = $homeController->pictureUpload($namePicture = 'picture');

		if (strrpos($uploadResults, 'uploads') === false) {
			$this->request->getSession()->set('message', $uploadResults);
			$this->profileUserView($userId);
		
		} else {
			$user = $this->userManager->getUser($userId);
			$oldAvatarUrl = $user->getAvatar();
			
			if ($oldAvatarUrl != 'public/images/profile.png') {
				unlink($oldAvatarUrl);
			}

			$this->userManager->updateProfilePicture($userId, $uploadResults);
			$this->request->getSession()->set('message', 'Photo de profil modifiée !');
			$this->profileUserView($userId);

			// Si l'utilisateur a modifié son propre profil, alors on modifie les variables de session

			$currentUserId = $this->request->getSession()->get('id');	
			if ($currentUserId == $userId) {
				$this->request->getSession()->set('avatar', $uploadResults);
			}
		}
	}

	/**
	 * Generate admin contacts list page with optionnal sorting preferences
	 * @param  Parameter $get     [optional sorting preferences]
	 * @return void                  
	 */
	public function adminContactsView(Parameter $get = null)
	{
		$allContactsNb = $this->contactManager->getContactsNb();
		$unreadContactsNb = $this->contactManager->getContactsNb(1);

		if ($get != null) {
			$sorting = $this->getSortingResults($get, 'Contacts');
			$status = $sorting[0];
			$sortingDate = $sorting[1];
			$contentTitle = $sorting[2];
		} else {
			$contentTitle = 'Tous les contacts';
			$status = 1;
			$sortingDate = null;
		}
		$allContacts = $this->contactManager->getContacts($contactId = null, $status, $sortingDate);
		
		
		return $this->view->render('backend', 'adminContactsView', 
			['allContactsNb' => $allContactsNb,
			'unreadContactsNb' => $unreadContactsNb,
			'contentTitle' => $contentTitle,
			'allContacts' => $allContacts,
			'session' => $this->request->getSession(),
			'get' => $get]);
	}

	/**
	 * Generate admin single contact page and associated optional answer
	 * @param  int $contactId 
	 * @param  array $errors    [optional error messages generated by answer input validity check]
	 * @return void            
	 */
	public function adminContactView($contactId, $errors = null)
	{
		$errorExists = $this->validation->exists('contactId', $contactId);

		if (!$errorExists) {
			$contact = $this->contactManager->getContacts($contactId);

			$currentStatus = $contact->getStatusId();

			if ($currentStatus != 3 ) {
				$answer = null;
				$this->contactManager->updateStatus($contactId, 2);
			
			} else {
				$answer = $this->contactManager->getAnswer($contactId);
			}

			$unreadContactsNb = $this->contactManager->getContactsNb(1);

			return $this->view->render('backend', 'adminContactView', 
				['contactId' => $contactId,
				'contact' => $contact,
				'currentStatus' => $currentStatus,
				'answer' => $answer,
				'unreadContactsNb' => $unreadContactsNb,
				'session' => $this->request->getSession(),
				'errors' => $errors]);
		
		} else {
			throw new Exception('L\'identifiant du contact n\'est pas valide.');
		}
	}

	/**
	 * Call contactManager to delete contact from database
	 * @param  int $contactId
	 * @param  int $dashboard [optional dashboard parameter to redirect to the initial page =1 if dashboard view]
	 * @return void            
	 */
	public function deleteContact($contactId, $dashboard = null)
	{
		$this->contactManager->deleteContact($contactId);
		$this->request->getSession()->set('message', "Message supprimé ! ");
		$this->adminContactsView();
		
	}

	/**
	 * Send email to user with admin answer email to previous contact
	 * @param  Parameter $post [answerSubject, answerContent, email, contactId]
	 * @return void          
	 */
	public function adminAnswerEmail(Parameter $post)
	{
		$contact = $this->contactManager->getContacts($post->get('contactId'));

		$subject = $post->get('answerSubject');
		$headers = "From: " . BLOG_AUTHOR . "/r/n";
		$message = $post->get('answerContent') . 
					" /r/n
					----------------/r/n/r/n
					De: " . $contact->getName() . " <" . $post->get('email') . ">/r/n
					Le: " . $contact->getDateMessage() . "/r/n
					Objet: " . $contact->getSubject() . "/r/n/r/n"
					. $contact->getContent();
			

		$message = wordwrap($message, 70, "\r\n");
		mail($post->get('email'), $subject, $message, $headers);	     
	}

	/**
	 * Call contactManager to add contact answer to database
	 * @param Parameter $post [contactId, answerSubject, answerContent]
	 * @return  void
	 */
	public function addAnswer(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Answer');

		if(!$errors) {

			$contact = $this->contactManager->getContacts($post->get('contactId'));
			$this->contactManager->updateStatus($post->get('contactId'), 3);   
			$this->contactManager->addAnswer($post);
			$this->adminAnswerEmail($post);
			$this->request->getSession()->set('message', "La réponse a bien été envoyée.");
			$this->adminContactView($post->get('contactId'));  
		
		} else {

			if (isset($errors['contactId'])) {
				throw new Exception($errors['contactId']);
			
			} elseif (isset($errors['email'])) {
				throw new Exception($errors['email']);
			}

			$this->adminContactView($post->get('contactId'), $errors);
		}
	}

	/**
	 * Get sorting results from url
	 * @param  Parameter $get          [optional sorting by status (sort) or date]
	 * @param  string $name [entities to sort : Articles, Commentaires, Contacts]
	 * @return array[$status of entity to display, sorting date, content title]
	 */
	public function getSortingResults(Parameter $get, $name)
	{
		$type = ['Articles' => ' non publiés', 'Commentaires' => ' non approuvés', 'Contacts' => ' non lus'];
		
		$status = $get->get('status');
		$date = $get->get('date');

		if ($status || $date) {
			if ($status && !$date) {
				foreach ($type as $key => $value) {
					if ($key == $name) {
						$contentTitle = $key . $value;
					}
				}
				$sortingDate = null;

			} elseif (!$status && $date) {
				foreach ($type as $key => $value) {
					if ($key == $name) {
						$contentTitle = 'Tous les ' . strtolower($name);
					}
				}
				$status = null;
				$sortingDate = $date;

			} else {
				foreach ($type as $key => $value) {
					if ($key == $name) {
						$contentTitle = $key . $value;
					}
				}
				$sortingDate = $date;
			} 	
		} else {
			foreach ($type as $key => $value) {
					if ($key == $name) {
						$contentTitle = 'Tous les ' . strtolower($name);
					}
				}
			$status = $sortingDate = null;
		}
		return $sorting = [$status, $sortingDate, $contentTitle];
	}
	
}


