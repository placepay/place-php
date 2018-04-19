<?php

namespace Place\Exceptions;

class APIException extends \Exception {
	public static $error_type = null;
	public static $status_code = null;
}

?>