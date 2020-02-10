<?php

namespace model;

require_once("model/Manager.php");

class CommentManager extends Manager
{
	public function getPostComments($postId, $status = null)
	{
		$sql = 'SELECT post.id AS postId, 
			comment.id AS commentId,
			comment.content AS commentContent, 
			DATE_FORMAT(comment.comment_date,\'%d-%m-%Y à %Hh%i\') AS commentDate, 
			comment.status AS status,
			user.first_name as first_name, 
			user.last_name AS last_name, 
			user.avatar AS avatar
			FROM comment 
			JOIN user on comment.user_id = user.id
			JOIN post on comment.post_id = post.id';

		if ($status != null)
		{
			$sql .= ' WHERE comment.status=1 AND post.id= :id
			ORDER BY comment.comment_date DESC';
		}
		else
		{
			$sql .= ' AND post.id= :id
			ORDER BY comment.comment_date DESC';
		}

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

	public function getTotalCommentsNb()
	{
		$sql = ('SELECT COUNT(*) AS commentsNb FROM comment');
		$req = $this->dbRequest($sql);

		$totalCommentsNb = $req->fetch();
		$commentsNb = $totalCommentsNb['commentsNb'];
		return $commentsNb;
	}

	public function getApprovedCommentsNb()
	{
		$sql = ('SELECT COUNT(*) AS commentsNb FROM comment WHERE comment.status=1');
		$req = $this->dbRequest($sql);

		$approvedCommentsNb = $req->fetch();
		$commentsNb = $approvedCommentsNb['commentsNb'];
		return $commentsNb;
	}

	public function getComments($commentsNb = null)
	{
		$sql = 'SELECT comment.id AS commentId, 
			comment.content AS content, 
			DATE_FORMAT(comment.comment_date,\'%d-%m-%Y à %Hh%i\') AS commentDate, 
			comment.status AS status,
			user.first_name as first_name, 
			user.last_name AS last_name,
			post.title AS postTitle,
			post.id AS postId
			FROM comment 
			JOIN user on comment.user_id = user.id
			JOIN post on comment.post_id = post.id
			ORDER BY comment.id DESC';

		if ($commentsNb !== null)
		{
			$sql.= ' LIMIT ' . $commentsNb;
		}
		
		$req = $this->dbRequest($sql);
		return $req;
	}

	public function getPostCommentsNb($postId)
	{
		$sql = ('SELECT COUNT(*) AS commentsNb FROM comment WHERE comment.post_id = :postId');

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();

		return $req;
	}

	public function approveComment($commentId)
	{
		$sql = 'UPDATE comment SET status=1	WHERE comment.id = :commentId';

		$req = $this->dbRequest($sql, array($commentId));
		$req->bindValue('commentId', $commentId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function deleteComment($commentId)
	{
		$sql = 'DELETE FROM comment	WHERE comment.id = :commentId';

		$req = $this->dbRequest($sql, array($commentId));
		$req->bindValue('commentId', $commentId, \PDO::PARAM_INT);
		$req->execute();
	}
}

