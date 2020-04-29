<?php

namespace Digitalis\Module\Debugger;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Field;

class Debugger extends Module {
	
	public function run () {
		
		$this->fields('add_options');
		add_action('admin_init' , [$this, 'add_cookie']);
	}
	
	public function add_options ($manager) {
		
		$manager->add_field(new Field("Display Errors for Administrators Only?", "debugger_show_admin", "checkbox", DIGITALIS_VALUE_TRUE));
		
		if ($this->get_option("debugger_show_admin")) $manager->add_action([$this, "render_module_tab"]);
		
	}
	
	public function render_module_tab () {
		
		echo 'Add the following to wp_config.php:<br>';
		echo '<pre>if ( isset( $_COOKIE["wp_debug"] ) && $_COOKIE["wp_debug"] === "true" ) {<br>';
		echo '	define( "WP_DEBUG", true );<br>';
		echo '} else {<br>';
		echo '	define( "WP_DEBUG", false );<br>';
		echo '}<br></pre>';
		
	}
	
	public function add_cookie () {
		
		if(current_user_can('administrator')) {
			setcookie( 'wp_debug', $this->get_option("debugger_show_admin"), time() + 86400, '/');
		}

	}
	
	
}

new Debugger();