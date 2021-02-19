# Courier-olza

## Init

```php
    /**
    * @return Sylapi\Courier\Courier
    */
    $courier = CourierFactory::create('Olza',[
        'login' => 'mylogin',
        'password' => 'mypassword',
        'sandbox' => true,
        'requestLanguage' => 'pl',
        'labelType' => 'A4',
        'speditionCode' => 'GLS',
        'shipmentType' => 'DIRECT'
    ]);

```

## CreateShipment

```php
    $sender = $courier->makeSender();
    $sender->setFullName('Nazwa Firmy/Nadawca')
        ->setStreet('Ulica')
        ->setHouseNumber('2a')
        ->setApartmentNumber('1')
        ->setCity('Miasto')
        ->setZipCode('66100')
        ->setCountry('Poland')
        ->setCountryCode('pl')
        ->setContactPerson('Jan Kowalski')
        ->setEmail('my@email.com')
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
    $parcel->setWeight(1.5);

    $shipment = $courier->makeShipment();
    $shipment->setSender($sender)
            ->setReceiver($receiver)
            ->setParcel($parcel)
            ->setContent('Zawartość przesyłki');

    try {
        $response = $courier->createShipment($shipment);
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump($response->referenceId); // Utworzony wewnetrzny idetyfikator zamowienia
            var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
        }

    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

## PostShipment

```php
    /**
     * Init Courier
     */
    $booking = $courier->makeBooking();
    $booking->setShipmentId('123456');
    try {
        $response = $courier->postShipment($booking);
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
            var_dump($response->trackingId); // Zewnetrzny idetyfikator sledzenia przesylki
            var_dump($response->trackingBarcode); // Zewnetrzny idetyfikator sledzenia przesylki
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

## GetStatus

```php
    /**
     * Init Courier
     */
    try {
        $response = $courier->getStatus('123456');
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump((string) $response);
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

## GetLabel

```php
    try {
        /**
         * Shipment musi zostac potwierdzony (postShipment) 
         * w przeciwnym wypadku otrzymamy błąd
         * o "Brak odpowiedniej przesyłki"
        */
        $response = $courier->getLabel('123456');
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump((string) $response);
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```