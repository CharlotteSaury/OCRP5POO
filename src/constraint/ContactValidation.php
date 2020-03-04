<?php

namespace src\constraint;
use config\Parameter;


class ContactValidation extends Validation
{
    private $errors = [];
    private $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if($name === 'name') {
            $error = $this->checkName($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'email') {
            $error = $this->checkEmail($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'subject') {
            $error = $this->checkSubject($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'content') {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error) {
        if($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkName($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('Nom', $value);
        }

        if($this->constraint->minLength($name, $value, 2)) 
        {
            return $this->constraint->minLength('Nom', $value, 2);
        }

        if($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('Nom', $value, 255);
        }
    }

    private function checkEmail($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('email', $value);
        }

        if($this->constraint->isEmail($name, $value))
        {
            return $this->constraint->isEmail('email', $value);
        }
    }

    private function checkSubject($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('objet', $value);
        }

        if($this->constraint->minLength($name, $value, 2)) 
        {
            return $this->constraint->minLength('objet', $value, 2);
        }

        if($this->constraint->maxLength($name, $value, 255)) 
        {
            return $this->constraint->maxLength('objet', $value, 255);
        }
    }

    private function checkContent($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('contenu', $value);
        }

        if($this->constraint->minLength($name, $value, 10)) 
        {
            return $this->constraint->minLength('contenu', $value, 10);
        }

        if($this->constraint->maxLength($name, $value, 1000)) 
        {
            return $this->constraint->maxLength('contenu', $value, 1000);
        }
    }
}
