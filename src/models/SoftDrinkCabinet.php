<?php

namespace App\Models;

/**
 * Class SoftDrinkCabinet
 * @package App\Models
 */
class SoftDrinkCabinet
{
    // door status options
    const DOOR_STATUS_CLOSED = 0;
    const DOOR_STATUS_OPEN = 1;
    // occupancy status options
    const OCCUPANCY_STATUS_EMPTY = 0;
    const OCCUPANCY_STATUS_PARTLY_FULL = 1;
    const OCCUPANCY_STATUS_FULL = 2;

    private $maxShelfCount;
    private $doorStatus;
    private $shelves = [];
    private $occupancyStatus;

    public function __construct($maxShelfCount)
    {
        $this->doorStatus = self::DOOR_STATUS_CLOSED;
        $this->occupancyStatus = self::OCCUPANCY_STATUS_EMPTY;
        $this->maxShelfCount = $maxShelfCount;
    }

    /**
     * Gets shelf count
     * @return mixed
     */
    public function getShelfCount()
    {
        return $this->maxShelfCount;
    }

    /**
     * Gets door status
     * @return bool
     */
    public function getDoorStatus()
    {
        return $this->doorStatus;
    }

    /**
     * Determines if the door is open
     * @return bool
     */
    public function isDoorOpen()
    {
        return $this->doorStatus == self::DOOR_STATUS_OPEN;
    }

    /**
     * Gets occupancy status
     * @return int
     */
    public function getOccupancyStatus()
    {
        return $this->occupancyStatus;
    }

    /**
     * Checks if the cabinet is full
     * @return bool
     */
    public function isFull()
    {
        return $this->occupancyStatus == self::OCCUPANCY_STATUS_FULL;
    }

    /**
     * Gets shelves
     * @return array
     */
    public function getShelves()
    {
        return $this->shelves;
    }

    /**
     * Checks if the cabinet is partly full
     * @return bool
     */
    public function isPartlyFull()
    {
        return $this->occupancyStatus == self::OCCUPANCY_STATUS_PARTLY_FULL;
    }

    /**
     * Adds a shelf into the cabinet
     * @param Shelf $shelf
     * @throws \Exception
     */
    public function addShelf(Shelf $shelf)
    {
        if (count($this->shelves) == $this->maxShelfCount) {
            throw new \Exception('You can not add more shelf because the cabinet\'s shelf capacity is full!');
        }

        $this->shelves[] = $shelf;
    }

    /**
     * Puts a product into first empty shelf
     * @param Product $product
     * @throws \Exception
     */
    public function putProduct(Product $product)
    {
        if (!$this->isDoorOpen()) {
            throw new \Exception('You can not add a new product because the door is closed!');
        }

        $hasAdded = false;
        foreach ($this->shelves as $shelf) {
            if ($shelf->isFull()) {
                continue;
            }

            $shelf->putProduct($product);
            $hasAdded = true;
            break;
        }

        if ($hasAdded) {
            $this->afterAddedProduct($product);
        } else {
            throw new \Exception("Product {$product->getName()} could not be added because the cabinet is full!");
        }
    }

    /**
     * Takes out a product from the cabinet
     * @throws \Exception
     */
    public function takeProduct()
    {
        if (!$this->isDoorOpen()) {
            throw new \Exception('You can not take a product from the cabinet because the door is closed!');
        }

        $takenProduct = null;
        for ($i = count($this->shelves) - 1; $i >= 0; $i--) {
            $shelf = $this->shelves[$i];
            if ($shelf->isEmpty()) {
                continue;
            }

            $takenProduct = $shelf->takeProduct();
            break;
        }

        if (isset($takenProduct)) {
            $this->afterTakenProduct($takenProduct);
        } else {
            throw new \Exception("Any product could not be taken because the cabinet is empty!");
        }
    }

    /**
     * Opens the door of the cabinet
     * @throws \Exception
     */
    public function openDoor()
    {
        if ($this->isDoorOpen()) {
            throw new \Exception('The door is already open!');
        }

        $this->doorStatus = self::DOOR_STATUS_OPEN;
    }

    /**
     * Closes the door of the cabinet
     * @throws \Exception
     */
    public function closeDoor()
    {
        if (!$this->isDoorOpen()) {
            throw new \Exception('The door is already closed!');
        }

        $this->doorStatus = self::DOOR_STATUS_CLOSED;
    }

    /**
     * Does some operations after added a product into the cabinet
     * @param Product $product
     */
    public function afterAddedProduct(Product $product)
    {
        $this->calculateOccupancyStatus();
    }

    /**
     * Does some operations after taken a product out from the cabinet
     * @param Product $product
     */
    public function afterTakenProduct(Product $product)
    {
        $this->calculateOccupancyStatus();
    }

    /**
     * Calculates the occupancy status of the cabinet
     */
    protected function calculateOccupancyStatus()
    {
        $fullShelfCount = 0;
        $hasAddedAnyProducts = false;

        /**
         * calculate counts of full shelves
         * determine if there is any products in the cabine
         */
        foreach ($this->shelves as $shelf) {
            if ($shelf->isFull()) {
                $fullShelfCount++;
            } else if ($shelf->isPartiallyFull()) {
                $hasAddedAnyProducts = true;
            }
        }

        /**
         * determine occupancy status of the cabinet
         */
        if ($fullShelfCount == count($this->shelves)) {
            $this->occupancyStatus = self::OCCUPANCY_STATUS_FULL;
        } else if ($hasAddedAnyProducts) {
            $this->occupancyStatus = self::OCCUPANCY_STATUS_PARTLY_FULL;
        } else {
            $this->occupancyStatus = self::OCCUPANCY_STATUS_EMPTY;
        }
    }

    /**
     * Gets occupancy status text (label)
     * @param $status
     * @return string
     */
    public static function getOccupancyStatusText($status)
    {
        if ($status == self::OCCUPANCY_STATUS_EMPTY) {
            $text = 'Empty';
        } else if ($status == self::OCCUPANCY_STATUS_PARTLY_FULL) {
            $text = 'Partly Full';
        } else {
            $text = 'Full';
        }

        return $text;
    }

    /**
     * Dumps the cabinet status all together
     * @return string
     */
    public function __toString()
    {
        $infoArr = [
            'DOOR STATUS' => $this->doorStatus == self::DOOR_STATUS_OPEN ? 'Open' : 'Closed',
            'OCCUPANCY STATUS' => self::getOccupancyStatusText($this->occupancyStatus),
            'SHELF COUNT' => count($this->shelves),
        ];

        for ($i = 0; $i < count($this->shelves); $i++) {
            $infoArr['SHELF ' . ($i + 1)] = "\n" . (string)$this->shelves[$i];
        }

        $output = '';
        foreach ($infoArr as $key => $val) {
            $output .= "$key: $val\n";
        }

        return $output;
    }
}

