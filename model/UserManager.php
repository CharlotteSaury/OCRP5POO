<?php

namespace model;

require_once 'model/Manager.php';

class UserManager extends Manager
{
	public function addUser ($pseudo, $pass, $email)
	{
		// Activation_code generation
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random_code = substr(str_shuffle($permitted_chars), 0, 10);
		$activation_code = password_hash($random_code, PASSWORD_DEFAULT);

		$sql = 'INSERT INTO user (pseudo, email, password, user_role_id, register_date, activation_code, avatar)
				VALUES (:pseudo, :email, :pass, 2, NOW(), :activation_code, "public/images/profile.jpg")';

		$req = $this->dbRequest($sql, array($pseudo, $email, $pass, $activation_code));
		$req->bindValue('pseudo', $pseudo);
		$req->bindValue('email', $email);
		$req->bindValue('pass', $pass);
		$req->bindValue('activation_code', $activation_code);

		$req->execute();
		return $activation_code;
	}

	public function pseudoExists($pseudo, $userId = null)
	{
		$sql = 'SELECT COUNT(*) AS nbUser FROM user WHERE pseudo = :pseudo';
		
		if ($userId != null)
		{
			$sql .= ' AND id != :userId';
			$req = $this->dbRequest($sql, array($pseudo, $userId));
			$req->bindValue('userId', $userId);
		}
		else
		{
			$req = $this->dbRequest($sql, array($pseudo));
		}

		$req->bindValue('pseudo', $pseudo);
		$req->execute();

		$pseudoExists = $req->fetch(\PDO::FETCH_ASSOC);
		return $pseudoExists['nbUser'];
	}

	public function emailExists($email, $userId = null)
	{
		$sql = 'SELECT COUNT(*) AS nbUser FROM user WHERE email = :email';
		
		if ($userId != null)
		{
			$sql .= ' AND id != :userId';
			$req = $this->dbRequest($sql, array($email, $userId));
			$req->bindValue('userId', $userId);
		}
		else
		{
			$req = $this->dbRequest($sql, array($email));
		}

		$req->bindValue('email', $email);
		$req->execute();

		$emailExists = $req->fetch(\PDO::FETCH_ASSOC);
		return $emailExists['nbUser'];
	}

	public function getUserCode($email)
	{
		$sql = 'SELECT activation_code from user WHERE email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();
		$donnees = $req->fetch(\PDO::FETCH_ASSOC);

		return $activation_code = $donnees['activation_code'];
	}

	public function userActivation($email)
	{
		$sql = 'UPDATE user SET activation_code = null WHERE email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();
	}

	public function getUserPass($email)
	{
		$sql = 'SELECT password from user WHERE email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();
		$donnees = $req->fetch(\PDO::FETCH_ASSOC);

		return $user_pass = $donnees['password'];
	}

	public function getSessionInfos($email)
	{
		$sql = 'SELECT user.id AS userId,
				user.pseudo AS pseudo,
				user.user_role_id AS role,
				user.avatar AS avatar
				FROM user
				WHERE user.email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();

		$sessionInfos = $req->fetchAll(\PDO::FETCH_ASSOC);
		return $sessionInfos;
	}

	public function newPassCode($email, $reinit_code)
	{
		$sql = 'UPDATE user SET reinitialization_code = :reinitialization_code
				WHERE email = :email';

		$req = $this->dbRequest($sql, array($reinit_code, $email));
		$req->bindValue('reinitialization_code', $reinit_code);
		$req->bindValue('email', $email);

		$req->execute();
	}

	public function getNewPassCode($email)
	{
		$sql = 'SELECT reinitialization_code from user WHERE email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();
		$donnees = $req->fetch(\PDO::FETCH_ASSOC);

		return $reinit_code = $donnees['reinitialization_code'];
	}

	public function newUserPass($email, $newPass)
	{
		$sql = 'UPDATE user SET password = :newPass, reinitialization_code = null WHERE email = :email';
		$req = $this->dbRequest($sql, array($newPass, $email));
		$req->bindValue('newPass', $newPass);
		$req->bindValue('email', $email);
		$req->execute();
	}

	public function getUserRole($userId)
	{
		$sql = 'SELECT user_role_id from user WHERE id = :userId';

		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();

		$donnees = $req->fetch(\PDO::FETCH_ASSOC);

		return $role = $donnees['user_role_id'];
	}

	public function getUserNb($userRoleId = null)
	{
		$sql = 'SELECT COUNT(*) AS usersNb FROM user';

		if ($userRoleId != null)
		{
			$sql .= ' WHERE user.user_role_id = ' . $userRoleId;
		}

		$req = $this->dbRequest($sql);

		$donnees = $req->fetch();
		$usersNb = $donnees['usersNb'];
		return $usersNb;
	}

	public function getUsers($usersNb = null, $usersActivity = null, $userRoleId = null)
	{
		$sql = 'SELECT user.id AS userId,
				user.pseudo AS pseudo,
				user.email AS email,
				DATE_FORMAT(user.register_date, \'%d-%m-%Y\') AS register_date,
				user_role.role AS role';

		if ($usersActivity !== null)
		{
			$sql .= ', (SELECT COUNT(*) FROM post WHERE post.user_id = userId) AS postsNb, 
				(SELECT COUNT(*) FROM comment WHERE comment.user_id = userId) AS commentsNb';
		}		
		
		$sql .= ' FROM user 
				JOIN user_role ON user.user_role_id = user_role.id';

		if ($userRoleId != null)
		{
			$sql .= ' WHERE user.user_role_id = ' . $userRoleId;
		}

		$sql .= ' ORDER BY user.register_date DESC';

		if ($usersNb !== null)
		{
			$sql.= ' LIMIT ' . $usersNb;
		}

		$req = $this->dbRequest($sql);
		return $req;
	}

	public function getUserInfos($userId)
	{
		$sql = 'SELECT user.id AS userId,
				user.pseudo AS pseudo,
				user.email AS email,
				DATE_FORMAT(user.register_date, \'%d-%m-%Y\') AS register_date,
				user_role.role AS role,
				user.user_role_id AS roleId,
				user.first_name AS first_name,
				user.last_name AS last_name,
				user.mobile AS mobile,
				user.website AS website,
				user.home AS home,
				DATE_FORMAT(user.birth_date, \'%d-%m-%Y\') AS birth_date,
				user.avatar AS avatar,
				user.user_about AS about
				FROM user 
				JOIN user_role ON user.user_role_id = user_role.id
				WHERE user.id = :userId';

		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();

		$userInfos = $req->fetchAll(\PDO::FETCH_ASSOC);
		return $userInfos;
	}

	public function editUserInfos($newUserInfos)
	{
		$sql = 'UPDATE user SET';

		foreach ($newUserInfos as $key => $value)
		{
			if ($key != 'user_role_id')
			{
				$sql .= ' ' . $key . '="' . $value . '", ';
			}
			else  
			{
				$sql .= ' ' . $key . '="' . $value . '"';
			}			
		}

		$sql .= ' WHERE user.id = :id';
		$req = $this->dbRequest($sql, array($newUserInfos['id']));
		$req->bindValue('id', $newUserInfos['id'], \PDO::PARAM_INT);
		$req->execute();
	}

	public function deleteBirthDate($userId)
	{
		$sql = 'UPDATE user SET birth_date = NULL WHERE user.id = :userId';
		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function getUserPostsNb($userId)
	{
		$sql = 'SELECT COUNT(*) AS postsNb FROM post WHERE post.user_id = :userId';
		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
		$userPostsNb = $req->fetch();
		return $userPostsNb;
	}

	public function getUsersActivity()
	{
		$sql = 'SELECT DISTINCT user.id AS userId, 
				(SELECT COUNT(*) AS postsNb FROM post WHERE post.user_id = userId) AS postsNb, 
				(SELECT COUNT(*) AS commentsNb FROM comment WHERE comment.user_id = userId) AS commentsNb
				FROM user
				LEFT JOIN post ON user.id = post.user_id
				LEFT JOIN comment ON user.id = comment.user_id
				ORDER BY user.id DESC';

		$req = $this->dbRequest($sql);

		while ($usersActivities = $req->fetch(\PDO::FETCH_ASSOC))
		{
			foreach ($usersActivities as $key => $value)
			{
				$usersActivityTable = array($key => $value);
			}
		}
		return $usersActivityTable;
	}

	public function getUserCommentsNb($userId)
	{
		$sql = 'SELECT COUNT(*) AS commentsNb FROM comment WHERE comment.user_id = :userId';
		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
		$userCommentsNb = $req->fetch();
		return $userCommentsNb;
	}

	public function updateProfilePicture($userId, $avatarUrl)
	{
		$sql = 'UPDATE user SET avatar = :avatarUrl WHERE id = :userId';

		$req = $this->dbRequest($sql, array($avatarUrl, $userId));
		$req->bindValue('avatarUrl', $avatarUrl);
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
	}
	
}

