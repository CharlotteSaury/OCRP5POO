<?php

require_once 'config/dev.php';
require_once 'vendor/autoload.php';

use Src\Controller\Router;

session_name('blog');
session_start();
$router = new Router();

if (
	isset($_COOKIE['auth']) 
	&& isset($_COOKIE['email']) 
	&& !empty($_COOKIE['auth']) 
	&& !isset($_SESSION['id'])
) {
	$auth = htmlspecialchars($_COOKIE['auth']);
	$auth = explode('-----', $auth);
	$email = htmlspecialchars($_COOKIE['email']);
	$ip = $_SERVER['REMOTE_ADDR'];

	if (password_verify($email, $auth[0]) && password_verify($ip, $auth[1])) {
		$router->connexionAuto($email);

	} else {
		$router->routerRequest();
	}

} else {
	$router->routerRequest();
}




