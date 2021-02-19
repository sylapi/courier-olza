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
 * CreateShipment.
 */
$sender = $courier->makeSender();
$sender->setFullName('Nazwa Firmy/Nadawca')
    ->setStreet('Ulica')
    ->setHouseNumber('2a')
    ->setApartmentNumber('1')
    ->setCity('Miasto')
    ->setZipCode('66100')
    ->setCountry('Poland')
    ->setCountryCode('cz')
    ->setContactPerson('Jan Kowalski')
    ->setEmail('login@email.com')
    ->setPhone('48500600700');

$receiver = $courier->makeReceiver();
$receiver->setFirstName('Jan')
    ->setSurname('Nowak')
    ->setStreet('Vysoká')
    ->setHouseNumber('15')
    ->setApartmentNumber('1896')
    ->setCity('Ostrava')
    ->setZipCode('70200')
    ->setCountry('Czechy')
    ->setCountryCode('cz')
    ->setContactPerson('Jan Kowalski')
    ->setEmail('login@email.com')
    ->setPhone('48500600700');

$parcel = $courier->makeParcel();
$parcel->setWeight(2.5);

$shipment = $courier->makeShipment();
$shipment->setSender($sender)
    ->setReceiver($receiver)
    ->setParcel($parcel)
    ->setContent('Zawartość przesyłki');

try {
    $response = $courier->createShipment($shipment);
    if ($response->hasErrors()) {
        var_dump($response->getFirstError()->getMessage());
    } else {
        var_dump($response->referenceId); // Utworzony wewnetrzny idetyfikator zamowienia
        var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
    }
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
