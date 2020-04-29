<?php

namespace Digitalis\Shortcode\Dynamic;

use Digitalis\Struct\JS_Loader;
use Digitalis\Util\Media;
use Digitalis\Util\Coder;

class Vivus extends \Digitalis\Shortcode\Dynamic {
	
	function shortcode($a, $content = null, $name = null) {
		
		$js_loader = new JS_Loader("vendor/js/", "vivus.min.js");
		$js_loader->inline = $a['inline'];
		$this->load_js($js_loader);
		
		$id = $a['id'];
		$callback = $a['callback'];
		
		$settings = $a['settings'];
		$style = $a['style'];
		
		if ($a['hide']) {
			//$settings .= ", onReady: function (v) { document.getElementById('$id').style.opacity = '1'; }";
			$style .= "opacity: 0;";
		}

		$html = "";
		if ($a['url']) $html = Media::SVG($a['url'], false, $id, false, $style);
		
		$js = "vivus_options = $settings;";
		if ($a['hide']) $js .= "vivus_options.onReady = function (v) { v.parentEl.style.opacity = '1'; };";
		if ($callback) {
			$js .= "new Vivus('$id', vivus_options , $callback);";
		} else {
			$js .= "new Vivus('$id', vivus_options);";
		}
		
		$handle = ($a['inline'] ? false : "vivus");
		Coder::js($js, $handle);

		return $html;
		
	}
	
	function get_options() {
		return array(
			'tag' => 'vivus',
			'atts' => array(
				'id'		=> '',
				'settings'	=> '',
				'callback'	=> '',
				'hide'		=> true,
				'url'		=> '',
				'inline'	=> false,
				'style'		=> '',
			),
			'required' => array(
				'id',
			),
			'dynamic' => array(
				'url',
			),
		);
	}
	
}

