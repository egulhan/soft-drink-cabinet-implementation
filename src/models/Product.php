<?php

namespace App\Models;

/**
 * Class Product
 * @package App\Models
 */
class Product
{
    private $name;

    /**
     * Product constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the name of the product
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the product
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}