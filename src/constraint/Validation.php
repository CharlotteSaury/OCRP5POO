<?php

namespace Src\Constraint;

use Src\Constraint\Constraint;
use Config\Parameter;


/**
 * Class Validation
 * Manage validation of form inputs according to form name
 */
class Validation
{
    protected $errors = [];
    protected $constraint;
    protected $userId;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }
 
    /**
     * Get all form input values ($Parameter $post) and analyzes each value regarding its name and constraints
     * @param  Parameter $post [mixed informations regarding form]
     * @return array $errors [array containing optional error message associated with field name]
     */
    public function check(Parameter $post)
    {
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    /**
     * Check validity of each field value with according to specific constraints 
     * @param  string $name  [fiels name]
     * @param  mixed $value [provided value]
     * @return void        [Call method addError]
     */
    public function checkField($name, $value)
    {
        $method = 'check' . ucfirst($name);
        if (method_exists($this, $method)) {
            $error = $this->$method($name, $value);
            $this->addError($name, $error);
        }
    }


    /**
     * Create error table with error messages linked to analyzed form fields
     * @param string $name  [form field name]
     * @param string $error [error message]
     */
    public function addError($name, $error) {
        if ($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    /**
     * Redirect to appropriate validation class according to $name
     * @param  object $data [Parameter $post]
     * @param  string $name [name of form to validate]
     * @return array [potential errors]
     */
    public function validate($data, $name)
    {
        if ($name === 'Comment') {
            $validation = new CommentValidation();
        
        } elseif ($name === 'Contact') {
            $validation = new ContactValidation();
        
        } elseif ($name === 'Answer') {
            $validation = new AnswerValidation();
        
        } elseif ($name === 'Inscription') {
            $validation = new InscriptionValidation();
        
        } elseif ($name === 'NewPass') {
            $validation = new NewPassValidation();
        
        } elseif ($name === 'Post') {
            $validation = new PostValidation();
        
        } elseif ($name === 'Content') {
            $validation = new ContentValidation();
        
        } elseif ($name === 'Category') {
            $validation = new CategoryValidation();
        
        } elseif ($name === 'User') {
            $validation = new UserValidation();
        }
        $errors = $validation->check($data);
        return $errors;
    }

    /**
     * Check in database if a entity exists with its id
     * @param  string $name  [postId, commentId, contentId, contactId, userId...]
     * @param  int $value [value of id]
     * @return array        [error descriptions]
     */
    public function exists($name, $value)
    {
        $exists = new ExistsValidation();
    	$errors = $exists->checkExists($name, $value);
        return $errors;
    }
}