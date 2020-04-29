<?php

namespace Digitalis\Util;

class Styler extends Utility {
	
	public static function css ( $css ) {
		
		//Issue: Invalid markup (<script></script> in body)
		//See notes.txt for info.
		
/* 		if ($enqueue) {

			$handle = 'digitalis-inline-css';
		
			wp_register_style  ( $handle , false );
			wp_enqueue_style   ( $handle );
			wp_add_inline_style( $handle , $css );			
			
		} else { */
			
		echo "\r\n<style>$css</style>\r\n";	

		//}

	}
	
	/*
	$data = array or selector
	
	$data = [
		"selector1" => [
			"prop1" => "value1",
			"prop2" => "value2",
		],
	];
	*/
	
	public static function inline ($data, $property = false, $value = false, $important = false) {
		
		if (is_array($data)) {
			$css = self::build_block($data, true);
		} else {
			$css = self::build_command($data, $property, $value, $important, true);
		}
		
		self::css($css);
		
	}
	
	public static function inline_file ( $path, $localize = true) {
		
		self::css(self::get_file($path, "css", $localize));
		
	}
	
	public static function build_block ($data, $clean = false) {
		
		$css = "";
		
		foreach ($data as $selector => $props) {
			$css .= $selector . " {" ;
			foreach ($props as $property => $value ) {
				
				$v = ($clean ? self::clean_css_value($property, $value) : $value);
				$css .= "$property: $v;";
				
			}
			$css .= "}" ;
		}
		
		return $css;
		
	}
	
	public static function build_command ($selector, $property, $value, $important = false, $clean = false) {
		
		$v = ($clean ? self::clean_css_value($property, $value) : $value);
		$i = ($important ? " !important" : "");
		
		return "$selector { $property: $v$i; }";
		
	}
	
	public static function clean_css_value($property, $value) {
		
		switch ($property) {
			case "background-image":
				if (!(substr($value, 0, 3) == "url")) {
					$value = "url('$value')";
				}	
				break;
		}
		
		return $value;
	}
	
}

?>