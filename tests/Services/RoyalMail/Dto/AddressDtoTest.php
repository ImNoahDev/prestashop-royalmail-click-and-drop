<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Tests\Services\RoyalMail\Test;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\Address;
use Delota\Prestashop\RoyalMailClickAndDrop\Tests\Services\RoyalMail\TestCase;
use InvalidArgumentException;

class AddressDtoTest extends TestCase
{
    /**
     * @dataProvider invalidCountryDataProvider
     */
    public function testInvalidCountry(string $countryCode, $regex)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches($regex);

        new Address('test', 'A', $countryCode);
    }

    public function invalidCountryDataProvider()
    {
        return [
            ['A', '/Country code must be exactly 3 letters/'],
            ['AB', '/Country code must be exactly 3 letters/'],
            ['ABCD', '/Country code must be exactly 3 letters/'],
            ['ABa', '/Country code must be all uppercase/'],
        ];
    }

    public function testEmptyCity()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('City cannot be empty');

        new Address('test', '', 'NLD');
    }
}
