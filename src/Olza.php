<?php
namespace Sylapi\Courier\Olza;

use Sylapi\Courier\Olza\Message\createShipment;
use Sylapi\Courier\Olza\Message\getLabel;
use Sylapi\Courier\Olza\Message\getTracking;

class Olza extends Connect
{
    protected $session;

    public function initialize($parameters) {

        $this->parameters = $parameters;

        if (!empty($parameters['accessData'])) {

            $this->setToken($parameters['accessData']['token']);
            $this->setPassword($parameters['accessData']['password']);
        }
        else {
            $this->setError('Access Data is empty');
        }
    }

    public function login() {

        if (empty($this->client)) {

            $options['token'] = $this->token;

            $this->client = new \SoapClient($this->getApiUri(), $options);

            $this->client->soap_defencoding = 'UTF-8';
            $this->client->decode_utf8 = true;
        }

        return false;
    }

    public function ValidateData() {

        $this->setResponse(['result' => true]);
    }


    public function CreatePackage() {

        $this->login();

        $createShipment = new createShipment();
        $createShipment->prepareData($this->parameters)->call($this->client);

        $response = $createShipment->getResponse();

        $this->setResponse($response);
        $this->setError($createShipment->getError());
    }

    public function GetPackage() {

        $this->login();

        if (empty($this->parameters['custom_id'])) {

            $getTracking = new getTracking();
            $getTracking->prepareData($this->parameters)->call($this->client);

            $this->setResponse($getTracking->getResponse());
            $this->setError($getTracking->getError());
        }
    }

    public function CheckPrice() {

        $response = (isset($this->parameters['options']['custom']['parcel_cost'])) ? $this->parameters['options']['custom']['parcel_cost'] : 0;
        $this->setResponse($response);
    }

    public function GetLabel() {

        $this->login();

        if (empty($this->parameters['custom_id'])) {

            $getLabel = new getLabel();
            $getLabel->prepareData($this->parameters)->call($this->client);

            $this->setResponse($getLabel->getResponse());
            $this->setError($getLabel->getError());
        }
    }
}