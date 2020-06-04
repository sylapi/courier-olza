<?php

namespace Sylapi\Courier\Olza\Message;

/**
 * Class createShipment.
 */
class createShipment
{
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $response;

    /**
     * @param array $data
     *
     * @return $this
     */
    public function prepareData($data = [])
    {
        $this->data = [
            'token' => [
                'UserName' => $data['accessData']['login'],
                'Password' => $data['accessData']['password'],
            ],
            'createShipmentRequest' => [
                'ServiceId' => $data['provider'],
                'ShipFrom'  => [
                    'PointId'         => '',
                    'PointType'       => 'WAREHOUSE',
                    'Email'           => $data['sender']['email'],
                    'Contact'         => $data['sender']['phone'],
                    'Name'            => $data['sender']['name'],
                    'Person'          => $data['sender']['name'],
                    'Address'         => $data['sender']['street'],
                    'City'            => $data['sender']['city'],
                    'PostCode'        => $data['sender']['postcode'],
                    'CountryCode'     => $data['sender']['country'],
                    'Nip'             => $data['sender']['nip'],
                    'IsPrivatePerson' => false,
                    'PostOfficeId'    => '',
                    'PickupPlaceId'   => '',
                ],
                'ShipTo' => [
                    'PointId'         => '',
                    'PointType'       => '',
                    'Email'           => $data['receiver']['email'],
                    'Contact'         => $data['receiver']['phone'],
                    'Name'            => $data['receiver']['name'],
                    'Person'          => $data['receiver']['name'],
                    'Address'         => $data['receiver']['street'],
                    'City'            => $data['receiver']['city'],
                    'PostCode'        => $data['receiver']['postcode'],
                    'CountryCode'     => $data['receiver']['country'],
                    'IsPrivatePerson' => false,
                    'PostOfficeId'    => '',
                ],
                'Parcels' => [
                    [
                        'Type'          => 'Package',
                        'IsNST'         => false,
                        'Weight'        => $data['options']['weight'],
                        'PickupPlaceId' => 1,
                    ],
                ],
                'ReferenceNumber'    => $data['options']['references'],
                'ContentDescription' => $data['options']['note'],
                'LabelFormat'        => 'PDF',
                'RebateCoupon'       => '',
                'MPK'                => '',
            ],
        ];

        if (in_array($data['provider'], [1])) {
            foreach ($this->data['createShipmentRequest']['Parcels'] as &$parcel) {
                $parcel['D'] = $data['options']['depth'];
                $parcel['W'] = $data['options']['width'];
                $parcel['S'] = $data['options']['height'];
            }
        }

        if ($data['saturday'] == true) {
            $this->data['AdditionalServices'][] = 'SOB';
        }
        if ($data['hour10'] == true) {
            $this->data['AdditionalServices'][] = 'DOR1000';
        }
        if ($data['hour12'] == true) {
            $this->data['AdditionalServices'][] = 'DOR1200';
        }

        if ($data['options']['cod'] == true) {
            $this->data['COD'] = [
                'Amount'       => $data['options']['amount'],
                'Currency'     => (!empty($data['options']['currency'])) ? $data['options']['currency'] : 'PLN',
                'RetAccountNo' => (!empty($data['options']['bank_number'])) ? $data['options']['bank_number'] : '',
            ];
        }

        if (!empty($data['options']['service'])) {
            $this->data['createShipmentRequest']['AdditionalServices']['Code'] = $data['options']['service'];
        }

        if ($this->data['createShipmentRequest']['ShipFrom']['CountryCode'] != $this->data['createShipmentRequest']['ShipTo']['CountryCode']) {
            $this->data['createShipmentRequest']['ShipFrom']['CountryCode'] = $this->data['createShipmentRequest']['ShipTo']['CountryCode'];
        }

        return $this;
    }

    /**
     * @param $client
     */
    public function send($client)
    {
        try {
            $this->data['ShipmentRequest'] = json_encode($this->data['ShipmentRequest']);

            $result = $client->CreateShipment($this->data);
            if (isset($result->CreateShipmentResult->PackageNo)) {
                $this->response['return'] = [
                    'status'      => $result->CreateShipmentResult->responseDescription.'',
                    'custom_id'   => $result->CreateShipmentResult->PackageNo.'',
                    'tracking_id' => '',
                    'price'       => 0,
                ];
            } else {
                $this->response['error'] = $result->CreateShipmentResult->responseDescription.'';
                $this->response['code'] = $result->CreateShipmentResult->responseCode.'';
            }
        } catch (\SoapFault $e) {
            $this->response['error'] = $e->faultactor.' | '.$e->faultstring;
            $this->response['code'] = $e->faultcode.'';
        }
    }

    /**
     * @return |null
     */
    public function getResponse()
    {
        if ($this->isSuccess() == true) {
            return $this->response['return'];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        if (isset($this->response['return']['status']) && $this->response['return']['status'] == 'Success') {
            return true;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        if (!empty($this->response['error'])) {
            $error = $this->response['code'].': '.$this->response['error'];

            return $error;
        }

        return null;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}
