<?php

namespace entity;

class Content extends Entity

{
	protected $postId,
			$contentTypeId,
			$content;

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

	public function postId()
	{
		return $this->postId;
	}

	public function contentTypeId()
	{
		return $this->contentTypeId;
	}

	public function content()
	{
		return $this->content;
	}
}