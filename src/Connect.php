<?php

namespace Sylapi\Courier\Olza;

abstract class Connect
{
    const API_LIVE = 'http://olzaapi.opennet.pl/api.asmx?wsdl';
    const API_SANDBOX = 'http://olzaapitest.pong.opennet.pl/api.asmx?wsdl';

    protected $api_uri;
    protected $session;
    protected $token;
    protected $login;
    protected $parameters;
    protected $error;
    protected $response;
    protected $code = '';
    protected $test = false;

    public function __construct() {
        $this->api_uri = self::API_LIVE;
    }

    protected function setLogin($login) {
        return $this->login = $login;
    }

    protected function setToken($token) {
        return $this->token = $token;
    }

    public function getApiUri() {
        return $this->api_uri;
    }

    public function sandbox() {
        return $this->api_uri = self::API_SANDBOX;
    }

    public function isSuccess() {
        return (empty($this->error)) ? true : false;
    }

    public function getError() {
        return $this->error;
    }

    protected function setError($value) {
        if (!empty($value)) {
            return $this->error[] = $value;
        }
    }

    protected function setCode($value) {
        return $this->code = $value;
    }

    public function getCode() {
        return $this->code;
    }

    protected function setResponse($value) {
        return $this->response = $value;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setSession($session) {
        $this->session = $session;
    }

    public function setUri($uri) {
        $this->test = true;
        $this->api_uri = $uri;
    }

    public function debug() {

        return [
            'success' => $this->isSuccess(),
            'code' => $this->getCode(),
            'error' => $this->getError(),
            'response' => $this->getResponse(),
        ];
    }
}