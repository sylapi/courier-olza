<?php

namespace Sylapi\Courier\Olza\Message;

class createShipment
{
    private $services = [
        'SAT' => 'SOB',
        'H10' => 'DOR1000',
        'H12' => 'DOR1200',
    ];
    private $data;
    private $response;

    public function prepareData($data = [])
    {
        $this->data = [
            'token' => [
                'UserName' => $data['accessData']['login'],
                'Password' => $data['accessData']['password'],
            ],
            'createShipmentRequest' => [
                'ServiceId' => $data['provider'],
                'ShipTo'    => [
                    'Email'           => $data['receiver']['email'],
                    'Contact'         => $data['receiver']['phone'],
                    'Name'            => $data['receiver']['name'],
                    'Person'          => $data['receiver']['name'],
                    'Address'         => $data['receiver']['street'],
                    'City'            => $data['receiver']['city'],
                    'PostCode'        => $data['receiver']['postcode'],
                    'CountryCode'     => $data['receiver']['country'],
                    'IsPrivatePerson' => true,
                    'PostOfficeId'    => '',
                ],
                'ShipFrom' => [
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
                    'PointType'       => '',
                ],
                'Parcels' => [
                    [
                        'Type'   => 'Package',
                        'IsNST'  => false,
                        'Weight' => $data['options']['weight'],
                    ],
                ],
                'ReferenceNumber'    => $data['options']['references'],
                'ContentDescription' => $data['options']['note'],
                'LabelFormat'        => 'None',
                'LoyaltyCardNo'      => '',
                'RebateCoupon'       => '',
                'MPK'                => '',
            ],
        ];

        if ($data['options']['cod'] == true) {
            $this->data['COD'] = [
                'Amount'       => $data['options']['amount'],
                'Currency'     => (!empty($data['options']['currency'])) ? $data['options']['currency'] : 'PLN',
                'RetAccountNo' => (!empty($data['options']['bank_number'])) ? $data['options']['bank_number'] : '',
            ];
        }

        if (!empty($data['options']['service'])) {
            $this->data['AdditionalServices']['Code'] = $data['options']['service'];
        }

        return $this;
    }

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
