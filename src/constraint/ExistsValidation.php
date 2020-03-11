<?php

namespace src\constraint;

use config\Parameter;

/**
 * Class ExistsValidation
 * Manage validity of entities (Post, User, Contact...)
 */
class ExistsValidation extends Validation
{
    public function checkExists($name, $value)
    {
        $error = $this->checkId($name, $value);
        $this->addError($name, $error);
        return $this->errors;
    }

    private function checkId($name, $value)
    {
        if ($this->constraint->exists($name, $value))
        {
            return $this->constraint->exists($name, $value);
        }
    }

}
