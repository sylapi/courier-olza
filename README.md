# sylapi/courier-olza

[![StyleCI](https://github.styleci.io/repos/261400599/shield?branch=master)](https://github.styleci.io/repos/261400599)

## Installation

Courier to install, simply add it to your `composer.json` file:

```json
{
    "require": {
        "sylapi/courier-olza": "~1.0"
    }
}
```


## Shipping information:
```php

$courier = new Courier('Olza');

$courier->sandbox(true);
$courier->setToken('abcdef-123456'); // Token
$courier->setProvider(187); // Id dostawcy

$address = [
    'name' => 'Name Lastname',
    'company' => 'Company Name',
    'street' => 'Street 123/2A',
    'postcode' => '12-123',
    'city' => 'Warszawa',
    'country' => 'PL',
    'phone' => '602602602',
    'email' => 'name@example.com'
];

$courier->setSender($address);
$courier->setReceiver($address);

$courier->setOptions([
        'weight' => 3.00,
        'width' => 30.00,
        'height' => 50.00,
        'depth' => 10.00,
        'amount' => 2390.10,
        'currency' => 'PLN',
        'cod' => true,
        'references' => 'Order #4567',
        'note' => 'Description',
    ]);
```

## ID dostawców:

- 179 : GLS CZ BusinessParcel
- 180 : GLS SK BusinessParcel
- 181 : GLS PL BusinessParcel
- 182 : GLS CZ EuroBusinessParcel
- 183 : Česká pošta - Balík do ruky
- 186 : Slovenská pošta - Expres Kuriér
- 187 : DPD PL Classic
- 188 : Geis - Paletová přeprava
- 189 : PPL parcel CZ private
- 190 : Zásilkovna - Pickup point
- 191 : Zásilkovna - Hungarian Post
- 192 : Zásilkovna - FAN Romania
- 194 : Zásilkovna - DPD Hungary