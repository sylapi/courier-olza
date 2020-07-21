<?php

namespace Sylapi\Courier\Olza\Message;

/**
 * Class getLabel
 * @package Sylapi\Courier\Olza\Message
 */
class getLabel
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
     * @var array
     */
    private $formats = ['A4' => 'PDFA4', 'A6' => 'PDF'];

    /**
     * @param array $data
     * @return $this
     */
    public function prepareData($data=[]) {

        $this->data = [
            'token' => [
                'UserName' => $data['accessData']['login'],
                'Password' => $data['accessData']['password'],
            ],
            'request' => [
                'RequestedLabels' => [
                    'PackagesByCarrier' => [
                        'Carrier' => 'olza_logistic',
                        'PackageNo' => [
                            $data['custom_id']
                        ]
                    ],
                ],
                'MimeFormat' => (!empty($this->formats[$data['format']])) ? $this->formats[$data['format']] : 'PDF',
            ]
        ];

        return $this;
    }

    /**
     * @param $client
     */
    public function send($client) {

        try {

            $result = $client->GetLabel($this->data);

            if (isset($result->GetLabelResult->LabelData->Label->ParcelID)) {

                $this->response['return'] = [
                    'status' => $result->GetLabelResult->responseDescription.'',
                    'tracking_id' => '',
                    'label' => $result->GetLabelResult->LabelData->Label->MimeData,
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

    /**
     * @return |null
     */
    public function getResponse() {
        if ($this->isSuccess() == true) {
            return $this->response['return']['label'];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess() {
        if (isset($this->response['return']['status']) && $this->response['return']['status'] == 'Success') {
            return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getError() {
        if (!$this->isSuccess()) {
            $error = $this->response['code'].': '.$this->response['error'];

            return $error;
        }

        return null;
    }

    /**
     * @return int
     */
    public function getCode() {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}