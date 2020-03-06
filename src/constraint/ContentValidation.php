<?php

namespace src\constraint;
use config\Parameter;


class ContentValidation extends Validation
{
    private $errors = [];
    private $constraint;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check(Parameter $post)
    {
        $contentId = $post->get('editContent');
        $content = $post->get($contentId);
         
        $this->checkField('content', $content);
        $this->checkField('contentId', $contentId);
        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if ($name === 'content') 
        {
            $error = $this->checkContent($name, $value);
            $this->addError($name, $error);
        
        } elseif ($name === 'contentId') {
            $error = $this->checkContentId($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error) {
        if ($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkContent($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('paragraphe', $value);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('paragraphe', $value, 2);
        }

        if ($this->constraint->maxLength($name, $value, 2000)) {
            return $this->constraint->maxLength('paragraphe', $value, 2000);
        }
    }

    private function checkContentId($name, $value)
    {
        if ($this->constraint->exists($name, $value)) {
            return $this->constraint->exists('contentId', $value);
        }
    }

}
