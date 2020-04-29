<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Util\Styler;

class Style extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		return $this->inline_field ($a['select'], $a['prop'], array($a['field'], $a['id']), $a['imp']);
		
	}
	
	protected function inline_field ($selector, $property, $field, $important = false) {
		
		if (is_array($field)) {
			$value = $this->field_value($field[0], false, $field[1]);
		} else {
			$value = $this->field_value($field);
		}
		
		
		if ($value) {
			Styler::inline($selector, $property, $value, $important);
		} else {
			return "Field '$field' not found.";
		}
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metastyle',
			'atts' => array(
				'field'		=> '',
				'prop'		=> '',
				'select'	=> '',
				'id'		=> '',
				'imp'		=> false,
			),
			'required' => array(
				'field',
				'prop',
				'select'
			),
		);
	}
	
}
