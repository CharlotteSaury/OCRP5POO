<?php

namespace Src\Constraint;

use Config\Parameter;

/**
 * Class ContactValidation
 * Manage input validity for contact form
 */
class ContactValidation extends Validation
{
    protected function checkName($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('Nom', $value);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('Nom', $value, 2);
        }

        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('Nom', $value, 255);
        }

        if ($this->constraint->containsOnlyLetter($name, $value)) {
            return $this->constraint->containsOnlyLetter('Nom', $value);
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
    }

    protected function checkSubject($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('objet', $value);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('objet', $value, 2);
        }

        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('objet', $value, 255);
        }
    }

    protected function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('contenu', $value);
        }

        if ($this->constraint->minLength($name, $value, 10)) {
            return $this->constraint->minLength('contenu', $value, 10);
        }

        if ($this->constraint->maxLength($name, $value, 1000)) {
            return $this->constraint->maxLength('contenu', $value, 1000);
        }
    }
}
