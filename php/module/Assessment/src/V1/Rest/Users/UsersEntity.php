<?php
namespace Assessment\V1\Rest\Users;

use ArrayObject;

class UsersEntity extends ArrayObject
{

	/**
     * Get the primary identifier
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the primary identifier
     *
     * @param int $id
     * 
     * @return EntityTrait
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
    * Override the default function in order to hide the Password field
    * 
    * @return Array
    */
    public function getArrayCopy()
    {
        $vars = get_object_vars($this);
        unset($vars['password']); // Since we don't actually want to expose the Password

        return $vars;
    }
}
