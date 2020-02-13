<?php

require_once('controller\Router.php');

use controller\Router;

var_dump($_COOKIE);

if (isset($_COOKIE['auth']) && !empty($_COOKIE['auth']) && !isset($_SESSION['id']))
{
	$auth = htmlspecialchars($_COOKIE['auth']);
	$auth = explode('-----', $auth);
	$ip = $_SERVER['REMOTE_ADDR'];

	if (password_verify($ip, $auth[1])) // rajouter vérification email
	{
		var_dump('connexion auto');
		session_start();
		var_dump($_SESSION);

		$router = new Router();
		$router->connexionAuto($auth[0]);
	}
	else
	{
		session_start();
		$router = new Router();
		$router->routerRequest();
	}
}
else
{
	session_start();
	var_dump($_SESSION);
	$router = new Router();
	$router->routerRequest();
}




