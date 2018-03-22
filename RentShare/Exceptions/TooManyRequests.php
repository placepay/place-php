<?php

namespace RentShare\Exceptions;

class TooManyRequests extends APIException {
	public static $status_code = 429;
}

?>