<?php

namespace src\model;
use config\Parameter;

class ContactManager extends Manager
{
	public function getTotalContactsNb()
	{
		$sql = 'SELECT COUNT(*) AS contactsNb FROM contact_form';
		$req = $this->dbRequest($sql);

		$totalContactNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $totalContactNb;
	}

	public function getUnreadContactsNb()
	{
		$sql = 'SELECT COUNT(*) AS contactsNb FROM contact_form
				WHERE contact_status_id = 1';
		$req = $this->dbRequest($sql);

		$unreadContactsNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $unreadContactsNb;
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
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Contact');
		
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
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Contact');
		
			$contacts = $req->fetchAll();
		}

		return $contacts;
	}

	public function addNewContact(Parameter $post)
	{
		$sql = 'INSERT INTO contact_form 
			(name, email, subject, content, date_message, contact_status_id) 
			VALUES (:name, :email, :subject, :content, NOW(), 1)';

		$req = $this->dbRequest($sql, array($post->get('name'), $post->get('email'), $post->get('subject'), $post->get('content')));
		$req->bindValue('name', $post->get('name'));
		$req->bindValue('email', $post->get('email'));
		$req->bindValue('subject', $post->get('subject'));
		$req->bindValue('content', $post->get('content'));
		$req->execute();

		$sql = 'SELECT id AS contactId FROM contact_form ORDER BY id DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$contactId = $req->fetch(\PDO::FETCH_COLUMN);
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

	public function addAnswer(Parameter $post)
	{
		$sql = 'INSERT INTO answer 
			(subject, content, date_answer) 
			VALUES (:subject, :content, NOW())';

		$req = $this->dbRequest($sql, array($post->get('answerSubject'), $post->get('answerContent')));
		$req->bindValue('subject', $post->get('answerSubject'));
		$req->bindValue('content', $post->get('answerContent'));
		$req->execute();

		$sql = 'SELECT id AS answerId FROM answer ORDER BY id DESC LIMIT 1';
		$req = $this->dbRequest($sql);
		$answerId = $req->fetch(\PDO::FETCH_COLUMN);

		$sql = 'INSERT INTO contact_answer (contact_id, answer_id)
				VALUE (:contact_id, :answer_id)';
		$req = $this->dbRequest($sql, array($post->get('contactId'), $answerId));
		$req->bindValue('contact_id', $post->get('contactId'), \PDO::PARAM_INT);
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

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\src\entity\Answer');
		
		$answer = $req->fetch();
		return $answer;
	}
	
}

