<?php
namespace Sylapi\Courier\Olza\Message;

class createShipment
{
    private $data;
    private $response;

    public function prepareData($data=[]) {

        $this->data = [
            'ServiceId' => '',
            'ShipTo' => [
                'Email' => $data['receiver']['email'],
                'Contact' => $data['receiver']['phone'],
                'Name' => $data['receiver']['name'],
                'Person' => $data['receiver']['name'],
                'Address' => $data['receiver']['street'],
                'City' => $data['receiver']['city'],
                'PostCode' => $data['receiver']['postcode'],
                'CountryCode' => $data['receiver']['country'],
                'IsPrivatePerson' => true,
                'PostOfficeId' => ''
            ],
            'ShipFrom' => [
                'Email' => $data['sender']['email'],
                'Contact' => $data['sender']['phone'],
                'Name' => $data['sender']['name'],
                'Person' => $data['sender']['name'],
                'Address' => $data['sender']['street'],
                'City' => $data['sender']['city'],
                'PostCode' => $data['sender']['postcode'],
                'CountryCode' => $data['sender']['country'],
                'IsPrivatePerson' => true,
                'PostOfficeId' => ''
            ],
            'Parcels' => [
                'Type' => 'Package',
                'D' => $data['options']['depth'],
                'S' => $data['options']['width'],
                'W' => $data['options']['height'],
                'IsNST' => false,
                'Weight' => $data['options']['weight'],
            ],
            'ReferenceNumber' => $data['options']['references'],
            'ContentDescription' => $data['options']['note'],
            'InsuranceAmount' => $data['options']['amount'],
            'LabelFormat' => 'None',
            'LoyaltyCardNo' => '',
            'RebateCoupon' => '',
            'MPK' => ''
        ];

        if ($data['options']['cod'] == true) {

            $this->data['COD'] = [
                'Amount' => $data['options']['amount'],
                'Currency' => (!empty($data['options']['currency'])) ? $data['options']['currency'] : 'PLN',
                'RetAccountNo' => ''
            ];
        }

        /*
        if (!empty($data['options']['custom']['external_customer_id'])) {
            $this->data['AdditionalServices']['Code'] = $data['options']['custom']['external_customer_id'];
        }
        */

        return $this;
    }

    public function send($client) {

        try {

            $result = $client->CreateShipment($this->data);

            if (isset($result->PackageNo)) {

                $this->response['return'] = [
                    'tracking_id' => $result->PackageNo,
                ];
            }
            else {

                $this->response['error'] = $result->responseDescription.'';
                $this->response['code'] = $result->responseCode.'';
            }
        }
        catch (\SoapFault $e) {

            $this->response['error'] = $e->faultactor.' | '.$e->faultstring;
            $this->response['code'] = $e->faultcode.'';
        }
    }

    public function getResponse() {
        if (empty($this->response['error']) && isset($this->response)) {
            return $this->response;
        }
        return null;
    }

    public function isSuccess() {
        if (!isset($this->response['error'])) {
            return true;
        }
        return false;
    }

    public function getError() {
        if (!empty($this->response['error'])) {
            $error = $this->response['error'].': '.$this->response['message'];

            return $error;
        }

        return null;
    }

    public function getCode() {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}