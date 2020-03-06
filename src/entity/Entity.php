<?php

namespace src\entity;

use src\entity\Entity;

abstract class Entity
{
	protected $id;

	public function __construct(array $data = [])
	{
		if (!empty($data)) {
			$this->hydrate($data);
		}
	}

	public function hydrate($data)
	{
		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst($key);

			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = (int) $id;
	}
}