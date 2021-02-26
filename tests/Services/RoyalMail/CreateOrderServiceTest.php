<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Tests\Services\RoyalMail;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\CreateOrderService;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\Address;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\CreateOrder;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\PackageContent;
use stdClass;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateOrderServiceTest extends TestCase
{
    public function testCreate()
    {
        $addressDto = new Address(
            'Leicester Square',
            'London',
            'ENG'
        );
        $addressDto->setAddressLine2('ADDRESS_2');
        $addressDto->setAddressLine3('ADDRESS_3');
        $addressDto->setCounty('COUNTY_1');
        $addressDto->setPostcode('1234');
        $addressDto->setFullName('FULL_NAME');
        $addressDto->setCompanyName('COMPANY_NAME');
        $date = new \DateTimeImmutable();
        $createOrderDto = new CreateOrder(
            $date,
            $addressDto,
            5,
            1,
            7,
            'EUR',
            new PackageContent(500, [])
        );

        $expectedBody = [
            'orderDate' => $date->format('Y-m-d'),
            'recipient' => [
                'address' => [
                    'addressLine1' => 'Leicester Square',
                    'city' => 'London',
                    'countryCode' => 'ENG',
                    'addressLine2' => 'ADDRESS_2',
                    'addressLine3' => 'ADDRESS_3',
                    'county' => 'COUNTY_1',
                    'postcode' => '1234',
                    'fullName' => 'FULL_NAME',
                    'companyName' => 'COMPANY_NAME',
                ],
            ],
        ];

        $responseStub = $this->getMockBuilder(stdClass::class)->addMethods(['getContent'])->getMock();
        $responseStub->method('getContent')->willReturn(json_encode([
            'createdOrders' => [
                [
                    'orderIdentifier' => 1234,
                ],
            ],
            'errorsCount' => 0,
            'successCount' => 1,
        ]));

        $httpMock = \Mockery::mock(HttpClientInterface::class);
        $httpMock->shouldReceive('request')->with(
            'POST',
            'orders',
            \Mockery::capture($usedBody)
        )->andReturn($responseStub);

        $createOrderService = new CreateOrderService($httpMock);
        $orderResponse = $createOrderService->create($createOrderDto);

        $data = $orderResponse->getRawArray();

        self::assertSame(1234, $data['createdOrders'][0]['orderIdentifier']);

        self::assertIsArray($usedBody);
        self::assertArraySubset($expectedBody, $usedBody['json']['items'][0]);
    }
}
