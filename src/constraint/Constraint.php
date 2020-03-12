<?php

namespace Src\Constraint;

use Src\Model\PostManager;
use Src\Model\CommentManager;
use Src\Model\ContactManager;
use Src\Model\UserManager;
use Src\Model\ContentManager;

/**
 * Class Constraint
 * Manage validity constraints of form inputs
 */
class Constraint
{
    private $postManager;
    private $commentManager;
    private $userManager;
    private $contactManager;
    private $contentManager;

    public function __construct()
    {
        $this->postManager = new PostManager;
        $this->userManager = new UserManager;
        $this->commentManager = new CommentManager;
        $this->contactManager = new ContactManager;
        $this->contentManager = new ContentManager;
    }

    public function notBlank($name, $value)
    {
        if (empty($value)) {
            return 'Le champ '. $name .' ne peut être vide.';
        }
    }
    public function minLength($name, $value, $minSize)
    {
        if (strlen($value) < $minSize) {
            return 'Le champ '. $name .' doit contenir au moins '. $minSize .' caractères.';
        }
    }
    public function maxLength($name, $value, $maxSize)
    {
        if (strlen($value) > $maxSize) {
            return 'Le champ '. $name .' doit contenir au maximum '. $maxSize .' caractères.';
        }
    }

    public function isPositive($name, $value)
    {
        if ($value < 0) {
            return 'Le champ '. $name .' n\'est pas valide.';
        }
    }

    public function isNumeric($name, $value)
    {
        if (!is_numeric($value)) {
            return 'Le champ '. $name .' n\'est pas valide.';
        }
    }

    public function containsLetter($name, $value)
    {
        if (!preg_match('#[a-zA-Z]+#', $value)) {
            return 'Le champ '. $name .' doit contenir au moins une lettre.';
        }
    }

    public function containsOnlyLetter($name, $value)
    {
        if (!preg_match('#^[a-zA-Z]+$#', $value)) {
            return 'Le champ '. $name .' ne doit contenir que des lettres.';
        }
    }

    public function containsNumeric($name, $value)
    {
        if (!preg_match('#[0-9]+#', $value)) {
            return 'Le champ '. $name .' doit contenir au moins un chiffre.';
        }
    }

    public function isEmail($name, $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return 'Le champ ' . $name . ' n\'est pas valide.';
        }
    }

    public function isMobile($name, $value)
    {
        if(!preg_match('#^0[1-9]([-. ]?[0-9]{2}){4}$#', $value)) {
            return 'Le champ ' . $name . ' n\'est pas valide.';
        }
    }

    public function hasBirthdateFormat($name, $value)
    {
        if(!preg_match('#^([0-9]{2})(-)([0-9]{2})(-)([0-9]{4})$#', $value)) {
            return 'Le champ ' . $name . ' n\'est pas dans un format autorisé.';
        }
    }

    public function isValidBirthdate($name, $value)
    {
        $checkDate = explode('-', $value);

        if(!checkdate($checkDate[1], $checkDate[0], $checkDate[2]) || ($checkDate[2] < 1900 )) {
            return 'Le champ ' . $name . ' n\'est pas valide.';
        }
    }

    public function pseudoExists($name, $value, $userId = null)
    {
        if ($userId != null) {
            if ($this->userManager->pseudoExists($value, $userId) == 1) {
                return 'Ce pseudo est déjà associé à un compte';
            }
        
        } else {
            if ($this->userManager->pseudoExists($value) == 1) {
                return 'Ce pseudo est déjà associé à un compte';
            }
        }
    }

    public function emailExists($name, $value, $userId = null)
    {
        if ($userId != null) {
            if ($this->userManager->emailExists($value, $userId) == 1) {
                return 'Cet email est déjà associé à un compte';
            }
        
        } else {
            if ($this->userManager->emailExists($value) == 1) {
                return 'Cet email est déjà associé à un compte';
            }
        }
    }

    public function exists($name, $value)
    {
        $name = ucfirst(substr($name, 0, -2));
        $manager = strtolower($name) . 'Manager';
        $method = 'get' . $name . 's';

        $objects = $this->$manager->$method();

        foreach ($objects as $object) {
            if ($object->getId() == $value) {
                return null;
            }
        }
        return 'Le champ ' . $name . ' ne correspond pas à un élément existant';
    }
}