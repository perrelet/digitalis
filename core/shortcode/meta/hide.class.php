<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Util\Styler;

class Hide extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		//$a = $this->get_atts($atts);
		//if ($error = $this->invalidate($a)) return $error;
		
		if (!get_field($a['field'])) Styler::inline ($a['select'], 'display', 'none', $a['imp']);	
		
	
	}
	
	function get_options() {
		return array(
			'tag' => 'metahide',
			'atts' => array(
				'field'		=> '',
				'select'	=> '',
				'imp'		=> false,
			),
			'required' => array(
				'field',
				'select'
			),
		);
	}
	
}

