<?php

namespace Digitalis\Shortcode\Dynamic;

use Digitalis\Struct\JS_Loader;
use Digitalis\Util\Coder;

class Canvas extends \Digitalis\Shortcode\Dynamic {
	
	function shortcode($a, $content = null, $name = null) {
		
		$id = ($a['id'] ? $a['id'] : "canvas" . $this->get_instance());
		
		$js = "";
		
		switch ($a['scene']) {
			
			case "particles":
				
				$dir = "assets/canvas/particles/";
				
				$js_loader = new JS_Loader($dir, "particles.min.js");
				$js_loader->inline = false;
				$this->load_js($js_loader);
				
				$js = $this->load_preset("assets/canvas/particles/", $a['preset'], $a['settings']);

				$js .= "particlesJS('$id', canvas_settings)";
				
				break;
				
			default:
				return "Invalid canvas scene '" . $a['scene'] . "'";
				
		}
		
		//if ($a['pause']) $this->load_js(new Digitalis_JS_Loader("assets/js/", "debounce.min.js"));
		
		Coder::js($js, "canvas");
		
		return "<div id='$id' style='width:100%; height: 100%;'></div>";
		
	}
	
	function get_options() {
		return array(
			'tag' => 'canvas',
			'atts' => array(
				'scene'		=> 'particles',
				'id'		=> false,
				'settings'	=> '',
				'preset'	=> false,
				//'pause'		=> true,
			),
			'required' => array(
			),
			'dynamic' => array(
				'scene',
				'settings',
			),
		);
	}
	
	private function load_preset($directory, $preset, $user_settings) {
		
		$js = "";	
		$settings = "{}";
		
		if ($preset) {
			
			$preset_url = DIGITALIS_PATH . $directory . "presets/" . $preset . ".js";
			$settings = @file_get_contents($preset_url);
			
			if ($settings) {
				
				if ($user_settings) {
				
					$this->load_js(new JS_Loader("assets/js/", "extend.min.js"));
					$settings = "extend(true, $settings, $user_settings);";
				
				}
				
			} else {
				
				$js .= "console.log('Canvas failed to load preset: $preset_url');";
				if ($user_settings) $settings = $user_settings;
				
			}
			
			
		} else {
			if ($user_settings) $settings = $user_settings;
		}
		
		$js .= "canvas_settings = $settings;";
		
		return $js;
		
	}
	
}


