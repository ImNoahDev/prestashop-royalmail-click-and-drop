<?php

namespace Delota\Prestashop\RoyalMailClickAndDrop\Services\Presta;

use Db;

class RoyalMailRepository
{
    public function upsertOrderIdentifier(int $orderId, string $orderIdentifier)
    {
        Db::getInstance()->insert('royal_mail_order', [
            'id_order' => $orderId,
            'id_royalmail' => $orderIdentifier,
        ], false, true, Db::REPLACE);
    }

    public function getOrderIdentifier(int $orderId): ?string
    {
        $rmOrderResult = Db::getInstance()
            ->query('SELECT id_royalmail FROM `' . _DB_PREFIX_ . 'royal_mail_order` WHERE id_order = ' . $orderId);

        if (empty($rmOrderResult)) {
            return null;
        }

        $rmOrder = Db::getInstance()->nextRow($rmOrderResult);

        return $rmOrder['id_royalmail'];
    }
}
