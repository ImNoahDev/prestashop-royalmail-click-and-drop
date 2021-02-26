<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\Dto;

use Address as PrestaAddress;
use Currency as PrestaCurrency;
use DateTimeImmutable;
use Order as PrestaOrder;

class CreateOrderFactory
{
    public static function fromPrestaOrderId(int $orderId): CreateOrder
    {
        $order = new PrestaOrder($orderId);
        $currency = new PrestaCurrency($order->id_currency);
        $address = new PrestaAddress($order->id_address_delivery);

        $products = $order->getProducts();

        $productItems = [];

        foreach ($products as $product) {
            $productItems[] = new ProductItem(
                $product['product_name'],
                $product['product_quantity'],
                $product['product_price'],
                $product['product_weight']
            );
        }
        $packageContentDto = new PackageContent(
            $order->getTotalWeight() * 1000,
            $productItems
        );

        $dto = new CreateOrder(
            new DateTimeImmutable($order->date_add),
            AddressFactory::fromPrestaAddress($address),
            $order->getTotalProductsWithTaxes(),
            $order->total_shipping,
            $order->getTotalProductsWithTaxes(),
            $currency->iso_code,
            $packageContentDto
        );

        $dto->setOrderReference($order->reference);

        return $dto;
    }
}
