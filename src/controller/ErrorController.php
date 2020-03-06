<?php

namespace src\controller;

use src\controller\Controller;

class ErrorController extends Controller

{
	public function errorView($errorMessage) 
	{
		return $this->view->render('frontend', 'errorView', 
			['errorMessage' => $errorMessage,
			'session' => $this->request->getSession()]);
	}

}


