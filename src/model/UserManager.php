<?php

namespace Src\Model;

use Config\Parameter;

/**
 * Class UserManager
 * Manage database requests related to users
 */
class UserManager extends Manager
{
	/**
	 * Get users from database according to user role
	 * @param  int $usersNb    [optional users number to get]
	 * @param  int $userRoleId [optional user role id (admin = 1, user = 2, super-admin = 3)]
	 * @return objects User
	 */
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

		if ($userRoleId != null) {
			$sql .= ' WHERE user.user_role_id = ' . $userRoleId;
		}

		$sql .= ' ORDER BY user.register_date DESC';

		if ($usersNb !== null) {
			$sql.= ' LIMIT ' . $usersNb;
		}

		$req = $this->dbRequest($sql);
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\User');
		$users = $req->fetchAll();
		return $users;
	}

	/**
	 * Get single user from database with userId or email
	 * @param  int $userId 
	 * @param  sring $email  
	 * @return object User
	 */
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

		if ($userId != null) {
			$sql .= ' WHERE user.id = :userId';
			$req = $this->dbRequest($sql, array($userId));
			$req->bindValue('userId', $userId, \PDO::PARAM_INT);	
		
		} else {
			$sql .= ' WHERE user.email = :email';
			$req = $this->dbRequest($sql, array($email));
			$req->bindValue('email', $email);
		}
		
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\User');
		$user = $req->fetch();
		return $user;
	}

	/**
	 * Add new user in database
	 * @param Parameter $post [pseudo, email, password]
	 * @param int $activation_code
	 * @return void
	 */
	public function addUser (Parameter $post, $activation_code)
	{
		$sql = 'INSERT INTO user 
				(pseudo, email, password, user_role_id, register_date, activation_code, avatar)
				VALUES 
				(:pseudo, :email, :pass, 2, NOW(), :activation_code, "public/images/profile.png")';

		$req = $this->dbRequest($sql, array($post->get('pseudo'), $post->get('email'), $post->get('pass'), $activation_code));
		$req->bindValue('pseudo', $post->get('pseudo'));
		$req->bindValue('email', $post->get('email'));
		$req->bindValue('pass', $post->get('pass'));
		$req->bindValue('activation_code', $activation_code);
		$req->execute();
	}

	/**
	 * Check if a pseuso exists in database and is related to a user (optional)
	 * @param  string $pseudo
	 * @param  int $userId [optional]
	 * @return int [return 1 if pseudo exists in database]
	 */
	public function pseudoExists($pseudo, $userId = null)
	{
		$sql = 'SELECT COUNT(*) AS nbUser FROM user WHERE pseudo = :pseudo';
		
		if ($userId != null) {
			$sql .= ' AND id != :userId';
			$req = $this->dbRequest($sql, array($pseudo, $userId));
			$req->bindValue('userId', $userId);
		
		} else {
			$req = $this->dbRequest($sql, array($pseudo));
		}

		$req->bindValue('pseudo', $pseudo);
		$req->execute();
		$pseudoExists = $req->fetch(\PDO::FETCH_COLUMN);
		return $pseudoExists;
	}

	/**
	 * Check if a email exists in database and is related to a user (optional)
	 * @param  string $email
	 * @param  int $userId [optional]
	 * @return int [return 1 if email exists in database]
	 */
	public function emailExists($email, $userId = null)
	{
		$sql = 'SELECT COUNT(*) AS nbUser FROM user WHERE email = :email';
		
		if ($userId != null) {
			$sql .= ' AND id != :userId';
			$req = $this->dbRequest($sql, array($email, $userId));
			$req->bindValue('userId', $userId);
		
		} else {
			$req = $this->dbRequest($sql, array($email));
		}

		$req->bindValue('email', $email);
		$req->execute();
		$emailExists = $req->fetch(\PDO::FETCH_COLUMN);
		return $emailExists;
	}

	/**
	 * Delete activation code from user database
	 * @param  string $email 
	 * @return void
	 */
	public function userActivation($email)
	{
		$sql = 'UPDATE user SET activation_code = null WHERE email = :email';

		$req = $this->dbRequest($sql, array($email));
		$req->bindValue('email', $email);
		$req->execute();
	}

	/**
	 * Insert reinitialization password code in user table 
	 * @param  string $email       
	 * @param  string $reinit_code 
	 * @return void
	 */
	public function newPassCode($email, $reinit_code)
	{
		$sql = 'UPDATE user SET reinitialization_code = :reinitialization_code
				WHERE email = :email';

		$req = $this->dbRequest($sql, array($reinit_code, $email));
		$req->bindValue('reinitialization_code', $reinit_code);
		$req->bindValue('email', $email);
		$req->execute();
	}

	/**
	 * Update user password in database and delete password reinitialization code
	 * @param  string $email 
	 * @param  string $newPass [new hashed password]
	 * @return void
	 */
	public function newUserPass($email, $newPass)
	{
		$sql = 'UPDATE user SET password = :newPass, reinitialization_code = null WHERE email = :email';
		$req = $this->dbRequest($sql, array($newPass, $email));
		$req->bindValue('newPass', $newPass);
		$req->bindValue('email', $email);
		$req->execute();
	}

	/**
	 * Get users number regarding to their role id
	 * @param  int $userRoleId [amdin = 1, user = 2, super-admin = 3]
	 * @return int [users number]
	 */
	public function getUserNb($userRoleId = null)
	{
		$sql = 'SELECT COUNT(*) AS usersNb FROM user';

		if ($userRoleId != null) {
			$sql .= ' WHERE user.user_role_id = ' . $userRoleId;
		}

		$req = $this->dbRequest($sql);
		$usersNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $usersNb;
	}

	/**
	 * Update user infos in database
	 * @param  Parameter $post [pseudo, first_name, last_name, birth_date, home, mobile, website...]
	 * @return void
	 */
	public function editUserInfos(Parameter $post)
	{
		$sql = 'UPDATE user SET';

		foreach ($post->all() as $key => $value) {

			if ($key != 'user_role_id') {

				if ($key != 'birth_date') {
					$sql .= ' ' . $key . '="' . $value . '", ';
				
				} else {

					if ($value != '') {
						$sql .= ' ' . $key . '="' . $value . '", ';
					}
				}
			
			} else {
				$sql .= ' ' . $key . '="' . $value . '"';
			}			
		}

		$sql .= ' WHERE user.id = :id';
		$req = $this->dbRequest($sql, array($post->get('id')));
		$req->bindValue('id', $post->get('id'), \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Delete birthdate user in database
	 * @param  int $userId 
	 * @return void
	 */
	public function deleteBirthDate($userId)
	{
		$sql = 'UPDATE user SET birth_date = NULL WHERE user.id = :userId';
		
		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Update user profile picture
	 * @param  int $userId    
	 * @param  string $avatarUrl 
	 * @return void
	 */
	public function updateProfilePicture($userId, $avatarUrl)
	{
		$sql = 'UPDATE user SET avatar = :avatarUrl WHERE id = :userId';

		$req = $this->dbRequest($sql, array($avatarUrl, $userId));
		$req->bindValue('avatarUrl', $avatarUrl);
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
	}
	
}

