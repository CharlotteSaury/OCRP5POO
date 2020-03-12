<?php

namespace Src\Constraint;

use Config\Parameter;

/**
 * Class UserValidation
 * Manage input validity for user profile form
 */
class UserValidation extends Validation
{
    protected $userId;

    public function check(Parameter $post)
    {
        $this->userId = $post->get('id');
        foreach ($post->all() as $key => $value) {
            $this->checkField($key, $value);
        }
        return $this->errors;
    }   

    protected function checkPseudo($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('pseudo', $value);
        }

        if ($this->constraint->minLength($name, $value, 2)) {
            return $this->constraint->minLength('pseudo', $value, 2);
        }

        if ($this->constraint->maxLength($name, $value, 25)) {
            return $this->constraint->maxLength('pseudo', $value, 25);
        }

        if ($this->constraint->pseudoExists($name, $value, $this->userId)) {
            return $this->constraint->pseudoExists($name, $value, $this->userId);
        }
    }

    protected function checkFirst_name($name, $value)
    {
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('prénom', $value, 255);
        }

        if ($this->constraint->containsOnlyLetter($name, $value)) {
            return $this->constraint->containsOnlyLetter('prénom', $value);
        }
    }

    protected function checkLast_name($name, $value)
    {
        if ($this->constraint->maxLength($name, $value, 255)) {
            return $this->constraint->maxLength('nom', $value, 255);
        }

        if ($this->constraint->containsOnlyLetter($name, $value)) {
            return $this->constraint->containsOnlyLetter('nom', $value);
        }
    }

    protected function checkHome($name, $value)
    {
        if ($this->constraint->maxLength($name, $value, 50)) {
            return $this->constraint->maxLength('home', $value, 50);
        }
    }

    protected function checkUser_about($name, $value)
    {
        if ($this->constraint->maxLength($name, $value, 1000)) {
            return $this->constraint->maxLength('"A propos de moi"', $value, 1000);
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

        if ($this->constraint->emailExists($name, $value, $this->userId)) 
            return $this->constraint->emailExists($name, $value, $this->userId);
        }

    protected function checkMobile($name, $value)
    {
        if ($value != '') {
            if ($this->constraint->isMobile($name, $value)) {
                return $this->constraint->isMobile('mobile', $value);
            }
        }
        
    }

    protected function checkWebsite($name, $value)
    {
        if ($this->constraint->maxLength($name, $value, 100)) {
            return $this->constraint->maxLength('website', $value, 100);
        }
    }

    protected function checkBirth_date($name, $value)
    {
        if ($value != '') {
            if ($this->constraint->hasBirthdateFormat($name, $value)) {
                return $this->constraint->hasBirthdateFormat('date de naissance', $value);
            }

            if ($this->constraint->isValidBirthdate($name, $value)) {
                return $this->constraint->isValidBirthdate('date de naissance', $value);
            }
        }
    }

    protected function checkUser_role_id($name, $value)
    {
        if ($this->constraint->notBlank($name, $value)) {
            return $this->constraint->notBlank('email', $value);
        }

        if (!in_array($value, ['1', '2', '3'])) {
            return 'Le role de l\'utilisateur n\'est pas valide';
        }

    }
}
