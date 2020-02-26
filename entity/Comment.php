<?php

namespace entity;

require_once 'entity/Entity.php';
use entity\Entity;

class Comment extends Entity
{
	protected $postId,
	$userId,
	$content,
	$commentDate,
	$status,
	$userAvatar,
	$userPseudo,
	$postTitle;


  	// SETTERS //

	public function setPostId($postId)
	{
		$this->postId = (int) $postId;
	}

	public function setUserId($userId)
	{
		$this->userId = (int) $userId;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}


	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setCommentDate(\DateTime $commentDate)
	{
		$this->commentDate = $commentDate;
	}

	public function setUserPseudo($userPseudo)
	{
		$this->userPseudo = $userPseudo;
	}

	public function setUserAvatar($userAvatar)
	{
		$this->userAvatar = $userAvatar;
	}

	public function setPostTitle($postTitle)
	{
		$this->postTitle = $postTitle;
	}



  	// GETTERS //

	public function postId()
	{
		return $this->postId;
	}

	public function userId()
	{
		return $this->userId;
	}

	public function content()
	{
		return $this->content;
	}

	public function status()
	{
		return $this->status;
	}

	public function commentDate()
	{
		return $this->commentDate;
	}

	public function userAvatar()
	{
		return $this->userAvatar;
	}

	public function userPseudo()
	{
		return $this->userPseudo;
	}

	public function postTitle()
	{
		return $this->postTitle;
	}

}