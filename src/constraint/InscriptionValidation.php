<?php

namespace src\constraint;

use config\Parameter;

/**
 * Class InscriptionValidation
 * Manage input validity for inscription form
 */
class InscriptionValidation extends Validation
{
    protected function checkPseudo($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('pseudo', $value);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('pseudo', $value, 2);
        }

        if ($this->constraint->maxLength($name, $value, 25)) {
            return $this->constraint->maxLength('pseudo', $value, 25);
        }

        if ($this->constraint->pseudoExists($name, $value)){
            return $this->constraint->pseudoExists($name, $value);
        }

        if ($this->constraint->containsLetter($name, $value)) {
            return $this->constraint->containsLetter('pseudo', $value);
        }
    }

    protected function checkEmail($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('email', $value);
        }

        if ($this->constraint->isEmail($name, $value)) {
            return $this->constraint->isEmail('email', $value);
        }

        if ($this->constraint->emailExists($name, $value)) {
            return $this->constraint->emailExists($name, $value);
        }
    }

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
