<?php

namespace src\entity;

require_once 'src/entity/Entity.php';
use src\entity\Entity;

class Contact extends Entity
{
	protected $name,
	$email,
	$subject,
	$content,
	$dateMessage,
	$statusId;


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

	public function name()
	{
		return $this->name;
	}

	public function email()
	{
		return $this->email;
	}

	public function content()
	{
		return $this->content;
	}

	public function subject()
	{
		return $this->subject;
	}

	public function dateMessage()
	{
		return $this->dateMessage;
	}

	public function statusId()
	{
		return $this->statusId;
	}
}