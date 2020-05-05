<?php
namespace Sylapi\Courier\Olza\Message;

class getLabel
{
    private $data;
    private $formats = ['A4' => 'PDFA4', 'A6' => 'PDF'];

    public function prepareData($data=[]) {

        $this->data = [
            'RequestedLabels' => [$data['tracking_id']],
            'MimeFormat' => (!empty($this->formats[$data['format']])) ? $this->formats[$data['format']] : 'PDF',
        ];

        return $this;
    }

    public function send($client) {

        try {

            $result = $client->GetLabel($this->data);

            if (isset($result->PackageNo)) {

                $this->response['return'] = [
                    'tracking_id' => $result->PackageNo,
                    'label' => $result->MimeData
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
        if ($this->isSuccess() == true) {
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
        return (!empty($this->response['error'])) ? $this->response['error'].': '.$this->response['message'] : null;
    }

    public function getCode() {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}