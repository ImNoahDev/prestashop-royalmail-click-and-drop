<?php

declare(strict_types=1);

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\RoyalMail;

use Configuration;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientFactory
{
    public static function create(string $baseUri = 'https://api.parcel.royalmail.com/api/v1/'): HttpClientInterface
    {
        $token = Configuration::get('ROYALMAILCLICKANDDROP_AUTH_KEY');

        if (empty($token)) {
            throw new NoTokenConfiguredException();
        }
        $client = HttpClient::create(['base_uri' => $baseUri]);

        return ScopingHttpClient::forBaseUri($client, $baseUri, [
                'headers' => [
                    'Authorization: ' . $token,
                ],
            ]
        );
    }
}
