# RoyalMail Click & Drop
![](https://github.com/Delota/prestashop-royalmail-click-and-drop/actions/workflows/ci.yml/badge.svg)

This repository automatically registers parcels with the Royal Mail Click & Drop service.

## Requirements
* Prestashop 1.7+
* PHP 7.2+

## Hooks
**ActionPaymentConfirmation**

When a new payment confirmation is received, the system will automatically call the Royal Mail API to register the parcel.

**ActionOrderStatusUpdate**

When an order is about to be set to Shipped, it will first try to pull a tracking number from the Royal Mail API and add this to the order before the status is set to Shipped.