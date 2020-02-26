<?php

namespace entity;

require_once 'Entity.php';
use entity\Entity;

class Post extends Entity

{
	protected $title,
			$chapo,
			$status,
			$dateCreation,
			$dateUpdate,
			$pseudo,
			$avatar,
			$mainImage,
			$approvedCommentsNb,
			$commentsNb,
			$categories;

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

	public function title()
	{
		return $this->title;
	}

	public function chapo()
	{
		return $this->chapo;
	}

	public function status()
	{
		return $this->status;
	}

	public function dateCreation()
	{
		return $this->dateCreation;
	}

	public function dateUpdate()
	{
		return $this->dateUpdate;
	}

	public function pseudo()
	{
		return $this->pseudo;
	}

	public function avatar()
	{
		return $this->avatar;
	}

	public function mainImage()
	{
		return $this->mainImage;
	}

	public function commentsNb()
	{
		return $this->commentsNb;
	}

	public function approvedCommentsNb()
	{
		return $this->approvedCommentsNb;
	}

	public function categories()
	{
		return $this->categories;
	}
}