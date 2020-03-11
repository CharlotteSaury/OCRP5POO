<?php

namespace Src\Entity;

use Src\Entity\Entity;

/**
 * Class Post
 * 	Describes Post entity 
 */
class Post extends Entity

{
	private $title;
	private $chapo;
	private $status;
	private $dateCreation;
	private $dateUpdate;
	private $pseudo;
	private $avatar;
	private $mainImage;
	private $approvedCommentsNb;
	private $commentsNb;
	private $categories;

  	// SETTERS //

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setChapo($chapo)
	{
		$this->chapo = $chapo;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setDateCreation(\DateTime $dateCreation)
	{
		$this->dateCreation = $dateCreation;
	}

	public function setDateUpdate(\DateTime $dateUpdate)
	{
		$this->dateUpdate = $dateUpdate;
	}

	public function setPseudo($pseudo)
	{
		$this->pseudo = $pseudo;
	}

	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
	}

	public function setMainImage($mainImage)
	{
		$this->mainImage = $mainImage;
	}

	public function setApprovedCommentsNb($approvedCommentsNb)
	{
		$this->approvedCommentsNb = $approvedCommentsNb;
	}

	public function setCommentsNb($commentsNb)
	{
		$this->commentsNb = $commentsNb;
	}

	public function setCategories($categories)
	{
		$this->categories = $categories;
	}


  	// GETTERS //

	public function getTitle()
	{
		return $this->title;
	}

	public function getChapo()
	{
		return $this->chapo;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getDateCreation()
	{
		return $this->dateCreation;
	}

	public function getDateUpdate()
	{
		return $this->dateUpdate;
	}

	public function getPseudo()
	{
		return $this->pseudo;
	}

	public function getAvatar()
	{
		return $this->avatar;
	}

	public function getMainImage()
	{
		return $this->mainImage;
	}

	public function getCommentsNb()
	{
		return $this->commentsNb;
	}

	public function getApprovedCommentsNb()
	{
		return $this->approvedCommentsNb;
	}

	public function getCategories()
	{
		return $this->categories;
	}
}