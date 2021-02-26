<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Tests\Services\RoyalMail;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\GetTrackingNumberService;
use stdClass;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetTrackingNumberServiceTest extends TestCase
{
    public function testCreate()
    {
        $responseStub = $this->getMockBuilder(stdClass::class)->addMethods(['getContent'])->getMock();
        $responseStub->method('getContent')->willReturn(json_encode([
            [
                'orderIdentifier' => 1234,
                'trackingNumber' => 'PHPUNIT_TRACKING_NUMBER',
            ],
        ]));

        $httpMock = \Mockery::mock(HttpClientInterface::class);
        $httpMock->shouldReceive('request')->with(
            'GET',
            'orders/1234'
        )->andReturn($responseStub);

        $trackingNumberService = new GetTrackingNumberService($httpMock);
        $trackingNumber = $trackingNumberService->getTrackingNumber(1234);

        self::assertSame('PHPUNIT_TRACKING_NUMBER', $trackingNumber);
    }
}
