<?php

require_once 'helper_functions.php';

use App\Models\Product;
use App\SoftDrinkCabinetBuilder;

try {
    $maxSelfCount = 3;
    $eachShelfMaxProductCount = 20;

    // build a soft drink cabinet
    $softDrinkCabinetBuilder = new SoftDrinkCabinetBuilder($maxSelfCount);

    // create product shelves for the cabinet
    $softDrinkCabinetBuilder->produceShelves($eachShelfMaxProductCount);

    // get the built cabinet
    $softDrinkCabinet1 = $softDrinkCabinetBuilder->getSoftDrinkCabinet();

    $softDrinkCabinet1->openDoor();

    // add 45 products into the cabinet
    for ($i = 1; $i <= 45; $i++) {
        $productName = 'Ayran - ' . $i;
        $product = new Product($productName);
        $softDrinkCabinet1->putProduct($product);
    }

    // show cabinet status
    echo "\nADDED SOME PRODUCTS\n";
    echo "CABINET STATUS\n\n";
    echo $softDrinkCabinet1;

    // take out 15 products from the cabinet
    for ($i = 1; $i <= 15; $i++) {
        $softDrinkCabinet1->takeProduct();
    }

    $softDrinkCabinet1->closeDoor();

    // show cabinet status
    echo "\nTAKEN SOME PRODUCTS\n";
    echo "CABINET STATUS\n\n";
    echo $softDrinkCabinet1;

} catch (\Exception $exception) {
    echo "\nERROR: " . $exception->getMessage() . "\n";
}


