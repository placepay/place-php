<?php

namespace Place\Exceptions;

class TooManyRequests extends APIException {
	public static $status_code = 429;
}

?>