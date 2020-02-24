<?php

namespace controller;

class ErrorController

{
	public function errorView($errorMessage) 
	{
			require './view/errorView.php';
	}

}


