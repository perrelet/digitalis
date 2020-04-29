<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Util\Styler;

class Style_Repeat extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		if(!have_rows($a['field'])) {
			
			return;
			
		} else {
			
			$i = 0;
			while (have_rows($a['field'])) {	
				the_row();
				$value = $this->field_value($a['subfield'], true);
				if (!($value == "")) {
					$selector = "." . $this->repeater_class($a['field'], $i) . " " . $a['select'];
					Styler::inline ($selector, $a['prop'], $value, $a['imp']);
				}
				$i++;

			}
			
		}
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metastyles',
			'atts' => array(
				'field'		=> '',
				'subfield'	=> '',
				'prop'		=> '',
				'select'	=> '',
				'imp'		=> false,
			),
			'required' => array(
				'field',
				'subfield',
				'prop'
			),
		);
	}
	
}