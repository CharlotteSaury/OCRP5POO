<?php

namespace src\controller;

require_once 'src/controller/Controller.php';
use src\controller\Controller;

class ErrorController extends Controller

{
	public function errorView($errorMessage) 
	{
		return $this->view->render('frontend', 'errorView', ['errorMessage' => $errorMessage]);
	}

}


