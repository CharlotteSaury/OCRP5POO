<?php

namespace model;

require_once("model/Manager.php");

class CommentManager extends Manager
{
	public function getPostComments($postId)
	{
		$sql = ('SELECT post.id AS postId, 
			comment.content AS commentContent, 
			DATE_FORMAT(comment.comment_date,\'%d-%m-%Y à %Hh%i\') AS commentDate, 
			user.first_name as first_name, 
			user.last_name AS last_name, 
			user.avatar AS avatar
			FROM comment 
			JOIN user on comment.user_id = user.id
			JOIN post on comment.post_id = post.id
			WHERE comment.status=1 AND post.id= :id
			ORDER BY commentDate DESC');

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		return $req;
	}

	public function addComment($postId, $email, $content)
	{
		// Recherche de l'id de l'utilisateur s'il existe déjà en bdd
		$userIdSql = 'SELECT user.id AS userId FROM user
					WHERE user.email = :email';
		$req = $this->dbRequest($userIdSql, array($email));
		$req->bindValue('email', $email);
		$req->execute();

		$donnees = $req->fetch();

		if (empty($donnees))
		{
			$req = $this->dbRequest('INSERT INTO user (email, register_date) VALUES (:email, NOW())', array($email));
			$req->bindValue('email', $email);
			$req->execute();

			$req = $this->dbRequest($userIdSql, array($email));
			$req->bindValue('email', $email);
			$req->execute();

			$donnees = $req->fetch();
		}

		$userId = $donnees['userId'];
		

		$sql = 'INSERT INTO comment (post_id, user_id, content, comment_date) 
				VALUES (:postId, :userId, :content, NOW())';
		$req = $this->dbRequest($sql, array($postId, $userId, $content));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->bindValue('userId', $userId);
		$req->bindValue('content', $content);
		$req->execute();
	}
}

