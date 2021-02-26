<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

use DateTimeImmutable;

class CreateOrder
{
    private $orderDate;
    private $address;
    private $shippingCostCharged;
    private $subtotal;
    private $total;
    private $orderReference;
    private $currency;
    private $packageContent;

    public function __construct(DateTimeImmutable $orderDate, Address $address, $subtotal, $shippingCostCharged, $total, string $currency, PackageContent $packageContent)
    {
        $this->setOrderDate($orderDate);
        $this->setAddress($address);
        $this->setSubtotal($subtotal);
        $this->setShippingCostCharged($shippingCostCharged);
        $this->setTotal($total);
        $this->setCurrency($currency);
        $this->setPackageContent($packageContent);
    }

    /**
     * @return mixed
     */
    public function getOrderDate(): DateTimeImmutable
    {
        return $this->orderDate;
    }

    /**
     * @param mixed $orderDate
     *
     * @return CreateOrder
     */
    public function setOrderDate(DateTimeImmutable $orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     *
     * @return CreateOrder
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingCostCharged()
    {
        return $this->shippingCostCharged;
    }

    /**
     * @param mixed $shippingCostCharged
     *
     * @return CreateOrder
     */
    public function setShippingCostCharged($shippingCostCharged)
    {
        $this->shippingCostCharged = $shippingCostCharged;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param mixed $subtotal
     *
     * @return CreateOrder
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     *
     * @return CreateOrder
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderReference()
    {
        return $this->orderReference;
    }

    /**
     * @param mixed $orderReference
     *
     * @return CreateOrder
     */
    public function setOrderReference($orderReference)
    {
        $this->orderReference = $orderReference;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     *
     * @return CreateOrder
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPackageContent(): PackageContent
    {
        return $this->packageContent;
    }

    /**
     * @param mixed $packageContent
     *
     * @return CreateOrder
     */
    public function setPackageContent($packageContent)
    {
        $this->packageContent = $packageContent;

        return $this;
    }
}
