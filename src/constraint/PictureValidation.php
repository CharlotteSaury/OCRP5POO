<?php

namespace Src\Constraint;

use Src\Config\Parameter;
use Src\Config\File;

/**
 * Class PictureValidation
 * Manage input validity for file uploads
 */
class PictureValidation extends Validation
{
    /**
     * Get all file informations (File $file) and analyzes each value regarding its name and constraints
     * @param  File $file
     * @return array $errors [array containing optional error message associated with field name]
     */
    public function checkPicture(File $file, $name)
    {
        foreach ($file->all() as $picture => $array) {

            if ($picture === $name) {
                if (!empty($array['name'])) {
                    foreach ($array as $field => $value) {

                        if ($field === 'error') {
                            $error = $this->checkPictureError($value);
                            $this->addError($field, $error);

                        } elseif ($field === 'size') {
                            $error = $this->checkPictureSize($value);
                            $this->addError($field, $error);

                        } elseif ($field === 'name') {
                            $fileInfos = pathinfo($value);
                            $pictureExtension = $fileInfos['extension'];
                            $error = $this->checkPictureName($pictureExtension);
                            $this->addError($field, $error);
                        }
                    }
                } else {
                $this->addError('name', 'Aucun fichier téléchargé.');
                }
            }
        }
        return $this->errors;
    }

    protected function checkPictureError($value) {
        if ($this->constraint->pictureValid($value)) {
            return $this->constraint->pictureValid($value);
        }
    }

    protected function checkPictureSize($value)
    {
        if ($this->constraint->pictureMaxSize($value, 1000000)) {
            return $this->constraint->pictureMaxSize($value, 1000000);
        }
    }

    protected function checkPictureName($pictureExtension)
    {
        if ($this->constraint->pictureExtension($pictureExtension)) {
            return $this->constraint->pictureExtension($pictureExtension);
        }
    }
}
