<?php

namespace src\entity;

require_once 'src/entity/Entity.php';
use src\entity\Entity;

abstract class Entity
{
	protected $id;

	public function __construct(array $data = [])
	{
		if (!empty($data))
		{
			$this->hydrate($data);
		}
	}

	public function hydrate($data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.ucfirst($key);

			if (is_callable([$this, $method]))
			{
				$this->$method($value);
			}
		}
	}

	public function id()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = (int) $id;
	}
}