<?php

namespace Src\Model;

use Src\Config\Parameter;

/**
 * Class ContentManager
 * Manage database requests related to contact form
 */
class ContactManager extends Manager
{
	/**
	 * Get contacts number (all unread if $status provided)
	 * @return int [contact number]
	 */
	public function getContactsNb($status = null)
	{
		$sql = 'SELECT COUNT(*) AS contactsNb FROM contact_form';

		if ($status != null) {
			$sql .= ' WHERE contact_status_id = :status';
			$req = $this->dbRequest($sql, array($status));
			$req->bindValue('status', $status, \PDO::PARAM_INT);
			$req->execute();
		} else {
			$req = $this->dbRequest($sql);
		}
		
		$ContactNb = $req->fetch(\PDO::FETCH_COLUMN);
		return $ContactNb;
	}

	/**
	 * Get all contacts or single contacts informations
	 * @param  int $contactId   [optional $contactId]
	 * @param  int $status      [optional]
	 * @param  $sortingDate [optional sorting by date ASC]
	 * @return object(s) Contact
	 */
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

		if ($status != null) {
			$sql .= ' WHERE contact_status.id = ' . $status;
		}
		
		if ($contactId != null) {
			$sql .= ' WHERE contact_form.id = :contactId';

			$req = $this->dbRequest($sql, array($contactId));
			$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
			$req->execute();
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Contact');
			$contacts = $req->fetch();
		
		} else {

			if ($sortingDate != null) {
				$sql .= ' ORDER BY contact_form.date_message ASC';
			
			} else {
				$sql .= ' ORDER BY contact_form.date_message DESC';
			}

			$req = $this->dbRequest($sql);
			$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Contact');
			$contacts = $req->fetchAll();
		}
		return $contacts;
	}

	/**
	 * Add new contact form in database
	 * @param Parameter $post [user name, email, subject, content]
	 * @return  int $contactId
	 */
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

	/**
	 * Upsate contact status (unread, read, sent) in database
	 * @param  int $contactId
	 * @param  int $status
	 * @return void
	 */
	public function updateStatus($contactId, $status)
	{
		$sql = 'UPDATE contact_form SET contact_status_id = :status WHERE contact_form.id = :contactId';
		$req = $this->dbRequest($sql, array($status, $contactId));
		$req->bindValue('status', $status, \PDO::PARAM_INT);
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Delete contact in database
	 * @param  int $contactId
	 * @return void
	 */
	public function deleteContact($contactId)
	{
		$sql = 'DELETE FROM contact_form WHERE contact_form.id = :contactId';

		$req = $this->dbRequest($sql, array($contactId));
		$req->bindValue('contactId', $contactId, \PDO::PARAM_INT);
		$req->execute();
	}

	/**
	 * Add new answer in database
	 * @param Parameter $post [subject, content]
	 * @return  void
	 */
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

	/**
	 * Get answer related to contact form from database
	 * @param  int $contactId
	 * @return object Answer 
	 */
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

		$req->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Src\Entity\Answer');
		
		$answer = $req->fetch();
		return $answer;
	}
	
}

