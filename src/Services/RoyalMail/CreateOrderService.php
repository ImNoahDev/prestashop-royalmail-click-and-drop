<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\CreateOrder;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\CreateOrderResponse;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\ProductItem;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateOrderService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param CreateOrder $createOrderDto
     *
     * @return int orderIdentifier
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function create(CreateOrder $createOrderDto): CreateOrderResponse
    {
        $addressDto = $createOrderDto->getAddress();

        $productItems = [];
        /** @var ProductItem $productItem */
        foreach ($createOrderDto->getPackageContent()->getProductItems() as $productItem) {
            $productItems[] = [
                'name' => $productItem->getName(),
                'quantity' => $productItem->getQuantity(),
                'unitValue' => $productItem->getUnitValue(),
                'unitWeightInGrams' => $productItem->getUnitWeightInGrams(),
            ];
        }

        $return = [
            'orderReference' => $createOrderDto->getOrderReference(),
            'recipient' => [
                'address' => [
                    'addressLine1' => $addressDto->getAddressLine1(),
                    'city' => $addressDto->getCity(),
                    'countryCode' => $addressDto->getCountryCode(),
                ],
            ],
            'orderDate' => $createOrderDto->getOrderDate()->format('Y-m-d'),
            'subtotal' => $createOrderDto->getSubtotal(),
            'shippingCostCharged' => $createOrderDto->getShippingCostCharged(),
            'total' => $createOrderDto->getTotal(),
            'currencyCode' => $createOrderDto->getCurrency(),
            'packages' => [
                [
                    'weightInGrams' => $createOrderDto->getPackageContent()->getWeightInGrams(),
                    'packageFormatIdentifier' => 'smallParcel',
                    'contents' => $productItems,
                ],
            ],
        ];

        if (!empty($addressDto->getAddressLine2())) {
            $return['recipient']['address']['addressLine2'] = $addressDto->getAddressLine2();
        }

        if (!empty($addressDto->getAddressLine3())) {
            $return['recipient']['address']['addressLine3'] = $addressDto->getAddressLine3();
        }

        if (!empty($addressDto->getCompanyName())) {
            $return['recipient']['address']['companyName'] = $addressDto->getCompanyName();
        }

        if (!empty($addressDto->getFullName())) {
            $return['recipient']['address']['fullName'] = $addressDto->getFullName();
        }

        if (!empty($addressDto->getCounty())) {
            $return['recipient']['address']['county'] = $addressDto->getCounty();
        }

        if (!empty($addressDto->getPostcode())) {
            $return['recipient']['address']['postcode'] = $addressDto->getPostcode();
        }

        $body = [
            'items' => [$return],
        ];

        $response = $this->httpClient->request('POST', 'orders', [
            'json' => $body,
        ]);

        $responseData = json_decode((string) $response->getContent(), true);
        if ($responseData['errorsCount'] > 0) {
            throw new Exception('RoyalMail: Failed to call API: ' . json_encode($responseData['failedOrders']));
        }

        return new CreateOrderResponse(
            $responseData,
            $responseData['createdOrders'][0]['orderIdentifier']
        );
    }
}
