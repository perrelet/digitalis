<?php

namespace Digitalis\Shortcode\Meta;

class Hyperlink extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		if ($this->is_builder()) return $content;
		
		$url = $this->field_value($a['field'], false, $a['id']);
		$target = ($a['new'] ? "target='_blank'" : "");
		
		return "<a href='$url' $target>$content</a>";
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metalink',
			'atts' => array(
				'field'		=> '',
				'id'		=> '',
				'new'		=> false,
			),
			'required' => array(
				'field',
			),
		);
	}
}