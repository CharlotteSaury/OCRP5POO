<?php

namespace src\controller;

use src\controller\Controller;

class HomeController extends Controller

{
	// Home page

	public function indexView($message = null) 
	{
		return $this->view->render('frontend', 'indexView', ['message' => $message]); 
	}
	
	public function legalView()
	{
		return $this->view->render('frontend', 'legalView');
	}

	public function confidentialityView()
	{
		return $this->view->render('frontend', 'confidentialityView');
	}

	public function newContactForm($name, $email, $subject, $content)
	{
		$contactId = $this->contactManager->addNewContact($name, $email, $subject, $content);
		return $contactId;
	}

	public function mailContactForm($name, $email, $subject, $content, $contactId)
	{
		// Email sent to administrator

		$mailSubject = "Blog : Nouveau message via le formulaire de contact.";
		$mailMessage = "De : " . $name . " <" . $email . ">\r\n
					Objet : " . $subject . "\r\n
					Message : " . $content . "\r\n\r\n
					Pour y répondre, cliquez sur le lien suivant : http://www.blogphp.charlottesaury.fr/index.php?action=contactView&id=" . $contactId . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail(CF_EMAIL, $mailSubject, $mailMessage);


		// Confitmation email sent to sender

		$mailSubject = "Votre message sur le blog de Charlotte SAURY";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$mailMessage = "Bonjour " . $name .  ",\r\n
					Votre message ci-dessous a bien été envoyé à l'auteur du blog. Nous vous remercions pour ce contact et tâcherons d'y répondre dans les plus brefs délais.\r\n
					----------------------\r\n
					Objet : " . $subject . "\r\n
					Message : " . $content . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail($email, $mailSubject, $mailMessage, $headers);
	}

	public function pictureUpload($namePicture)
	{
		if (isset($_FILES[$namePicture]))
		{
			if ($_FILES[$namePicture]['error'] == 0)
			{
				if ($_FILES[$namePicture]['size'] <= 2000000)
				{
					$fileInfos = pathinfo($_FILES[$namePicture]['name']);
					$extension_upload = $fileInfos['extension'];
					$allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');

					if (in_array($extension_upload, $allowed_extensions))
					{
						$uploadResults = 'public/uploads/' . microtime(true) . '_' . basename($_FILES[$namePicture]['name']);

						move_uploaded_file($_FILES[$namePicture]['tmp_name'], $uploadResults);
					}
					else
					{
						$uploadResults = 'L\'extension du fichier n\'est pas acceptée, merci de ne charger que des fichiers .jpg, .jpeg, .gif ou .png';
					}
				}
				else
				{
					$uploadResults = 'Fichier trop volumineux, merci de ne pas dépasser 2Mo.';
				}
			}
			elseif ($_FILES[$namePicture]['error'] == 1 || $_FILES[$namePicture]['error'] == 2)
			{
				$uploadResults = 'Fichier trop volumineux, merci de ne pas dépasser 2Mo.';
			}
			else
			{
				$uploadResults = 'Erreur. Le fichier n\'a pu être téléchargé.';
			}
		}
		else
		{
			$uploadResults = 'Aucun fichier téléchargé.';
		}

		return $uploadResults;
	}
	
}


