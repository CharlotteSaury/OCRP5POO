<?php

namespace model;

require_once("model/Manager.php");

class UserManager extends Manager
{
	public function getUserNb()
	{
		$sql = ('SELECT COUNT(*) AS usersNb FROM user');
		$req = $this->dbRequest($sql);

		$totalUsersNb = $req->fetch();
		$usersNb = $totalUsersNb['usersNb'];
		return $usersNb;
	}

	public function getRecentUsers()
	{
		$sql = ('SELECT user.id AS userId,
			user.pseudo AS pseudo,
			user.email AS email,
			user.register_date,
			user_role.role AS role
			FROM user 
			JOIN user_role ON user.user_role_id = user_role.id
			ORDER BY user.register_date DESC
			LIMIT 5');

		$req = $this->dbRequest($sql);
		return $req;
	}
}

