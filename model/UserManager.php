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

	public function getUsers($usersNb = null, $usersActivity = null)
	{
		$sql = 'SELECT user.id AS userId,
				user.pseudo AS pseudo,
				user.email AS email,
				user.register_date AS register_date,
				user_role.role AS role';

		if ($usersActivity !== null)
		{
			$sql .= ', (SELECT COUNT(*) FROM post WHERE post.user_id = userId) AS postsNb, 
				(SELECT COUNT(*) FROM comment WHERE comment.user_id = userId) AS commentsNb';
		}		
		
		$sql .= ' FROM user 
				JOIN user_role ON user.user_role_id = user_role.id
				ORDER BY user.register_date DESC';

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
				user.register_date AS register_date,
				user_role.role AS role,
				user.first_name AS first_name,
				user.last_name AS last_name,
				user.mobile AS mobile,
				user.website AS website,
				user.home AS home,
				user.birth_date AS birth_date,
				user.avatar AS avatar,
				user.user_about AS about
				FROM user 
				JOIN user_role ON user.user_role_id = user_role.id
				WHERE user.id = :userId';

		$req = $this->dbRequest($sql, array($userId));
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
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

