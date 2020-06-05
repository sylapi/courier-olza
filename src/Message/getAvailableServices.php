<?php

namespace Sylapi\Courier\Olza\Message;

class getAvailableServices
{
    private $data;
    private $response;

    public function prepareData($data = [])
    {
        $this->data = [
            'token' => [
                'UserName' => $data['accessData']['login'],
                'Password' => $data['accessData']['password'],
            ],
            'getAvailableServicesRequest' => [
                'ReadyDate' => date('Y-m-d'),
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
                'InsuranceAmount'    => $data['options']['amount'],
            ],
        ];

        if ($data['saturday'] == true) {
            $this->data['getAvailableServicesRequest']['AdditionalServices'][] = 'SOB';
        }
        if ($data['hour10'] == true) {
            $this->data['getAvailableServicesRequest']['AdditionalServices'][] = 'DOR1000';
        }
        if ($data['hour12'] == true) {
            $this->data['getAvailableServicesRequest']['AdditionalServices'][] = 'DOR1200';
        }

        if ($data['options']['cod'] == true) {
            $this->data['getAvailableServicesRequest']['COD'] = [
                'Amount'       => $data['options']['amount'],
                'Currency'     => (!empty($data['options']['currency'])) ? $data['options']['currency'] : 'PLN',
                'RetAccountNo' => (!empty($data['options']['bank_number'])) ? $data['options']['bank_number'] : '',
            ];
        }

        if (!empty($data['options']['service'])) {
            $this->data['getAvailableServicesRequest']['AdditionalServices']['Code'] = $data['options']['service'];
        }

        if ($this->data['getAvailableServicesRequest']['ShipFrom']['CountryCode'] != $this->data['createShipmentRequest']['ShipTo']['CountryCode']) {
            $this->data['getAvailableServicesRequest']['ShipFrom']['CountryCode'] = $this->data['createShipmentRequest']['ShipTo']['CountryCode'];
        }

        return $this;
    }

    public function send($client)
    {
        try {
            $this->data['getAvailableServicesRequest'] = json_encode($this->data['getAvailableServicesRequest']);

            $result = $client->CreateShipment($this->data);
            pr($result);
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

    public function getResponse()
    {
        if ($this->isSuccess() == true) {
            return $this->response['return'];
        }

        return null;
    }

    public function isSuccess()
    {
        if (isset($this->response['return']['status']) && $this->response['return']['status'] == 'Success') {
            return true;
        }

        return false;
    }

    public function getError()
    {
        if (!empty($this->response['error'])) {
            $error = $this->response['code'].': '.$this->response['error'];

            return $error;
        }

        return null;
    }

    public function getCode()
    {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}
