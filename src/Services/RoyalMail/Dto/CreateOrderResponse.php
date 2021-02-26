<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

class CreateOrderResponse
{
    private $rawArray;
    private $orderIdentifier;

    public function __construct($rawArray, $orderIdentifier)
    {
        $this->rawArray = $rawArray;
        $this->orderIdentifier = $orderIdentifier;
    }

    public function getRawArray()
    {
        return $this->rawArray;
    }

    public function getOrderIdentifier()
    {
        return $this->orderIdentifier;
    }
}
