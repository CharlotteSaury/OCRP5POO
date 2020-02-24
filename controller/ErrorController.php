<?php

namespace controller;

require_once './view/View.php';

use view\View;

class ErrorController

{
	private $_view;

	public function __construct()
	{
		$this->_view = new View();
	}

	public function errorView($errorMessage) 
	{
		return $this->_view->render('frontend', 'errorView', ['errorMessage' => $errorMessage]);
	}

}


