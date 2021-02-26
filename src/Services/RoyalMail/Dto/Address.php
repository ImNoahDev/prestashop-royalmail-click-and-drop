<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

use Webmozart\Assert\Assert;

class Address
{
    private $fullName;
    private $companyName;
    private $addressLine1;
    private $addressLine2;
    private $addressLine3;
    private $city;
    private $county;
    private $postcode;
    private $countryCode;

    public function __construct(string $addressLine1, string $city, string $countryCode)
    {
        $this->setAddressLine1($addressLine1);
        $this->setCity($city);
        $this->setCountryCode($countryCode);
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName): Address
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setCompanyName($companyName): Address
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function setAddressLine1($addressLine1): Address
    {
        Assert::stringNotEmpty($addressLine1, 'Address line 1 cannot be empty');
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function setAddressLine2($addressLine2): Address
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getAddressLine3()
    {
        return $this->addressLine3;
    }

    public function setAddressLine3($addressLine3): Address
    {
        $this->addressLine3 = $addressLine3;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city): Address
    {
        Assert::stringNotEmpty($city, 'City cannot be empty');
        $this->city = $city;

        return $this;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function setCounty($county): Address
    {
        $this->county = $county;

        return $this;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function setPostcode($postcode): Address
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode): Address
    {
        Assert::length($countryCode, 3, 'Country code must be exactly 3 letters. Got: %s');
        Assert::upper($countryCode, 'Country code must be all uppercase. Got: %s');
        $this->countryCode = $countryCode;

        return $this;
    }
}
