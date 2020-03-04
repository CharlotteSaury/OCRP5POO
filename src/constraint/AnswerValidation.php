<?php

namespace src\constraint;
use config\Parameter;


class AnswerValidation extends Validation
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
        if($name === 'answerSubject') {
            $error = $this->checkAnswerSubject($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'email') {
            $error = $this->checkEmail($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'contactId') {
            $error = $this->checkContactId($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'answerContent') {
            $error = $this->checkAnswerContent($name, $value);
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

    private function checkAnswerSubject($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('objet', $value);
        }

        if($this->constraint->minLength($name, $value, 2)) 
        {
            return $this->constraint->minLength('objet', $value, 2);
        }

        if($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('objet', $value, 255);
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

    private function checkAnswerContent($name, $value)
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

    private function checkContactId($name, $value)
    {
        if ($this->constraint->exists($name, $value))
        {
            return $this->constraint->exists('contactId', $value);
        }
    }
}
