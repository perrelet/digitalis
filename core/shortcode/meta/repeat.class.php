<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Util\Marker;

class Repeat extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		if ($this->is_builder()) return $content;
		
		$html = "";
		if(have_rows($a['field'])) {
			
			$wrap = ["", ""];
			
			if ($a['cols']) {
				if ($a['cols'] == "auto") {
					$rows = count(get_field($a['field']));
				} else {
					$rows = $a['cols'];
				}
				$wrap = Marker::get_flex_element($rows, $a['margin'], $a['min-width']);
			}
			
			return $this->loop_subfields($a['field'], $content, 1, $wrap);
			
		}
		
		return $html;
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metarepeat',
			'atts' => array(
				'field'		=> '',
				'cols'		=> false,
				'min-width'	=> 300,
				'margin'	=> 0,
			),
			'required' => array(
				'field',
			),
		);
	}
}