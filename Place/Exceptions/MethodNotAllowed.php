<?php

namespace Place\Exceptions;

class MethodNotAllowed extends APIException {
	public static $status_code = 405;
}

?>