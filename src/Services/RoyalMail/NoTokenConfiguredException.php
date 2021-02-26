<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail;

use Exception;
use Throwable;

class NoTokenConfiguredException extends Exception
{
    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message ?? 'There is no Auth key configured for the Royal Mail integration',
            $code,
            $previous
        );
    }
}
