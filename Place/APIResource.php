<?php
/**
 * Place/APIResource.php
 *
 * @package default
 */


namespace Place;

/**
 * Class APIResource
 *
 * @package Place
 */
class APIResource {
	public static $resource;
	public static $_object_index = array();



	/**
	 *
	 */
	public static function new($obj, $client=null) {
		$class = get_called_class();
		if ( isset($obj['id']) && isset(self::$_object_index[$obj['id']]) ) {
			self::$_object_index[$obj['id']]->_set_obj($obj);
			return self::$_object_index[$obj['id']];
		}
		return new $class($obj, $client);
	}





	/**
	 *
	 * @param unknown $obj
	 * @param unknown $client (optional)
	 */
	function __construct($obj, $client=null) {
		$this->_client = isset($client) ? $client : Place::getDefaultClient();
		$this->_set_obj($obj);
	}



	/**
	 *
	 * @param unknown $obj
	 */
	private function _set_obj($obj) {
		$this->_obj = $obj;
		$this->_obj = self::_conv_object($this->_obj, $this->_client);
		if ( isset($obj['id']) )
			self::$_object_index[$obj['id']] = $this;
	}



	/**
	 *
	 * @param unknown $obj
	 * @param unknown $inverse (optional)
	 * @return unknown
	 */
	private static function _conv_object($obj, $client=null, $inverse=false) {
		$new_obj = array();
		foreach ($obj as $key => $val) {
			$new_obj[$key] = $obj[$key];
			if ( $inverse ) {
				if ($val instanceof self) {
					$val = $new_obj[$key] = $val->_obj;
				}
			}
			else if ( Utils::is_assoc( $val ) && isset($val['object']) ) {
				foreach ( Utils::getSubclassesOf( APIResource::class ) as $resource ) {
					if ( $val['object'] != $resource::$object_type )
						continue;
					$val = $new_obj[$key] = $resource::new($val, $client);
					break;
				}


			}
			if ( is_array($val) )
				$new_obj[$key] = self::_conv_object($val, $client, $inverse);
		}
		return $new_obj;
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
		return json_encode(self::_conv_object($this->_obj, $this->_client, true), JSON_PRETTY_PRINT);
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
		$client = isset($params['client']) ? $params['client'] : Place::getDefaultClient();
		if ( isset($params['id']) )
			$path = join('/', array($path, $params['id']));
		$url = join('/', array($client->api_url, trim($path, '/')));

		if ( isset($params['params']) )
			$url .= '?' . http_build_query($params['params'], '', '&');

		$request = curl_init($url);
		curl_setopt($request, CURLOPT_USERPWD, $client->api_key . ":");
		curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($request, CURLINFO_HEADER_OUT, true);
        curl_setopt($request, CURLOPT_HEADER, true);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($request, CURLOPT_HTTPHEADER, array(
				'X-API-Version: v2.5'
			));

        $data = '';
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

        list ($resHeaders, $response) = explode("\r\n\r\n", $response, 2);

        $httpRequest = curl_getinfo($request, CURLINFO_HEADER_OUT)
            .$data;

        curl_close($request);
        
        $httpResponse = $resHeaders
            . "\r\n\r\n"
            . $response;

		$obj = json_decode($response, true);

		if ( $obj === null ) {
			if ( $status_code == 500 )
				throw (new Exceptions\InternalError(json_last_error_msg()))
                    ->withRequest($httpRequest)
                    ->withResponse($httpResponse);
			throw (new Exceptions\InvalidResponse(json_last_error_msg()))
                ->withRequest($httpRequest)
                ->withResponse($httpResponse);
		}

		if ( !Utils::is_assoc($obj) )
			throw (new Exceptions\InvalidResponse())
                ->withRequest($httpRequest)
                ->withResponse($httpResponse);

		if ( !isset($obj['object']) )
			throw (new Exceptions\InvalidResponse('Response missing "object" attribute'))
                ->withRequest($httpRequest)
                ->withResponse($httpResponse);

		$object_type = $obj['object'];

		if ($status_code != 200) {
			if ( $object_type != 'error' )
				throw (new Exceptions\InvalidResponse('Expected error object'))
                    ->withRequest($httpRequest)
                    ->withResponse($httpResponse);
			foreach ( Utils::getSubclassesOf( Exceptions\APIException::class ) as $exc ) {
				if ( $exc::$status_code != $status_code )
					continue;
				if ( $exc::$error_type && $exc::$error_type != Utils::get_val( $obj, 'error_type') )
					continue;
				throw new $exc(Utils::get_val($obj, 'error_description'));
			}


			throw (new Exceptions\APIException(Utils::get_val($obj, 'error_description')))
                ->withRequest($httpRequest)
                ->withResponse($httpResponse);
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
			return self::request('PUT', array('id'=>$id, 'json'=>$update));
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
			return self::request('PUT', array('params'=>$filter_by, 'json'=>$update_all));
		}

		$delete_all = Utils::get_val($filter_by, 'delete_all');
		if ( $delete_all ) {
			unset($filter_by['delete_all']);
			return self::request('DELETE', array('params'=>$filter_by));
		}

		return self::request('GET', array('params'=>$filter_by));
	}


	public static function update_all($updates, $params=null) {
		foreach ($updates as $key => $update ) {
			$update[1]["id"] = $update[0]->id;
			$updates[$key] = $update[1];
		}
		return self::request('PUT',
							array( 'json'=>array("object"=>"list", "values"=>$updates), 'params'=>$params) );
	}


	/**
	 *
	 * @param unknown $obj
	 * @return unknown
	 */
	public static function create($obj) {
		if ( !Utils::is_assoc( $obj ) )
			$obj = array("object"=>"list", "values"=>$obj);

		$obj = self::_conv_object($obj, null, true);
		return self::request('POST', array('json'=>$obj));
	}


}
