<?php

namespace RentShare;

class TooManyRequests extends APIException {
	public static $status_code = 429;
}

?>