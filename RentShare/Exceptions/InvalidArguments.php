<?php

namespace RentShare\Exceptions;

class InvalidArguments extends APIException {
	public static $error_type = 'InvalidArguments';
	public static $status_code = 400;
}

?>