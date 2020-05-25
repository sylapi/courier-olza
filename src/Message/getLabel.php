<?php

namespace Sylapi\Courier\Olza\Message;

class getLabel
{
    private $data;
    private $response;
    private $formats = ['A4' => 'PDFA4', 'A6' => 'PDF'];

    public function prepareData($data = [])
    {
        $this->data = [
            'token' => [
                'UserName' => $data['accessData']['login'],
                'Password' => $data['accessData']['password'],
            ],
            'request' => [
                'RequestedLabels' => [
                    'PackagesByCarrier' => [

                        'Carrier'   => 'dpd',
                        'PackageNo' => [$data['custom_id']],

                    ],
                ],
                'MimeFormat' => (!empty($this->formats[$data['format']])) ? $this->formats[$data['format']] : 'PDF',
            ],
        ];

        return $this;
    }

    public function send($client)
    {
        try {
            pr($this->data);
            $result = $client->GetLabel($this->data);
            pr($result);
            if (isset($result->PackageNo)) {
                $this->response['return'] = [
                    'tracking_id' => $result->PackageNo,
                    'label'       => $result->MimeData,
                ];
            } else {
                $this->response['error'] = $result->responseDescription.'';
                $this->response['code'] = $result->responseCode.'';
            }
        } catch (\SoapFault $e) {
            pr($e);
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
