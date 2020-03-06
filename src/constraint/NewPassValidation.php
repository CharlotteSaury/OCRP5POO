<?php

namespace src\constraint;
use config\Parameter;


class NewPassValidation extends Validation
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
        if ($name === 'pass1') {
            $error = $this->checkPass1($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error) {
        if ($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkPass1($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('mot de passe', $value);
        }

        if ($this->constraint->minLength($name, $value, 6)) {
            return $this->constraint->minLength('mot de passe', $value, 6);
        }

        if ($this->constraint->maxLength($name, $value, 20)) {
            return $this->constraint->maxLength('mot de passe', $value, 20);
        }

        if ($this->constraint->containsLetter($name, $value)) {
            return $this->constraint->containsLetter('mot de passe', $value);
        }

        if ($this->constraint->containsNumeric($name, $value)) {
            return $this->constraint->containsNumeric('mot de passe', $value);
        }
    }


}
