<?php

namespace Digitalis\Module\Login_Page;

use Digitalis\Admin\Option\Field;
use Digitalis\Util\Styler;
use Digitalis\Module\Module;

class Login_Page extends Module {
	
	function run () {
		
		$this->fields('add_options');
		$this->enqueue_script('style', 'login');
		
		add_filter( 'login_headerurl', [$this, 'logo_url'] );
		
	}
	
	function add_options ($manager) {
		
		$manager->add_field(new Field("Login Logo URL", "login_logo_url", "text", ""));
		$manager->add_field(new Field("Login Logo Height", "login_logo_height", "text", "100px"));
		
	}
	
	function style () {

		$data = [
			"#login h1 a, .login h1 a" => [
				"background-image" 	=> "url(" . $this->get_option('login_logo_url') . ")",
				"background-size" 	=> "contain",
				"width" 			=> "100%",
				"height" 			=> $this->get_option('login_logo_height'),
			],
		];
		
		$handle = $this->get_handle();
		
		wp_register_style 	($handle, false, [], DIGITALIS_VERSION);
		wp_enqueue_style 	($handle);
		wp_add_inline_style ($handle, Styler::build_block($data, false));
		
	}
	
	function logo_url () {
		return home_url();
	}
	
}

new Login_Page();