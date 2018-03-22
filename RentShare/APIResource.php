<?php
/**
 * RentShare/APIResource.php
 *
 * @package default
 */


namespace RentShare;

/**
 * Class APIResource
 *
 * @package RentShare
 */
class APIResource {
	public static $resource;
	public static $_object_index = array();



	/**
	 *
	 */
	public static function new($obj, $client=null) {
		$class = get_called_class();
		if ( isset($obj['id']) && isset(self::$_object_index[$obj['id']]) )
			return self::$_object_index[$obj['id']];
		return new $class($obj, $client);
	}





	/**
	 *
	 * @param unknown $obj
	 * @param unknown $client (optional)
	 */
	function __construct($obj, $client=null) {
		$this->_client = isset($client) ? $client : RentShare::getDefaultClient();
		$this->_set_obj($obj);
	}



	/**
	 *
	 * @param unknown $obj
	 */
	private function _set_obj($obj) {
		$this->_obj = $obj;
		$this->_obj = $this->_conv_object($this->_obj);
		if ( isset($obj['id']) )
			self::$_object_index[$obj['id']] = $this;
	}



	/**
	 *
	 * @param unknown $obj
	 * @param unknown $inverse (optional)
	 * @return unknown
	 */
	private function _conv_object($obj, $inverse=false) {
		foreach ($obj as $key => $val) {
			if ( $inverse ) {
				if ($val instanceof self) {
					$val = $obj[$key] = $val->_obj;
				}
			}
			else if ( Utils::is_assoc( $val ) && isset($val['object']) ) {
				foreach ( Utils::getSubclassesOf( APIResource::class ) as $resource ) {
					if ( $val['object'] != $resource::$object_type )
						continue;
					$val = $obj[$key] = $resource::new($val, $this->_client);
					break;
				}


			}
			if ( is_array($val) )
				$obj[$key] = $this->_conv_object($val, $inverse);
		}
		return $obj;
	}



	/**
	 *
	 * @param unknown $name
	 * @return unknown
	 */
	public function __get($name) {
		if (isset($this->_obj[$name]))
			return $this->_obj[$name];
		else
			throw new \Exception("$name does not exists");
	}


	/**
	 *
	 * @return unknown
	 */
	public function json() {
		return json_encode($this->_conv_object($this->_obj, true), JSON_PRETTY_PRINT);
	}


	/**
	 *
	 * @param unknown $method
	 * @param unknown $params
	 * @return unknown
	 */
	public static function request($method, $params) {
		$class  = get_called_class();
		$path   = isset($params['path'])   ? $params['path']   : $class::$resource;
		$client = isset($params['client']) ? $params['client'] : RentShare::getDefaultClient();
		if ( isset($params['id']) )
			$path = join('/', array($path, $params['id']));
		$url = join('/', array($client->api_url, trim($path, '/')));

		if ( isset($params['params']) )
			$url .= '?' . http_build_query($params['params'], '', '&');

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_USERPWD, $client->api_key . ":");
		curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

		if ( isset($params['json']) ) {
			$data = json_encode($params['json']);
			curl_setopt($request, CURLOPT_POSTFIELDS, $data);
			curl_setopt($request, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data)
				));
		}

		$response    = curl_exec($request);
		$status_code = curl_getinfo($request, CURLINFO_HTTP_CODE);
		curl_close($request);

		$obj = json_decode($response, true);

		if ( $obj === null ) {
			if ( $status_code == 500 )
				throw new InternalError();
			throw new InvalidResponse();
		}

		if ( !Utils::is_assoc($obj) )
			throw new InvalidResponse();

		if ( !isset($obj['object']) )
			throw new InvalidResponse('Response missing "object" attribute');
		$object_type = $obj['object'];

		if ($status_code != 200) {
			if ( $object_type != 'error' )
				throw new InvalidResponse('Expected error object');
			foreach ( Utils::getSubclassesOf( APIException::class ) as $exc ) {
				if ( $exc::$status_code != $status_code )
					continue;
				if ( $exc::$error_type && $exc::$error_type != Utils::$get_val( $obj, 'error_type') )
					continue;
				throw new $exc(Utils::get_val($obj, 'error_description'));
			}


			throw new APIException(Utils::get_val($obj, 'error_description'));
		}

		if ( $object_type == 'list' ) {
			$objects = array();
			foreach ( $obj['values'] as $o )
				array_push( $objects, $class::new($o, $client) );
			return $objects;
		}

		return $class::new($obj, $client);
	}



	/**
	 *
	 * @param unknown $updates
	 */
	public function update($updates) {
		self::request('PUT', array( 'id'=>$this->id, 'json'=>$updates));
	}



	/**
	 *
	 */
	public function delete() {
		self::request('DELETE', array( 'id'=>$this->id ));
	}



	/**
	 *
	 * @param unknown $id
	 * @param unknown $update (optional)
	 * @return unknown
	 */
	public static function get($id, $update=null) {
		if ( $update )
			return self::request('POST', array('id'=>$id, 'json'=>$update));
		return self::request('GET', array('id'=>$id));
	}


	/**
	 *
	 * @param unknown $filter_by (optional)
	 * @return unknown
	 */
	public static function select($filter_by=null) {
		$update_all = Utils::get_val($filter_by, 'update_all');
		if ( $update_all ) {
			unset($filter_by['update_all']);
			return self::request('POST', array('params'=>$filter_by, 'json'=>$update_all));
		}

		$delete_all = Utils::get_val($filter_by, 'delete_all');
		if ( $delete_all ) {
			unset($filter_by['delete_all']);
			return self::request('DELETE', array('params'=>$filter_by));
		}

		return self::request('GET', array('params'=>$filter_by));
	}



	/**
	 *
	 * @param unknown $obj
	 * @return unknown
	 */
	public static function create($obj) {
		if ( !Utils::is_assoc( $obj ) )
			$obj = array("object"=>"list", "values"->$obj);

		$obj = $this->_conv_object($obj, true);
		return self::request('POST', array('json'=>$obj));
	}


}
