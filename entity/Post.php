<?php

namespace entity;

class Post

{
	protected $title,
	$chapo,
	$status,
	$dateCreation,
	$dateUpdate,
	$author;

	const INVALID_AUTHOR = 1;
	const INVALID_TITLE = 2;
	const INVALID_CHAPO = 3;
	

  	// SETTERS //


	public function setAuthor($author)
	{
		if (!is_string($auteur) || empty($auteur))
		{
			$this->erreurs[] = self::INVALID_AUTHOR;
		}

		$this->author = $author;
	}

	public function setTitle($title)
	{
		if (!is_string($title) || empty($title))
		{
			$this->erreurs[] = self::INVALID_TITLE;
		}

		$this->title = $title;
	}

	public function setChapo($chapo)
	{
		if (!is_string($chapo) || empty($chapo))
		{
			$this->erreurs[] = self::INVALID_CHAPO;
		}

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


  	// GETTERS //

	public function author()
	{
		return this->author;
	}

	public function title()
	{
		return this->title;
	}

	public function chapo()
	{
		return this->chapo;
	}

	public function status()
	{
		return this->status;
	}

	public function dateCreation()
	{
		return this->dateCreation;
	}

	public function dateUpdate()
	{
		return this->dateUpdate;
	}
}