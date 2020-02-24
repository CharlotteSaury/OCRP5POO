<?php

namespace view;

use Exception;

class View

{
	private $_file,
			$_title,
			$_header,
			$_content,
			$_contentTitle;

	public function render($app, $template, $data = [])
	{
		$this->_file = 'view/' . $app . '/' . $template . '.php';
		$this->renderFile($this->_file, $data);
		$view = $this->renderFile('view/' . $app . '/' . $app . '_template.php', $data);
		echo $view;
	}

	private function renderFile($_file, $data)
	{
		if (file_exists($_file))
		{
			extract($data);
            ob_start();
            require $_file;
            return ob_get_clean();
		}
		throw new Exception ('Vue non trouvée');
		
	}
}