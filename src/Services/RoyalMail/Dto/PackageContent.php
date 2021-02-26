<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

class PackageContent
{
    private $weightInGrams;
    private $productItems;

    public function __construct($weightInGrams, array $contents)
    {
        $this->weightInGrams = $weightInGrams;
        $this->productItems = $contents;
    }

    public function addProductItem(ProductItem $item)
    {
        $this->productItems[] = $item;
    }

    /**
     * @return mixed
     */
    public function getProductItems()
    {
        return $this->productItems;
    }

    /**
     * @return mixed
     */
    public function getWeightInGrams()
    {
        return $this->weightInGrams;
    }
}
