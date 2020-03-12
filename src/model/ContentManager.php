<?php

namespace Src\Model;

/**
 * Class ContentManager
 * Manage database requests related to post contents
 */
class ContentManager extends Manager
{
	/**
	 * Get all contents from database or contents related to single post if ^postId provided
	 * @param  int $postId [optional, to get contents from single post]
	 * @return objects Content
	 */
	public function getContents($postId = null)
	{
		$sql = 'SELECT post.id AS postId,
			content.content AS content,
			content.id AS id, 
			content_type.id AS contentTypeId 
			FROM post 
			JOIN content ON post.id = content.post_id 
			JOIN content_type ON content_type.id=content.content_type_id';
		
		if ($postId != null) {
			$sql .= ' WHERE post.id= :postId ORDER BY content.id ASC';
			$req = $this->dbRequest($sql, array($postId));
			$req->bindValue(':postId', $postId, \PDO::PARAM_INT);
			$req->execute();
		} else {
			$req = $this->dbRequest($sql);
		}

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Content');
		$contents = $req->fetchAll();
		return $contents;
	}

	/**
	 * Get single content informations from database
	 * @param  int $contentId
	 * @return object Content
	 */
	public function getContent($contentId)
	{
		$sql = 'SELECT post.id AS postId,
			content.content AS content,
			content.id AS id, 
			content_type.id AS contentTypeId 
			FROM post 
			JOIN content ON post.id = content.post_id 
			JOIN content_type ON content_type.id=content.content_type_id 
			WHERE content.id= :contentId 
			ORDER BY content.id ASC';
		
		$req = $this->dbRequest($sql, array($contentId));
		$req->bindValue(':contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Content');
		$content = $req->fetch();
		return $content;
	}

	/**
	 * Delete single content of database
	 * @param  int $contentId 
	 * @return void
	 */
	public function deleteContent($contentId)
	{
		$sql = 'DELETE FROM content WHERE content.id = :contentId';

		$req = $this->dbRequest($sql, array($contentId));
		$req->bindValue('contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Delete contents from post of database
	 * @param  int $postId 
	 * @return void
	 */
	public function deleteContentsFromPost($postId)
	{
		$sql = 'DELETE FROM content WHERE content.post_id = :postId';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Add new content in database
	 * @param int $postId 
	 * @param string $content [content is null if new paragraph, content = image url if new picture]
	 * @return void
	 */
	public function addContent($postId, $content = null)
	{
		$sql = 'INSERT INTO content (post_id, content_type_id, content)';

		if ($content != null) {
			$sql .= 'VALUES (:postId, 1, :content)';
			$req = $this->dbRequest($sql, array($postId, $content));
			$req->bindValue('postId', $postId, \PDO::PARAM_INT);
			$req->bindValue('content', $content);
		
		} else {
			$sql .= ' VALUES (:postId, 2, "")';
			$req = $this->dbRequest($sql, array($postId));
			$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		}
		$req->execute();
	}

	/**
	 * Update post content (picture, paragraph) in database
	 * @param  int $contentId
	 * @param  string $content (paragraph or picture url)
	 * @return void
	 */
	public function editContent($contentId, $content)
	{
		$sql = 'UPDATE content SET content.content = :content 
				WHERE content.id = :contentId';

		$req = $this->dbRequest($sql, array($content, $contentId));
		$req->bindValue('content', $content);
		$req->bindValue('contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
	}
}

