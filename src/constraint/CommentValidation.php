<?php

namespace Src\Constraint;

use Config\Parameter;

/**
 * Class CommentValidation
 * Manage input validity for post comment form
 */
class CommentValidation extends Validation
{

    protected function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('contenu', $value);
        }

        if ($this->constraint->maxLength($name, $value, 2000)) {
            return $this->constraint->maxLength('contenu', $value, 2000);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('contenu', $value, 2);
        }
    }

    protected function checkPostId($name, $value)
    {
        if ($this->constraint->exists($name, $value)) {
            return $this->constraint->exists('postId', $value);
        }
    }
}