<?php

namespace Src\Constraint;

use Config\Parameter;

/**
 * Class PostValidation
 * Manage input validity for post infos form
 */
class PostValidation extends Validation
{
    protected function checkTitle($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('titre', $value);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('titre', $value, 2);
        }

        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('titre', $value, 255);
        }
    }

    protected function checkChapo($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('chapo', $value);
        }

        if ($this->constraint->minLength($name, $value, 10)) {
            return $this->constraint->minLength('chapo', $value, 10);
        }

        if ($this->constraint->maxLength($name, $value, 350)) {
            return $this->constraint->maxLength('chapo', $value, 350);
        }
    }

}
