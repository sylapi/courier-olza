# Courier-olza

![StyleCI](https://github.styleci.io/repos/261400599/shield?style=flat&style=flat) ![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat) [![Build](https://github.com/sylapi/courier-olza/actions/workflows/build.yaml/badge.svg?event=push)](https://github.com/sylapi/courier-olza/actions/workflows/build.yaml) [![codecov.io](https://codecov.io/github/sylapi/courier-olza/coverage.svg)](https://codecov.io/github/sylapi/courier-olza/)

## Methody

### Init

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
        'shipmentType' => 'DIRECT',
        // 'cod' => [
        //     'codAmount' => 50,
        //     'codReference' => '0123456789'
        // ],
        // 'services' => [
            // 'T12' => true,
            // 'XS' => true,
            // 'S12' => true,
            // 'S10' => true,
            // 'SAT' => true,
            // 'PALLET' => true,
            // 'CSP' => true,
            // 'SM2' => '+420123456789',
            // 'INS' => 5000
        // ]
    ]);

```

### CreateShipment

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

### PostShipment

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

### GetStatus

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

### GetLabel

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

## ENUMS

### ShipmentType

| WARTOŚĆ | OPIS |
| ------ | ------ |
| DIRECT | Bezpośrednia przesyłka |
| WAREHOUSE | Z magazynu OlzaLogistic |

### SpeditionCode

| WARTOŚĆ | OPIS |
| ------ | ------ |
| GLS | GLS |
| CP | Poczta Czeska - list polecony |
| CP-RR | Poczta Czeska - list polecony |
| CP-NP | Poczta Czeska - paczka na pocztę |
| SP | Poczta Słowacka - kurier ekspresowy |
| DPD | DPD |
| PPL | PPL - paleta |
| ZAS | Zásilkovna - miejsce odbioru |
| ZAS-P | Zásilkovna - przesyłka pocztowa na adres |
| ZAS-K | Zásilkovna - przesyłka kurierska na adres |
| ZAS-D | Zásilkovna - na adres DPD |
| ZAS-C | Zásilkovna - Cargus |
| ZAS-B | Zásilkovna - best delivery |
| ZAS-COL | Coletaria - pick-up point |
| GEIS-P | Geis - przesyłka na palecie |
| BMCG-IPK | InPost Kuriér |
| BMCG-IPKP | InPost - Paczkomaty |
| BMCG-DHL | DHL |
| BMCG-PPK | Pocztex Kurier 48 |
| BMCG-PPE | Pocztex Ekspres 24 |
| BMCG-UC | Cargus |
| BMCG-HUP | Maďarská Pošta - business parcel |
| BMCG-FAN | FAN Courier - home delivery |
| BMCG-INT |  WE|DO (In Time) |
| BMCG-INT-PP | WE|DO (In Time) - výdejní |
| ZAS-ECONT-HD | Econt - home delivery |
| ZAS-ECONT-PP | Econt - pick-up point |
| ZAS-ACS-HD | ACS - home delivery |
| ZAS-ACS-PP | ACS - pick-up point |
| ZAS-SPEEDY-PP | Speedy - pick-up point |
| ZAS-SPEEDY-HD | Speedy - home delivery |


## Komendy

| KOMENDA | OPIS |
| ------ | ------ |
| composer tests | Testy |
| composer phpstan |  PHPStan |
| composer coverage | PHPUnit Coverage |
| composer coverage-html | PHPUnit Coverage HTML (DIR: ./coverage/) |
