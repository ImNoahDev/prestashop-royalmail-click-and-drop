<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\GetTrackingNumberService;
use Exception;
use Order;
use Psr\Log\LoggerInterface;

class TrackingNumberService
{
    private $trackingNumberService;
    private $royalMailRepo;
    private $logger;

    public function __construct(
        GetTrackingNumberService $trackingNumberService,
        RoyalMailRepository $royalMailRepo,
        LoggerInterface $logger
    ) {
        $this->trackingNumberService = $trackingNumberService;
        $this->royalMailRepo = $royalMailRepo;
        $this->logger = $logger;
    }

    public function tryRetrieveAndSet(int $orderId)
    {
        $identifier = $this->royalMailRepo->getOrderIdentifier($orderId);

        if (empty($identifier)) {
            $this->logger->info(
                'RoyalMail: No RMOrder found',
                ['object_id' => $orderId, 'object_type' => 'Order']
            );

            return;
        }

        try {
            $trackingNumber = $this->trackingNumberService->getTrackingNumber($identifier);

            $order = new Order($orderId);
            $order->setWsShippingNumber($trackingNumber);

            $this->logger->info(
                'RoyalMail: Successfully set tracking number: ' . $trackingNumber,
                ['object_id' => $orderId, 'object_type' => 'Order']
            );
        } catch (Exception $e) {
            $this->logger->critical(
                'RoyalMail: Could not get tracking number: ' . $e->getMessage(),
                ['object_id' => $orderId, 'object_type' => 'Order']
            );
        }
    }
}
