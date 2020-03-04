<?php

namespace src\constraint;

class Validation
{
    public function validate($data, $name)
    {
        if($name === 'Comment') 
        {
            $commentValidation = new CommentValidation();
            $errors = $commentValidation->check($data);
            return $errors;
        }
        elseif($name === 'Contact') 
        {
            $contactValidation = new ContactValidation();
            $errors = $contactValidation->check($data);
            return $errors;
        }
        elseif($name === 'Answer') 
        {
            $answerValidation = new AnswerValidation();
            $errors = $answerValidation->check($data);
            return $errors;
        }
        elseif($name === 'Inscription') 
        {
            $inscriptionValidation = new InscriptionValidation();
            $errors = $inscriptionValidation->check($data);
            return $errors;
        }
        elseif($name === 'NewPass') 
        {
            $newPassValidation = new NewPassValidation();
            $errors = $newPassValidation->check($data);
            return $errors;
        }
        elseif($name === 'Post') 
        {
            $postValidation = new PostValidation();
            $errors = $postValidation->check($data);
            return $errors;
        }
        elseif($name === 'Content') 
        {
            $contentValidation = new ContentValidation();
            $errors = $contentValidation->check($data);
            return $errors;
        }
        elseif($name === 'Category') 
        {
            $categoryValidation = new CategoryValidation();
            $errors = $categoryValidation->check($data);
            return $errors;
        }
        elseif($name === 'User') 
        {
            $userValidation = new UserValidation();
            $errors = $userValidation->check($data);
            return $errors;
        }
    }

    public function exists($name, $value)
    {
        $exists = new ExistsValidation();
    	$errors = $exists->check($name, $value);
        return $errors;
    }
}