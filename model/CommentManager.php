<?php

namespace model;

require_once("model/Manager.php");

class CommentManager extends Manager
{
	public function getPostComments($postId)
	{
		$sql = ('SELECT post.id AS postID, 
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

}

