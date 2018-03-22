<?php
/**
 * RentShare/Utils.php
 *
 * @package default
 */


namespace RentShare;

abstract class Utils {

	/**
	 *
	 * @param unknown $array
	 * @param unknown $key_to_check
	 * @return unknown
	 */
	public static function get_val($array, $key_to_check) {
		if (isset($array[$key_to_check])) {
			return $array[$key_to_check];
		}
	}



	/**
	 *
	 * @param unknown $arr
	 * @return unknown
	 */
	public static function is_assoc($arr) {
		return is_array($arr) && array_keys($arr) !== range(0, count($arr) - 1);
	}



	/**
	 *
	 * @param unknown $parent
	 * @return unknown
	 */
	public static function getSubclassesOf($parent) {
		$result = array();
		foreach (get_declared_classes() as $class) {
			if (is_subclass_of($class, $parent))
				$result[] = $class;
		}
		return $result;
	}


}


?>
