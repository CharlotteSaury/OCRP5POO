<?php

namespace Src\Config;

/**
 * Class File
 * Manage $_FILES variables 
 */
class File
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function set($id, $name, $value)
    {
        $_FILES[$id][$name] = $value;
    }

    public function get($id, $name = null)
    {
        if ($name != null)
        {
            if(isset($_FILES[$id][$name])) 
            {
            return $_FILES[$id][$name];
            }
        }
        else
        {
            if(isset($_FILES[$id])) 
            {
            return $_FILES[$id];
            }
        }
        
    }

    public function all()
    {
        return $this->file;
    }
}