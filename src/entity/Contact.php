<?php

namespace Src\Entity;

use Src\Entity\Entity;
/**
 * Class Contact
 * 	Describes Contact entity 
 */
class Contact extends Entity
{
	private $name;
	private $email;
	private $subject;
	private $content;
	private $dateMessage;
	private $statusId;


  	// SETTERS //

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function setDateMessage(\DateTime $dateMessage)
	{
		$this->dateMessage = $dateMessage;
	}

	public function setStatusId($statusId)
	{
		$this->statusId = (int) $statusId;
	}



  	// GETTERS //

	public function getName()
	{
		return $this->name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getDateMessage()
	{
		return $this->dateMessage;
	}

	public function getStatusId()
	{
		return $this->statusId;
	}
}