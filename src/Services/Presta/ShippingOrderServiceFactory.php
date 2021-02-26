<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\CreateOrderServiceFactory;
use PrestaShop\PrestaShop\Adapter\LegacyLogger;

class ShippingOrderServiceFactory
{
    public static function create(): ShippingOrderService
    {
        return new ShippingOrderService(
            CreateOrderServiceFactory::create(),
            new RoyalMailRepository(),
            new LegacyLogger()
        );
    }
}
