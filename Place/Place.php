<?php
/**
 * Place/Place.php
 *
 * @package default
 */


namespace Place;

class Place {
	public static $api_key;
	public static $api_url = 'https://api.placepay.com';

	public static $PROD_URL = 'https://api.placepay.com';
	public static $TEST_URL = 'https://test-api.placepay.com';

	private static $_default_client = null;

	const VERSION = '0.5.2';



	/**
	 *
	 * @return unknown
	 */
	public static function getDefaultClient() {
		if ( !self::$_default_client )
			self::$_default_client = new Client();
		return self::$_default_client;
	}


}


?>
