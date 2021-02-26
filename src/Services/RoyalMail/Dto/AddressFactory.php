<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

use Address as PrestaAddress;
use Country as PrestaCountry;
use League\ISO3166\ISO3166;

class AddressFactory
{
    public static function fromPrestaAddress(PrestaAddress $address)
    {
        $country = new PrestaCountry($address->id_country);

        $addressDto = new Address(
            $address->address1,
            $address->city,
            self::convertCountryAlpha2To3($country->iso_code)
        );

        if (!empty($address->address2)) {
            $addressDto->setAddressLine2($address->address2);
        }

        if (!empty($address->firstname) || !empty($address->lastname)) {
            $addressDto->setFullName(trim($address->firstname . ' ' . $address->lastname));
        }

        if (!empty($address->company)) {
            $addressDto->setCompanyName($address->company);
        }

        if (!empty($address->postcode)) {
            $addressDto->setPostcode($address->postcode);
        }

        return $addressDto;
    }

    private static function convertCountryAlpha2To3(string $alpha2): string
    {
        return (new ISO3166())->alpha2($alpha2)['alpha3'];
    }
}
