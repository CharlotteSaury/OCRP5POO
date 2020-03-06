<?php

namespace src\entity;

use src\entity\Entity;

class Content extends Entity

{
	private $postId;
	private $contentTypeId;
	private $content;

  	// SETTERS //

	public function setPostId($postId)
	{
		$this->postId = (int) $postId;
	}

	public function setContentTypeId($contentTypeId)
	{
		$this->contentTypeId = $contentTypeId;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}
	
  	// GETTERS //

	public function getPostId()
	{
		return $this->postId;
	}

	public function getContentTypeId()
	{
		return $this->contentTypeId;
	}

	public function getContent()
	{
		return $this->content;
	}
}