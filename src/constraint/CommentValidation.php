<?php

namespace src\constraint;
use config\Parameter;


class CommentValidation extends Validation
{
    private $errors = [];
    private $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) 
        {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if($name === 'content') 
        {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'postId') 
        {
            $error = $this->checkPostId($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error) 
    {
        if($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkContent($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('contenu', $value);
        }

        if($this->constraint->maxLength($name, $value, 2000)) 
        {
            return $this->constraint->maxLength('contenu', $value, 2000);
        }
    }

    private function checkPostId($name, $value)
    {
        if ($this->constraint->exists($name, $value))
        {
            return $this->constraint->exists('postId', $value);
        }
    }
}