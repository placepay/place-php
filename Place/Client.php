<?php
/**
 * Place/Client.php
 *
 * @package default
 */


namespace Place;

class Client {
	private $_api_key;
	private $_api_url;





	/**
	 *
	 * @param unknown $api_key (optional)
	 * @param unknown $api_url (optional)
	 */
	function __construct($api_key=null, $api_url=null) {
		$this->_api_key = $api_key;
		$this->_api_url = $api_url;
	}

	function __get($property) {
		if (property_exists($this, $property))
			return $this->$property;
		if ($property == "api_key") {
			if ($this->_api_key)
				return $this->_api_key;
			return Place::$api_key;
		}
		if ($property == "api_url") {
			if ($this->_api_url)
				return $this->_api_url;
			if ($this->api_key && substr( $this->api_key, 0, 5 ) == 'test_' && Place::$api_url == Place::$PROD_URL)
				return Place::$TEST_URL;
			return Place::$api_url;
		}
	}
}


?>
