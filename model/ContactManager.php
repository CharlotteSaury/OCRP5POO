<?php

namespace model;

require_once 'model/Manager.php';
require_once 'entity/Contact.php';
require_once 'entity/Answer.php';

use entity\Contact;
use entity\Answer;

class ContactManager extends Manager
{
	public function getTotalContactsNb()
	{
		$sql = 'SELECT COUNT(*) AS contactsNb FROM contact_form';
		$req = $this->dbRequest($sql);

		$totalContactNb = $req->fetch();
		$contactsNb = $totalContactNb['contactsNb'];
		return $contactsNb;
	}

	public function getUnreadContactsNb()
	{
		$sql = 'SELECT COUNT(*) AS contactsNb FROM contact_form
				WHERE contact_status_id = 1';
		$req = $this->dbRequest($sql);

		$unreadContactsNb = $req->fetch();
		$contactsNb = $unreadContactsNb['contactsNb'];
		return $contactsNb;
	}
	
	public function getContacts($contactId = null, $status = null, $sortingDate = null)
	{
		$sql = 'SELECT contact_form.id AS id, 
			contact_form.name AS name, 
			contact_form.email AS email, 
			contact_form.subject AS subject, 
			contact_form.content AS content, 
			contact_status.id AS statusId,
			DATE_FORMAT(contact_form.date_message, \'%d-%m-%Y à %Hh%i \') AS dateMessage
			FROM contact_form 
			JOIN contact_status ON contact_form.contact_status_id = contact_status.id';

		if ($status != null)
		{
			$sql .= ' WHERE contact_status.id = ' . $status;
		}
		
		if ($contactId != null)
		{
			$sql .= ' WHERE contact_form.id = :contactId';
			$req = $this->dbRequest($sql, array($contactId));
			$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
			$req->execute();
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\entity\Contact');
		
			$contacts = $req->fetch();
		}
		else
		{
			if ($sortingDate != null)
			{
				$sql .= ' ORDER BY contact_form.date_message ASC';
			}
			else
			{
				$sql .= ' ORDER BY contact_form.date_message DESC';
			}
			$req = $this->dbRequest($sql);
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\entity\Contact');
		
			$contacts = $req->fetchAll();
		}

		return $contacts;
	}

	public function addNewContact($name, $email, $subject, $content)
	{
		$sql = 'INSERT INTO contact_form 
			(name, email, subject, content, date_message, contact_status_id) 
			VALUES (:name, :email, :subject, :content, NOW(), 1)';

		$req = $this->dbRequest($sql, array($name, $email, $subject, $content));
		$req->bindValue('name', $name);
		$req->bindValue('email', $email);
		$req->bindValue('subject', $subject);
		$req->bindValue('content', $content);
		$req->execute();

		$sql = 'SELECT id AS contactId FROM contact_form ORDER BY id DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$donnees = $req->fetch(\PDO::FETCH_ASSOC);
		$contactId = $donnees['contactId'];
		return $contactId;
	}

	public function updateStatus($contactId, $status)
	{
		$sql = 'UPDATE contact_form SET contact_status_id = :status WHERE contact_form.id = :contactId';
		$req = $this->dbRequest($sql, array($status, $contactId));
		$req->bindValue('status', $status, \PDO::PARAM_INT);
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function deleteContact($contactId)
	{
		$sql = 'DELETE FROM contact_form WHERE contact_form.id = :contactId';

		$req = $this->dbRequest($sql, array($contactId));
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function addAnswer($contactId, $answerSubject, $answerContent)
	{
		$sql = 'INSERT INTO answer 
			(subject, content, date_answer) 
			VALUES (:subject, :content, NOW())';

		$req = $this->dbRequest($sql, array($answerSubject, $answerContent));
		$req->bindValue('subject', $answerSubject);
		$req->bindValue('content', $answerContent);
		$req->execute();

		$sql = 'SELECT id AS answerId FROM answer ORDER BY id DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$donnees = $req->fetch(\PDO::FETCH_ASSOC);
		$answerId = $donnees['answerId'];

		$sql = 'INSERT INTO contact_answer (contact_id, answer_id)
				VALUE (:contact_id, :answer_id)';
		$req = $this->dbRequest($sql, array($contactId, $answerId));
		$req->bindValue('contact_id', $contactId, \PDO::PARAM_INT);
		$req->bindValue('answer_id', $answerId, \PDO::PARAM_INT);
		$req->execute();
	}

	public function getAnswer($contactId)
	{
		$sql = 'SELECT answer.id AS id,
				answer.subject AS subject,
				answer.content AS content,
				answer.date_answer AS dateAnswer,
				contact_answer.contact_id AS contactId
				FROM answer 
				JOIN contact_answer ON contact_answer.answer_id = answer.id
				WHERE contact_answer.contact_id = :contactId';

		$req = $this->dbRequest($sql, array($contactId));
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\entity\Answer');
		
		$answer = $req->fetch();
		return $answer;
	}
	
}

