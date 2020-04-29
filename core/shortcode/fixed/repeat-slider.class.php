<?php

namespace Digitalis\Shortcode\Fixed;

use Digitalis\Struct\JS_Loader;

class Repeat_Slider extends \Digitalis\Shortcode\Fixed {
	
	function shortcode($a, $content = null, $name = null) {
		
		$js_loader = new JS_Loader("assets/js/", "repeat-slider.min.js");
		$js_loader->params_name = "Repslider";
		$js_loader->params = $a;
		$js_loader->instantiate = true;
		$this->load_js($js_loader);
		
		return "";
	}
	
	function get_options() {
		return array(
			'tag' => 'repeat-slider',
			'atts' => array(
				'slider'	=> '#slider',
				'left'		=> '#slide-left',
				'right'		=> '#slide-right',
				'time'		=> 0,
			),
			'required' => array(
			),
		);
	}
	
}

/*repeater:
	align children: horizontally
	overflow: hidden
	
Children:
	min-width: 100%;
	transition
*/

