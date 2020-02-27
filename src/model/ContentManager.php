<?php

namespace src\model;

class ContentManager extends Manager
{
	public function getPostContents($postId)
	{
		$sql = 'SELECT post.id AS postId,
			content.content AS content,
			content.id AS id, 
			content_type.id AS contentTypeId 
			FROM post 
			JOIN content ON post.id = content.post_id 
			JOIN content_type ON content_type.id=content.content_type_id 
			WHERE post.id= :postId 
			ORDER BY content.id ASC';
		
		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue(':postId', $postId, \PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Content');
		
		$contents = $req->fetchAll();
		return $contents;
	}

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

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Content');
		
		$content = $req->fetch();
		return $content;
	}

	public function deleteContent($contentId)
	{
		$sql = 'DELETE FROM content WHERE content.id = :contentId';

		$req = $this->dbRequest($sql, array($contentId));
		$req->bindValue('contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function addParagraph($postId)
	{
		$sql = 'INSERT INTO content (post_id, content_type_id, content) 
				VALUES (:postId, 2, "")';

		$req = $this->dbRequest($sql, array($postId));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function editParagraph($newParagraphs)
	{
		foreach ($newParagraphs AS $key => $value)
		{
			$sql = 'UPDATE content SET content.content = :content 
				WHERE content.id = :contentId';

			$req = $this->dbRequest($sql, array($value, $key));
			$req->bindValue('content', $value);
			$req->bindValue('contentId', $key, \PDO::PARAM_INT);
			$req->execute();
		}
	}

	public function addPicture($postId, $content)
	{
		$sql = 'INSERT INTO content (post_id, content_type_id, content) 
				VALUES (:postId, 1, :content)';

		$req = $this->dbRequest($sql, array($postId, $content));
		$req->bindValue('postId', $postId, \PDO::PARAM_INT);
		$req->bindValue('content', $content);
		$req->execute();
	}

	public function updatePostPicture($contentId, $url)
	{
		$sql = 'UPDATE content SET content.content = :url WHERE content.id = :contentId';

		$req = $this->dbRequest($sql, array($url, $contentId));
		$req->bindValue('url', $url);
		$req->bindValue('contentId', $contentId, \PDO::PARAM_INT);
		$req->execute();
	}
}

