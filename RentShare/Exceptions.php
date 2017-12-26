<?php
namespace RentShare;

class APIException extends \Exception {
	public static $error_type = null;
	public static $status_code = null;
}


class InvalidArguments extends APIException {
    public static $error_type = 'InvalidArguments';
    public static $status_code = 400;
}


class InvalidRequest extends APIException {
    public static $error_type = 'Error';
    public static $status_code = 400;
}


class Unauthorized extends APIException {
  	public static $status_code = 401;
}

class Forbidden extends APIException {
    public static $status_code = 403;
}


class NotFound extends APIException {
    public static $status_code = 404;
}


class MethodNotAllowed extends APIException {
    public static $status_code = 405;
}


class TooManyRequests extends APIException {
    public static $status_code = 429;
}


class InternalError extends APIException {
    public static $status_code = 500;
}

class InvalidResponse extends APIException {}
?>