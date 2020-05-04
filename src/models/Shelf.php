<?php

namespace App\Models;

/**
 * Class Shelf
 * @package App\Models
 */
class Shelf
{
    private $maxProductCount;
    private $products;

    /**
     * Shelf constructor.
     * @param $maxProductCount
     */
    public function __construct($maxProductCount)
    {
        $this->maxProductCount = $maxProductCount;
        $this->products = [];
    }

    /**
     * Gets max product count
     * @return mixed
     */
    public function getMaxProductCount()
    {
        return $this->maxProductCount;
    }

    /**
     * Gets the count of products on the shelf
     * @return int
     */
    public function getProductCount()
    {
        return count($this->products);
    }

    /**
     * Gets products
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Checks if the shelf is empty
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getProductCount() == 0;
    }

    /**
     * Checks if the shelf is full
     * @return bool
     */
    public function isFull()
    {
        return $this->getProductCount() == $this->maxProductCount;
    }

    /**
     * Checks if the shelf is partly full
     * @return bool
     */
    public function isPartiallyFull()
    {
        return !$this->isEmpty() && !$this->isFull();
    }

    /**
     * Puts a product into the shelf
     * @param Product $product
     * @throws \Exception
     */
    public function putProduct(Product $product)
    {
        if ($this->isFull()) {
            throw new \Exception('Can not be added more products because the shelf is full!');
        }

        $this->products[] = $product;
    }

    /**
     * Takes a product out from the shelf
     * @return mixed
     * @throws \Exception
     */
    public function takeProduct()
    {
        if ($this->isEmpty()) {
            throw new \Exception('Can not be taken a product from the cabinet because the shelf is empty!');
        }

        return array_pop($this->products);
    }

    /**
     * Dumps the shelf status
     * @return string
     */
    public function __toString()
    {
        $infoArr = [
            'IS FULL' => $this->isFull() ? 'Yes' : 'No',
            'IS PARTLY FULL' => $this->isPartiallyFull() ? 'Yes' : 'No',
            'PRODUCT COUNT' => $this->getProductCount(),
        ];

        $output = '';
        foreach ($infoArr as $key => $val) {
            $output .= "$key: $val\n";
        }

        return $output;
    }
}