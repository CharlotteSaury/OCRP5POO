<?php

namespace Src\Constraint;

use Config\Parameter;

/**
 * Class CategoryValidation
 * Manage input validity for category form
 */
class CategoryValidation extends Validation
{
    protected function checkCategoryName($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('catégorie', $value);
        }

        if ($this->constraint->maxLength($name, $value, 25)) {
            return $this->constraint->maxLength('catégorie', $value, 25);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('catégorie', $value, 2);
        }
    }

}