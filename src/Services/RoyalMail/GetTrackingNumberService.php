<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetTrackingNumberService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getTrackingNumber(int $royalMailId): string
    {
        $response = $this->httpClient->request('GET', 'orders/' . $royalMailId);

        $responseData = json_decode((string) $response->getContent(), true);

        return $trackingNumber = $responseData[0]['trackingNumber'];
    }
}
