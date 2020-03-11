<?php

namespace Config;

use Exception;

/**
 * Class Parameter
 * Manage $_GET and $_POST variables 
 */
class Parameter
{
	private $parameter;

	public function __construct($parameter)
	{
		$this->parameter = $parameter;
	}

	public function get($name)
	{
		if (isset($this->parameter[$name]))
		{
			return htmlspecialchars($this->parameter[$name]);
		}
	}

	public function set($name, $value)
	{
		$this->parameter[$name] = $value;
	}

	public function all()
	{
		return $this->parameter;
	}
}