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
 * GetLabel.
 */
try {
    $response = $courier->getLabel('123456');
    if ($response->hasErrors()) {
        var_dump($response->getFirstError()->getMessage());
    } else {
        var_dump((string) $response);
    }
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
