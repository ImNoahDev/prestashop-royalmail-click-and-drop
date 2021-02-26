<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta;

use Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail\GetTrackingNumberServiceFactory;
use PrestaShop\PrestaShop\Adapter\LegacyLogger;

class TrackingNumberServiceFactory
{
    public static function create(): TrackingNumberService
    {
        return new TrackingNumberService(
            GetTrackingNumberServiceFactory::create(),
            new RoyalMailRepository(),
            new LegacyLogger()
        );
    }
}
