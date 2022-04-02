<?php

namespace Digitalis\Util;

class Coder extends Utility {
	
	public static $inline_count = 0;
	
	public static function js ( $js, $handle = false, $params_name = false, $params_array = false ) {

		self::$inline_count++;
		if (!$handle) $handle = 'digitalis-inline-' . self::$inline_count;
		
		wp_register_script  	( $handle , false );
		wp_enqueue_script   	( $handle );
		wp_add_inline_script	( $handle , $js, 'before' );

		if ($params_name && $params_array) {
			wp_localize_script	( $handle, $params_name, $params_array );
		}
		
	}
	
	public static function src ( $url, $handle, $params_name = false, $params_array = false ) {
		
		//Head, defer and async options...
		
		wp_register_script  	( $handle , $url, [], DIGITALIS_VERSION );
		wp_enqueue_script   	( $handle );
	
	}
	
	public static function instantiate ( $object, $params_array, $instance ) {
		
		$js = "let " . strtolower($object) . $instance . " = new $object(" . json_encode($params_array) . ");";
		self::js($js);
		
	}
	
	public static function load ( $js_loader ) {
		
		$path = $js_loader->directory . $js_loader->file;

		if ($js_loader->inline) {
			
			if ($js_loader->relative) $path = DIGITALIS_PATH . $path;
			$handle = ($js_loader->handle ? $js_loader->handle : $path);
			
			if ($js_loader->instantiate) {
				
				self::js(self::get_file($path, "js", false), $handle, false, false);
				
			} else {
				
				self::js(self::get_file($path, "js", false), $handle, $js_loader->params_name, $js_loader->params);

			}

		} else {

			if ($js_loader->relative) $path = DIGITALIS_URI . $path;
			$handle = ($js_loader->handle ? $js_loader->handle : $js_loader->file);
			
			self::src($path, $handle, $js_loader->params_name, $js_loader->params);
			
		}
		
	}
	
}

?>