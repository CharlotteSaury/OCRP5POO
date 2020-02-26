<?php

namespace Entity;

class Content extends Entity
{
	protected $postId,
			$type,
			$content;

	const INVALID_CONTENT = 1;
	const INVALID_TYPE = 2;


  	public function isValid()
  	{
  		return !(empty($this->content) || empty($this->type));
  	}


  	// SETTERS //


  	public function setPostId($postId)
  	{
  		$this->postId = (int) $postId;
  	}

  	public function setType($type)
 	{
  		if (!is_string($type) || empty($type))
  		{
  			$this->erreurs[] = self::INVALID_TYPE;
  		}

  		$this->type = $type;
  	}

  	public function setContent($content)
  	{
  		if (!is_string($content) || empty($content))
  		{
  			$this->erreurs[] = self::INVALID_CONTENT;
  		}

  		$this->content = $content;
  	}

  	
  	// GETTERS //

  	public function postId()
  	{
  		return this->postId;
  	}

  	public function type()
  	{
  		return this->type;
  	}

  	public function content()
  	{
  		return this->content;
  	}
}