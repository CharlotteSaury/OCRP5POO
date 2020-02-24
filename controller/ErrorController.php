<?php

namespace controller;

require_once './controller/Controller.php';
use controller\Controller;

class ErrorController extends Controller

{
	public function errorView($errorMessage) 
	{
		return $this->view->render('frontend', 'errorView', ['errorMessage' => $errorMessage]);
	}

}


