<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

class ProductItem
{
    private $quantity;

    private $unitValue;

    private $unitWeightInGrams;

    private $name;

    public function __construct($name, $quantity, $unitValue, $unitWeightInGrams)
    {
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setUnitValue($unitValue);
        $this->setUnitWeightInGrams($unitWeightInGrams);
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     *
     * @return ProductItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitValue()
    {
        return $this->unitValue;
    }

    /**
     * @param mixed $unitValue
     *
     * @return ProductItem
     */
    public function setUnitValue($unitValue)
    {
        $this->unitValue = $unitValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnitWeightInGrams()
    {
        return $this->unitWeightInGrams;
    }

    /**
     * @param mixed $unitWeightInGrams
     *
     * @return ProductItem
     */
    public function setUnitWeightInGrams($unitWeightInGrams)
    {
        $this->unitWeightInGrams = $unitWeightInGrams;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return ProductItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
