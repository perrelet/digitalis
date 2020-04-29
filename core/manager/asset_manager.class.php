<?php

namespace Digitalis\Manager;

use Digitalis\Struct\JS_Loader;
use Digitalis\Util\Coder;
use Digitalis\Util\Styler;

class Asset_Manager {
	
	protected $assets;
	
	public function __construct() {
		$this->assets = [];
		$this->assets['css'] = [];
		$this->assets['js'] = [];
	}
	
	public function load_css($asset_name, $asset_path, $inline = true) {
		
		if (!(array_key_exists($asset_name, $this->assets['css']))) {
			
			if ($inline) {
				
				Styler::inline_file($asset_path . $asset_name);
				
			} else {
				//TODO: ENQUE THE STYLE
				return false;
			}

			$this->assets['css'][$asset_name] = true;
			return true;
		}
		return false;
		
	}
	
	public function load_js (JS_Loader $js_loader) {
		
		$asset_key = $js_loader->file;
		
		if (!(array_key_exists($asset_key, $this->assets['js']))) {
			
			$this->assets['js'][$asset_key] = 1;
			Coder::load($js_loader);
			
		} else {
			
			$this->assets['js'][$asset_key]++;
			
		}
		
		if ($js_loader->instantiate) {
		
			Coder::instantiate($js_loader->params_name, $js_loader->params, $this->assets['js'][$asset_key]);
			
		}
		
		return $this->assets['js'][$asset_key];
		
	}
	
}

?>