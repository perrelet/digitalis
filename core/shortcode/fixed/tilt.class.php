<?php

namespace Digitalis\Shortcode\Fixed;

use Digitalis\Struct\JS_Loader;
use Digitalis\Util\Coder;

class Tilt extends \Digitalis\Shortcode\Fixed {
	
	function shortcode($a, $content = null, $name = null) {
		
		$js_loader = new JS_Loader("vendor/js/", "vanilla-tilt.min.js");
		$js_loader->inline = $a['inline'];
		$this->load_js($js_loader);
		
		$settings = $a['settings'];//$this->create_array($a['settings'], true);
		$selector = $a['select'];
		$query = ($a['multiple'] ? "querySelectorAll" : "querySelector");
		
		$js = "VanillaTilt.init(document.$query('$selector'), $settings);";
		
		$handle = ($a['inline'] ? false : "tilt");
		Coder::js($js, $handle);

		return "";
	}
	
	function get_options() {
		return array(
			'tag' => 'tilt-js',
			'atts' => array(
				'select'	=> '',
				'settings'	=> '',
				'multiple'	=> true,
				'inline'	=> false
			),
			'required' => array(
				'select',
			),
		);
	}
	
}
