<?php
namespace RentShare;

abstract class Util
{
	public static function get_val($array, $key_to_check){
	    if(isset($array[$key_to_check])) {
	        return $array[$key_to_check];
	    }
	}
	
	public static function is_assoc($arr){
		return is_array($arr) && array_keys($arr) !== range(0, count($arr) - 1);
	}
	
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