<?php

namespace Digitalis\Module;

use Digitalis\Admin\Option\Field;

class Module {
	
	protected $name;
	protected $class_unq;
	protected $class_qual;
	
	function __construct() {
		
		$this->class_qual 	= get_class($this);
		$this->class_unq 	= $this->get_unqualified_class($this->class_qual);
		$this->name 		= strtolower($this->class_unq);
		
		$this->run();
		
	}
	
	public function digitalis() {
		
		global $Digitalis;
		return $Digitalis;
		
	}

	public function fields (String $callback) {
		
		if (is_admin()) add_action('Digitalis\Options\Modules\Field', [$this, $callback], 10, 1);
		
	}
	
	public function tabs (String $callback) {
		
		if (is_admin()) add_action('Digitalis\Options\Modules\Tab', [$this, $callback], 10, 1);
		
	}
	
	public function get_unqualified_class ($qualified_class){
		if ($pos = strrpos($qualified_class, '\\')) return substr($qualified_class, $pos + 1);
		return $pos;
	}

	public function get_option( string $option, $default = false ) {
		
		return get_option($this->get_option_key($option), $default);
		
	}
	
	public function get_option_key (string $option) {
		
		return DIGITALIS_OPTION . $option;
		
	}
	
	public function get_user_meta( int $user_id, string $key = '', bool $single = false ) {
		
		return get_user_meta($user_id, DIGITALIS_USER_META . $key, $single);
		
	}
	
	public function update_user_meta( int $user_id, string $meta_key, $meta_value, $prev_value = '' ) {
		
		return update_user_meta( $user_id, DIGITALIS_USER_META . $meta_key, $meta_value, $prev_value );
		
	}
	
	public function enqueue_script (String $callback, $zone = false) {
		
		switch ($zone) {
			case "admin":
				add_action( 'admin_enqueue_scripts', [$this, $callback] );
				break;
			case "login":
				add_action( 'login_enqueue_scripts', [$this, $callback] );
				break;
			default:
				add_action( 'wp_enqueue_scripts', [$this, $callback] );
		}

	}
	
	public function get_handle ($unique = false) {
		
		return DIGITALIS_HANDLE . ($unique ? $this->name : DIGITALIS_MODULE_HANDLE);
		
	}
	
	public function get_asset_manager () {
		
		return $this->digitalis()->get_asset_manager();
		
	}
	
}