<?php

namespace Src\Constraint;

use Config\Parameter;

/**
 * Class NewPassValidation
 * Manage input validity for new pass form
 */
class NewPassValidation extends Validation
{
    protected function checkPass1($name, $value)
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
