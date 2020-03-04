<?php

namespace src\constraint;
use config\Parameter;


class CategoryValidation extends Validation
{
    private $errors = [];
    private $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) 
        {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if($name === 'categoryName')
        {
            $error = $this->checkCategory($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error) 
    {
        if($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkCategory($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('catégorie', $value);
        }

        if($this->constraint->maxLength($name, $value, 25)) 
        {
            return $this->constraint->maxLength('catégorie', $value, 25);
        }

        if($this->constraint->minLength($name, $value, 2)) 
        {
            return $this->constraint->minLength('catégorie', $value, 2);
        }
    }

}