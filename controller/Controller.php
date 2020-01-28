<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');

class controller

{
	public function indexView() 
	{
		require('./view/frontend/indexView.php');
	}
}


