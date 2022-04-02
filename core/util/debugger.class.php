<?php

namespace Digitalis\Util;

class Debugger extends Utility {
	
	public static $permission = null;
	
	public static function has_permission () {
		
		if (self::$permission === null) {
			
			self::$permission = current_user_can('administrator');
			
		}
		
		return self::$permission;
		
	}
	
	public static function printer ($content, $pre = true) {
		
		if (!self::has_permission()) return;
		
		if ($pre) echo "<pre>";
		print_r($content);
		if ($pre) echo "</pre>";
		
	}
	
	public static function console ($content, $label = false) {
		
		echo "<script>";
			if ($label) echo "console.debug('{$label}:');";
			echo "console.debug(" . json_encode($content) . ");";
		echo "</script>";
		
	}
	
	public static function logger ($content, $label = false) {
		
		if (!self::has_permission()) return;
		
		if ($label) error_log($label . ":");
		error_log(print_r($content, true));
		
	}
	
	
	
}

?>