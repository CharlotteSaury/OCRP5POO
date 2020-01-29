<?php

namespace view;

class View 
{
	private $file;
	private $title;

	public function __construct($action)
	{
		// Determination du nom du fichier à partir de l'action
		$this->file = "view/" . $action . ".php";
	}

	//Génère et affiche la vue

	public function generate($vars)
	{
		//Génération de la partir spécifique de la vue
		$content = $this->generateFile($this->file, $vars);
		//Génération du template commun utilisant la partie spécifique 
		$view = $this->generateFile("view/template.php", array('title' => $this->title, 'content' => $content));
		//renvoie la vue au navigateur
		echo $vue;
	}

	//Génère un fichier vue et renvoie le résultats produit

	private function generateFile($file, $vars)
	{
		if (file_exists($file))
		{
			//rend les éléments du tableau $vars accessibles dans la vue
			extract($vars);

			ob_start();
			// inclut le fichier vue
			require $file;

			return ob_get_clean;
		}
		else
		{
			throw new Exception("Fichier" . $fichier. "introuvable");
		}
	}


}