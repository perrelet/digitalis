<?php

namespace Digitalis\Shortcode\Fixed;

use DOMDocument;

class AOS extends \Digitalis\Shortcode\Fixed {
	
	function shortcode($a, $content = null, $name = null) {
		
		$doc = new DOMDocument();
		$doc->loadXML($content);
		
		$doc->documentElement->setAttribute('data-aos', $a['ani']);
		
		if (!($a['offset'] == "")) 	$doc->documentElement->setAttribute('data-aos-offset', $a['offset']);
		if (!($a['delay'] == "")) 	$doc->documentElement->setAttribute('data-aos-delay', $a['delay']);
		if (!($a['duration'] == ""))$doc->documentElement->setAttribute('data-aos-duration', $a['duration']);
		if (!($a['easing'] == "")) 	$doc->documentElement->setAttribute('data-aos-easing', $a['easing']);
		if (!($a['mirror'] == "")) 	$doc->documentElement->setAttribute('data-aos-mirror', $a['mirror']);
		if (!($a['once'] == "")) 	$doc->documentElement->setAttribute('data-aos-once', $a['once']);
		if (!($a['anchor'] == "")) 	$doc->documentElement->setAttribute('data-aos-anchor-placement', $a['anchor']);
		
		return $doc->saveHTML();	
		
	}
	
	function get_options() {
		return array(
			'tag' => 'aos-custom',
			'atts' => array(
				'ani'	=> '',
				'offset'	=> '',
				'delay' 	=> '',
				'duration' 	=> '',
				'easing' 	=> '',
				'mirror' 	=> '',
				'once' 		=> '',
				'anchor' 	=> '',
			),
			'required' => array(
				'ani',
			),
		);
	}
	
}


