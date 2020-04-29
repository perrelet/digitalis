<?php

namespace Digitalis\Shortcode\Meta;

class Value extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		//$a = $this->get_atts($atts);
		//if ($error = $this->invalidate($a)) return $error;
		
		return $this->field_value($a['field'], false, $a['id']);	
	
	}
	
	function get_options() {
		return array(
			'tag' => 'metavalue',
			'atts' => array(
				'field'		=> '',
				'id'		=> '',
			),
			'required' => array(
				'field',
			),
		);
	}
	
}