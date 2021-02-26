<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail;

class CreateOrderServiceFactory
{
    public static function create(): CreateOrderService
    {
        return new CreateOrderService(HttpClientFactory::create());
    }
}
