<?php

namespace src\constraint;

use config\Parameter;

/**
 * Class ContentValidation
 * Manage input validity for content form
 */
class ContentValidation extends Validation
{
    public function check(Parameter $post)
    {
        $contentId = $post->get('editContent');
        $content = $post->get($contentId);
         
        $this->checkField('content', $content);
        $this->checkField('contentId', $contentId);
        return $this->errors;
    }

    protected function checkContent($name, $value)
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

    protected function checkContentId($name, $value)
    {
        if ($this->constraint->exists($name, $value)) {
            return $this->constraint->exists('contentId', $value);
        }
    }

}
