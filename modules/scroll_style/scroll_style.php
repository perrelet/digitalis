<?php

namespace Digitalis\Module\Hide_WP_Menu;

use Digitalis\Module\Module;
use Digitalis\Struct\JS_Loader;
use Digitalis\Admin\Option\Field;

class Scroll_Style extends Module {
	
	public function __construct () {
		
		add_action('Digitalis\Options\Modules\Field', [$this, "add_options"]);
		add_action('wp_enqueue_scripts', [$this, 'scripts']);
		
	}
	
	function add_options ($manager) {
		
		$manager->add_field(new Field("Offset (px)", "scroll_style_offset", "text", "1"));
		
	}
	
	public function scripts () {

		$asset_manager = $this->digitalis()->get_asset_manager();
		
		$asset_manager->load_js(new JS_Loader("assets/js/", "throttle.min.js"));
		
		$loader = new JS_Loader("modules/scroll_style/", "scroll_style.min.js");
		//$loader = new JS_Loader("modules/scroll_style/", "scroll_style.js");
		$loader->params_name = "scroll_style_params";
		$loader->params = [
			"offset"		=>	get_option(DIGITALIS_OPTION . 'scroll_style_offset')
		];
		$asset_manager->load_js($loader);
	
	}
	
}

new Scroll_Style();