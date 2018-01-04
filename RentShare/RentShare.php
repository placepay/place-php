<?php
/**
 * RentShare/RentShare.php
 *
 * @package default
 */


namespace RentShare;

class RentShare {
	public static $api_key;
	public static $api_url = 'https://api.rentshare.com';

	public static $PROD_URL = 'https://api.rentshare.com';
	public static $TEST_URL = 'https://staging-api.rentshare.com';

	private static $_default_client = null;

	const VERSION = '0.5.0';



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
