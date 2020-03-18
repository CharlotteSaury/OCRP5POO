<?php

namespace Src\Model;

use Src\Config\Parameter;

/**
 * Class CommentManager
 * Manage database requests related to comments
 */
class CommentManager extends Manager
{
	/**
	 * Get comments according to status
	 * @param  int $commentsNb  [optional number of comments to provide]
	 * @param  int $status      [optional comment status (approved (1)/unapproved (0))]
	 * @param  string $sortingDate [optional sorting by date ASC]
	 * @return objects Comment
	 */
	public function getComments($commentsNb = null, $status = null, $sortingDate = null)
	{
		$sql = 'SELECT comment.id AS id, 
			comment.content AS content, 
			DATE_FORMAT(comment.comment_date,\'%d-%m-%Y à %Hh%i\') AS commentDate, 
			comment.status AS status,
			user.pseudo AS userPseudo,
			post.title AS postTitle,
			post.id AS postId
			FROM comment 
			JOIN user on comment.user_id = user.id
			JOIN post on comment.post_id = post.id';

		if ($status != null) {
			$sql.= ' WHERE comment.status = ' . $status;
		}

		if ($sortingDate != null) {
			$sql.= ' ORDER BY comment.comment_date ASC';

		} else {
			$sql.= ' ORDER BY comment.comment_date DESC';
		}

		if ($commentsNb !== null) {
			$sql.= ' LIMIT ' . $commentsNb;
		}
		
		$req = $this->dbRequest($sql);
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Comment');
		$comments = $req->fetchAll();
		return $comments;
	}

	/**
	 * Get comments related to single post according to comment status
	 * @param  int $postId 
	 * @param  int $status [optional comment status (approved (1)/unapproved (0))]
	 * @return objects Comment 
	 */
	public function getPostComments($postId, $status = null)
	{
		$sql = 'SELECT post.id AS postId, 
			comment.id AS id,
			comment.content AS content, 
			DATE_FORMAT(comment.comment_date,\'%d-%m-%Y à %Hh%i\') AS commentDate, 
			comment.status AS status,
			user.id AS userId,
			user.pseudo AS userPseudo,
			user.avatar AS userAvatar
			FROM comment 
			JOIN user on comment.user_id = user.id
			JOIN post on comment.post_id = post.id';

		if ($status != null) {
			$sql .= ' WHERE comment.status=2 AND post.id= :id
			ORDER BY comment.comment_date DESC';
		
		} else {
			$sql .= ' AND post.id= :id
			ORDER BY comment.comment_date DESC';
		}

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':id', $postId, \PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Comment');
		$comments = $req->fetchAll();
		return $comments;
	}

	/**
	 * Add new comment in database
	 * @param Parameter $post   [postId, comment content]
	 * @param int  $userId
	 * @return void
	 */
	public function addComment(Parameter $post, $userId)
	{
		$sql = 'INSERT INTO comment (post_id, user_id, content, comment_date, status) 
				VALUES (:postId, :userId, :content, NOW(), 1)';

		$req = $this->dbRequest($sql, array($post->get('postId'), $userId, $post->get('content')));
		$req->bindValue('postId', $post->get('postId'), \PDO::PARAM_INT);
		$req->bindValue('userId', $userId, \PDO::PARAM_INT);
		$req->bindValue('content', $post->get('content'));
		$req->execute();
	}

	/**
	 * Get comments number according to status (approved (1)/unapproved (0))
	 * @param  int $status [optional status approved (1)/unapproved (0)]
	 * @return int commentsNb         [comments number]
	 */
	public function getCommentsNb($status = null)
	{
		$sql = 'SELECT COUNT(*) AS commentsNb FROM comment';

		if ($status != null) {
			$sql .= ' WHERE comment.status = :status';
			$req = $this->dbRequest($sql, array($status));
			$req->bindValue('status', $status, \PDO::PARAM_INT);
			$req->execute();
		
		} else {
			$req = $this->dbRequest($sql);
		}

		$commentsNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $commentsNb;
	}

	/**
	 * Update comment status in database
	 * @param  int $commentId 
	 * @return void           
	 */
	public function approveComment($commentId)
	{
		$sql = 'UPDATE comment SET status=2	WHERE comment.id = :commentId';

		$req = $this->dbRequest($sql, array($commentId));
		$req->bindValue('commentId', $commentId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Delete comment in database
	 * @param  int $commentId
	 * @return void            
	 */
	public function deleteComment($commentId)
	{
		$sql = 'DELETE FROM comment	WHERE comment.id = :commentId';

		$req = $this->dbRequest($sql, array($commentId));
		$req->bindValue('commentId', $commentId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Delete all comments from a single post
	 * @param  int $postId
	 * @return void
	 */
	public function deleteCommentsFromPost($postId)
	{
		$sql = 'DELETE FROM comment	WHERE post_id = :postId';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}
}

