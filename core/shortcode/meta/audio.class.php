<?php

namespace Digitalis\Shortcode\Meta;

class Audio extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		return $this->html5_audio($this->field_value($a['field']), $a['styles']);	
	
	}
	
	protected function html5_audio($file, $styles = "") {
		
		//print_r($file);
		
		return "<audio controls style='$styles'><source src='$file' type='audio/mpeg'>Your browser does not support the audio element.</audio>";

		
	}
	
	function get_options() {
		return array(
			'tag' => 'metaaudio',
			'atts' => array(
				'field'		=> '',
				'styles'	=> '',
			),
			'required' => array(
				'field',
			),
		);
	}
	
}

