<?php

namespace src\entity;

use src\entity\Entity;

class Answer extends Entity
{
	private $subject,
	$content,
	$dateAnswer,
	$contactId;


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

	public function contactId()
	{
		return $this->contactId;
	}
	

	public function content()
	{
		return $this->content;
	}

	public function subject()
	{
		return $this->subject;
	}

	public function dateAnswer()
	{
		return $this->dateAnswer;
	}

}