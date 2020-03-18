<?php

namespace Src\Constraint;

use Src\Config\Parameter;

/**
 * Class AnswerValidation
 * Manage input validity for answer form
 */
class AnswerValidation extends Validation
{
    protected function checkAnswerSubject($name, $value)
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

    protected function checkEmail($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('email', $value);
        }

        if ($this->constraint->isEmail($name, $value)) {
            return $this->constraint->isEmail('email', $value);
        }
    }

    protected function checkAnswerContent($name, $value)
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

    protected function checkContactId($name, $value)
    {
        if ($this->constraint->exists($name, $value)) {
            return $this->constraint->exists('contactId', $value);
        }
    }
}
