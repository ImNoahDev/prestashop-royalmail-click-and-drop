<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta;

use Configuration;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\CreateOrderService;
use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto\CreateOrderFactory;
use Exception;
use Order;
use Psr\Log\LoggerInterface;

class ShippingOrderService
{
    private $createOrderService;
    private $royalMailRepo;
    private $logger;

    public function __construct(
        CreateOrderService $createOrderService,
        RoyalMailRepository $royalMailRepo,
        LoggerInterface $logger
    ) {
        $this->createOrderService = $createOrderService;
        $this->royalMailRepo = $royalMailRepo;
        $this->logger = $logger;
    }

    public function register(int $orderId)
    {
        $order = new Order($orderId);

        $configCarrierId = intval(Configuration::get('ROYALMAILCLICKANDDROP_CARRIER_ID') ?? 0);
        if ((int) $configCarrierId === 0 || (int) $order->id_carrier !== $configCarrierId) {
            $this->logger->info(
                'RoyalMail: Not confirming with RM as different carrier is selected.' . $order->id_carrier . ':' . $configCarrierId,
                ['object_id' => $orderId, 'object_type' => 'Order']
            );

            return;
        }

        try {
            $orderDto = CreateOrderFactory::fromPrestaOrderId($orderId);

            $orderResponse = $this->createOrderService->create($orderDto);

            $this->logger->info('RoyalMail: Successfully created order with response: ' . json_encode($orderResponse->getRawArray()));

            $this->royalMailRepo->upsertOrderIdentifier($orderId, $orderResponse->getOrderIdentifier());

            $this->logger->info(
                'RoyalMail: Set orderIdenfitier: ' . $orderResponse->getOrderIdentifier(),
                ['object_id' => $orderId, 'object_type' => 'Order']
            );
        } catch (Exception $e) {
            $this->logger->critical(
                'RoyalMail: Could not confirm order with RoyalMail service: ' . $e->getMessage(),
                ['object_id' => $orderId, 'object_type' => 'Order']
            );
        }
    }
}
