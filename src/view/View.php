<?php

namespace src\view;

use Exception;

class View

{
	private $file;

	public function render($app, $template, $data = [])
	{
		$this->file = 'src/view/' . $app . '/' . $template . '.php';
		$this->renderFile($this->file, $data);
		$view = $this->renderFile('src/view/' . $app . '/' . $app . '_template.php', $data);
		echo $view;
	}

	private function renderFile($file, $data)
	{
		if (file_exists($file)) {
			extract($data);
            ob_start();
            require $file;
            return ob_get_clean();
		}
		throw new Exception ('Vue non trouvée');
	}
}