<?php

namespace Place\Exceptions;

class InvalidRequest extends APIException {
	public static $error_type = 'Error';
	public static $status_code = 400;
}

?>