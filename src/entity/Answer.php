<?php

namespace src\entity;

use src\entity\Entity;

class Answer extends Entity
{
	private $subject;
	private $content;
	private $dateAnswer;
	private $contactId;


  	// SETTERS //

	public function setContactId($contactId)
	{
		$this->contactId = (int) $contactId;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function setDateAnswer(\DateTime $dateAnswer)
	{
		$this->dateAnswer = $dateAnswer;
	}


  	// GETTERS //

	public function getContactId()
	{
		return $this->contactId;
	}
	

	public function getContent()
	{
		return $this->content;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getDateAnswer()
	{
		return $this->dateAnswer;
	}

}