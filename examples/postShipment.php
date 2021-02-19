<?php

use Sylapi\Courier\CourierFactory;

$courier = CourierFactory::create('Olza', [
    'login'           => 'mylogin',
    'password'        => 'mypassword',
    'sandbox'         => true,
    'requestLanguage' => 'pl',
    'labelType'       => 'A4',
    'speditionCode'   => 'GLS',
    'shipmentType'    => 'WAREHOUSE',
]);

/**
 * PostShipment.
 */
$booking = $courier->makeBooking();
$booking->setShipmentId('123456');

try {
    $response = $courier->postShipment($booking);
    if ($response->hasErrors()) {
        var_dump($response->getFirstError()->getMessage());
    } else {
        var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
        var_dump($response->trackingId); // Zewnetrzny idetyfikator sledzenia przesylki
        var_dump($response->trackingBarcode); // Zewnetrzny idetyfikator sledzenia przesylki
    }
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
