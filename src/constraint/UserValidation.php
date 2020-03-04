<?php

namespace src\constraint;
use config\Parameter;


class UserValidation extends Validation
{
    private $errors = [];
    private $constraint;
    private $userId;

    public function __construct()
    {
        $this->constraint = new Constraint();
    }

    public function check(Parameter $post)
    {
        $this->userId = $post->get('id');
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }

    private function checkField($name, $value)
    {
        if($name === 'pseudo') 
        {
            $error = $this->checkPseudo($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'first_name' || $name === 'last_name') 
        {
            $error = $this->checkName($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'birth_date') 
        {
            $error = $this->checkBirthDate($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'home') 
        {
            $error = $this->checkHome($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'user_about') 
        {
            $error = $this->checkUserAbout($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'email') 
        {
            $error = $this->checkEmail($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'mobile') 
        {
            $error = $this->checkMobile($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'website') 
        {
            $error = $this->checkWebsite($name, $value);
            $this->addError($name, $error);
        }
        elseif($name === 'user_role_id') 
        {
            $error = $this->checkUserRoleId($name, $value);
            $this->addError($name, $error);
        }
    }

    private function addError($name, $error) {
        if($error) {
            $this->errors += [
                $name => $error
            ];
        }
    }

    private function checkPseudo($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('pseudo', $value);
        }

        if($this->constraint->minLength($name, $value, 2)) 
        {
            return $this->constraint->minLength('pseudo', $value, 2);
        }

        if($this->constraint->maxLength($name, $value, 25)) 
        {
            return $this->constraint->maxLength('pseudo', $value, 25);
        }

        if($this->constraint->pseudoExists($name, $value, $this->userId))
        {
            return $this->constraint->pseudoExists($name, $value, $this->userId);
        }
    }

    private function checkName($name, $value)
    {
        if($this->constraint->maxLength($name, $value, 255)) 
        {
            return $this->constraint->maxLength('nom/prénom', $value, 255);
        }

        if($this->constraint->isString($name, $value)) 
        {
            return $this->constraint->maxLength('nom/prénom', $value);
        }
    }

    private function checkHome($name, $value)
    {
        if($this->constraint->maxLength($name, $value, 50)) 
        {
            return $this->constraint->maxLength('home', $value, 50);
        }
    }

    private function checkUserAbout($name, $value)
    {
        if($this->constraint->maxLength($name, $value, 1000)) 
        {
            return $this->constraint->maxLength('"A propos de moi"', $value, 1000);
        }
    }

    private function checkEmail($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('email', $value);
        }

        if($this->constraint->isEmail($name, $value))
        {
            return $this->constraint->isEmail('email', $value);
        }

        if($this->constraint->emailExists($name, $value, $this->userId))
        {
            return $this->constraint->emailExists($name, $value, $this->userId);
        }

    }

    private function checkMobile($name, $value)
    {
        if ($value != '')
        {
            if($this->constraint->isMobile($name, $value)) 
            {
                return $this->constraint->isMobile('mobile', $value);
            }
        }
        
    }

    private function checkWebsite($name, $value)
    {
        if($this->constraint->maxLength($name, $value, 100)) 
        {
            return $this->constraint->maxLength('website', $value, 100);
        }
    }

    private function checkBirthDate($name, $value)
    {
        if ($value != '')
        {
            if($this->constraint->hasBirthdateFormat($name, $value)) 
            {
                return $this->constraint->hasBirthdateFormat('date de naissance', $value);
            }

            if($this->constraint->isValidBirthdate($name, $value)) 
            {
                return $this->constraint->isValidBirthdate('date de naissance', $value);
            }
        }
    }

    private function checkUserRoleId($name, $value)
    {
        if($this->constraint->notBlank($name, $value)) 
        {
            return $this->constraint->notBlank('email', $value);
        }

        if(!in_array($value, ['1', '2', '3'])) 
        {
            return 'Le role de l\'utilisateur n\'est pas valide';
        }

    }
}
