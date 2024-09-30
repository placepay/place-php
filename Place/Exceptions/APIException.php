<?php

namespace Place\Exceptions;

class APIException extends \Exception {
	public static $error_type = null;
	public static $status_code = null;

    public $request;
    public $response;

    public function withRequest($request) {
        $this->request = $request;
        return $this;
    }

    public function withResponse($response) {
        $this->response = $response;
        return $this;
    }

    public function __toString()
    {
        return parent::__toString()
            . PHP_EOL
            . 'Request:'
            . PHP_EOL
            . $this->request
            . PHP_EOL
            . PHP_EOL
            . 'Response:'
            . PHP_EOL
            . $this->response
            . PHP_EOL;
    }
}
