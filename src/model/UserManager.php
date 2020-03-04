<?php

namespace src\model;

use config\Parameter;

class UserManager extends Manager
{
	public function getUsers($usersNb = null, $userRoleId = null)
	{
		$sql = 'SELECT user.id AS id,
				user.pseudo AS pseudo,
				user.email AS email,
				DATE_FORMAT(user.register_date, \'%d-%m-%Y\') AS registerDate,
				user_role.role AS role, 
				(SELECT COUNT(*) FROM post WHERE post.user_id = user.id) AS postsNb, 
				(SELECT COUNT(*) FROM comment WHERE comment.user_id = user.id) AS commentsNb 
				FROM user 
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

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\User');
		
		$users = $req->fetchAll();
		return $users;
	}

	public function getUser($userId = null, $email = null)
	{
		$sql = 'SELECT user.id AS id,
				user.pseudo AS pseudo,
				user.email AS email,
				user.password AS password,
				DATE_FORMAT(user.register_date, \'%d-%m-%Y\') AS registerDate,
				user.user_role_id AS userRoleId,
				user_role.role AS role,
				user.first_name AS firstName,
				user.last_name AS lastName,
				user.mobile AS mobile,
				user.website AS website,
				user.home AS home,
				DATE_FORMAT(user.birth_date, \'%d-%m-%Y\') AS birthDate,
				user.avatar AS avatar,
				user.user_about AS userAbout,
				user.activation_code AS actCode,
				user.reinitialization_code AS reinitCode,
				(SELECT COUNT(*) FROM post WHERE post.user_id = user.id) AS postsNb,
				(SELECT COUNT(*) FROM comment WHERE comment.user_id = user.id) AS commentsNb 
				FROM user 
				JOIN user_role ON user.user_role_id = user_role.id';

		if ($userId != null)
		{
			$sql .= ' WHERE user.id = :userId';
			$req = $this->dbRequest($sql, array($userId));
			$req->bindValue('userId', $userId, \PDO::PARAM_INT);	
		}
		else
		{
			$sql .= ' WHERE user.email = :email';
			$req = $this->dbRequest($sql, array($email));
			$req->bindValue('email', $email);
		}
		
		$req->execute();

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\User');
		$user = $req->fetch();
		return $user;
	}

	public function addUser ($post)
	{
		// Activation_code generation
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random_code = substr(str_shuffle($permitted_chars), 0, 10);
		$activation_code = password_hash($random_code, PASSWORD_DEFAULT);

		$sql = 'INSERT INTO user (pseudo, email, password, user_role_id, register_date, activation_code, avatar)
				VALUES (:pseudo, :email, :pass, 2, NOW(), :activation_code, "public/images/profile.png")';

		$req = $this->dbRequest($sql, array($post->get('pseudo'), $post->get('email'), $post->get('pass'), $activation_code));
		$req->bindValue('pseudo', $post->get('pseudo'));
		$req->bindValue('email', $post->get('email'));
		$req->bindValue('pass', $post->get('pass'));
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

		$pseudoExists = $req->fetch(\PDO::FETCH_COLUMN);
		return $pseudoExists;
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

		$emailExists = $req->fetch(\PDO::FETCH_COLUMN);
		return $emailExists;
	}

	public function userActivation($email)
	{
		$sql = 'UPDATE user SET activation_code = null WHERE email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();
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

	public function newUserPass($email, $newPass)
	{
		$sql = 'UPDATE user SET password = :newPass, reinitialization_code = null WHERE email = :email';
		$req = $this->dbRequest($sql, array($newPass, $email));
		$req->bindValue('newPass', $newPass);
		$req->bindValue('email', $email);
		$req->execute();
	}

	public function getUserNb($userRoleId = null)
	{
		$sql = 'SELECT COUNT(*) AS usersNb FROM user';

		if ($userRoleId != null)
		{
			$sql .= ' WHERE user.user_role_id = ' . $userRoleId;
		}

		$req = $this->dbRequest($sql);

		$usersNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $usersNb;
	}

	public function editUserInfos(Parameter $post)
	{
		$sql = 'UPDATE user SET';

		foreach ($post->all() as $key => $value)
		{
			if ($key != 'user_role_id')
			{
				if ($key != 'birth_date')
				{
					$sql .= ' ' . $key . '="' . $value . '", ';
				}
				else
				{
					if ($value != '')
					{
						$sql .= ' ' . $key . '="' . $value . '", ';
					}
				}
			}
			else  
			{
					$sql .= ' ' . $key . '="' . $value . '"';
			}			
		}

		$sql .= ' WHERE user.id = :id';
		$req = $this->dbRequest($sql, array($post->get('id')));
		$req->bindValue('id', $post->get('id'), \PDO::PARAM_INT);
		$req->execute();
	}

	public function deleteBirthDate($userId)
	{
		$sql = 'UPDATE user SET birth_date = NULL WHERE user.id = :userId';
		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
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

