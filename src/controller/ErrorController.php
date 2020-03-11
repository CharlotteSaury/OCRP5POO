<?php

namespace src\controller;

use src\controller\Controller;


/**
 * Class ErrorController
 * Manage redirection to error view
 */
class ErrorController extends Controller

{
	/**
	 * Return error view page
	 * @param  string $errorMessage [errorMessage from Exception throws]
	 * @return void [return error view page]
	 */
	public function errorView($errorMessage) 
	{
		return $this->view->render('frontend', 'errorView', 
			['errorMessage' => $errorMessage,
			'session' => $this->request->getSession()]);
	}

}


