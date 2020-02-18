<?php

namespace model;

require_once("model/Manager.php");

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
	
	public function getContacts($contactId = null)
	{
		$sql = 'SELECT contact_form.id AS contactId, 
			contact_form.name AS name, 
			contact_form.email AS email, 
			contact_form.subject AS subject, 
			contact_form.content AS content, 
			contact_status.id AS statusId,
			contact_status.name AS status,
			DATE_FORMAT(contact_form.date_message, \'%d-%m-%Y à %Hh%i \') AS date_message
			FROM contact_form 
			JOIN contact_status ON contact_form.contact_status_id = contact_status.id';

		if ($contactId != null)
		{
			$sql .= ' WHERE contact_form.id = :contactId';
			$req = $this->dbRequest($sql, array($contactId));
			$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
			$req->execute();
		}
		else
		{
			$sql .= ' ORDER BY contactId DESC';
			$req = $this->dbRequest($sql);
		}

		return $req;
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

	public function getContactStatus($contactId)
	{
		$sql = 'SELECT contact_status.id AS statusId
			FROM contact_status 
			JOIN contact_form ON contact_form.contact_status_id = contact_status.id
			WHERE contact_form.id = :contactId';

		$req = $this->dbRequest($sql, array($contactId));
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();
		$donnees = $req->fetch(\PDO::FETCH_ASSOC);
		
		return $donnees['statusId'];
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
		$sql = 'SELECT answer.subject AS subject,
				answer.content AS content,
				answer.date_answer AS date_answer 
				FROM answer 
				JOIN contact_answer ON contact_answer.answer_id = answer.id
				WHERE contact_answer.contact_id = :contactId';

		$req = $this->dbRequest($sql, array($contactId));
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();

		$donnees = $req->fetchAll(\PDO::FETCH_ASSOC);
		return $donnees;
	}
	
}

