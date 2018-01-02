<?php
/**
 * RentShare/Client.php
 *
 * @package default
 */


namespace RentShare;

class Client {
	public $api_key;
	public $api_url;





	/**
	 *
	 * @param unknown $api_key (optional)
	 * @param unknown $api_url (optional)
	 */
	function __construct($api_key=null, $api_url=null) {
		$this->api_key = $api_key ? $api_key : RentShare::$api_key;
		$this->api_url = $api_url ? $api_url : RentShare::$api_url;
	}


}


?>
