<?php

namespace Digitalis\Shortcode\Dynamic;

use Digitalis\Util\Media;

class SVG extends \Digitalis\Shortcode\Dynamic {
	
	function shortcode($a, $content = null, $name = null) {
		
		return Media::SVG($a['url'], $a['inline'], $a['id'], $a['class'], $a['style']);
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metasvg',
			'atts' => array(
				'url'			=> '!svg',
				'inline'		=> false,
				'id'			=> '',
				'class'			=> '',
				'style'			=> '',
			),
			'required' => array(
			),
			'dynamic' => array(
				'url',
			),
		);
	}
	
}


