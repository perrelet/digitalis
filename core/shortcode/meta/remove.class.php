<?php

namespace Digitalis\Shortcode\Meta;

class Remove extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		if ($this->is_builder()) return $content;
		
		$value = get_field($a['field']);
		
		if ($value) {
			return $content;
		} else {
			return "";
		}
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metaremove',
			'atts' => array(
				'field'		=> '',
				'inv'		=> 'false'
			),
			'required' => array(
				'field',
			),
		);
	}
	
}
