<?php

namespace src\constraint;
use config\Parameter;


class ExistsValidation extends Validation
{
    private $errors = [];
    private $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check($name, $value)
    {
        $error = $this->checkId($name, $value);
        $this->addError($name, $error);
        return $this->errors;
    }

    private function addError($name, $error) 
    {
        if ($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkId($name, $value)
    {
        if ($this->constraint->exists($name, $value))
        {
            return $this->constraint->exists($name, $value);
        }
    }

}
