<?php

namespace Sylapi\Courier\Olza\Message;

class getTracking
{
    private $data;

    public function prepareData($data = [])
    {
        $this->data = [
            'RequestedLabels' => [$data['tracking_id']],
            'MimeFormat'      => 'None',
        ];

        return $this;
    }

    public function send($client)
    {
        try {
            $result = $client->GetLabel($this->data);

            if (isset($result->ParcelID)) {
                $this->response['return'] = [
                    'tracking_id' => $result->ParcelID,
                ];
            } else {
                $this->response['error'] = $result->responseDescription.'';
                $this->response['code'] = $result->responseCode.'';
            }
        } catch (\SoapFault $e) {
            $this->response['error'] = $e->faultactor.' | '.$e->faultstring;
            $this->response['code'] = $e->faultcode.'';
        }
    }

    public function getResponse()
    {
        if ($this->isSuccess() == true) {
            $package = $this->response['return'];
            $package['tracking_id'] = $package['ParcelID'];

            return $package;
        }

        return null;
    }

    public function isSuccess()
    {
        if (empty($this->response['error'])) {
            return true;
        }

        return false;
    }

    public function getError()
    {
        return (!empty($this->response['error'])) ? $this->response['error'].': '.$this->response['message'] : null;
    }

    public function getCode()
    {
        return (!empty($this->response['status'])) ? $this->response['status'] : 0;
    }
}
