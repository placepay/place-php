<?php

namespace RentShare;

class APIException extends \Exception {
	public static $error_type = null;
	public static $status_code = null;
}

?>