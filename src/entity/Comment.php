<?php

namespace Src\Entity;

use Src\Entity\Entity;

/**
 * Class Comment
 * 	Describes Comment entity 
 */
class Comment extends Entity
{
	private $postId;
	private $userId;
	private $content;
	private $commentDate;
	private $status;
	private $userAvatar;
	private $userPseudo;
	private $postTitle;


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

	public function getPostId()
	{
		return $this->postId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getCommentDate()
	{
		return $this->commentDate;
	}

	public function getUserAvatar()
	{
		return $this->userAvatar;
	}

	public function getUserPseudo()
	{
		return $this->userPseudo;
	}

	public function getPostTitle()
	{
		return $this->postTitle;
	}

}