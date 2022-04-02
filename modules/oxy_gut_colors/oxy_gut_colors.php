<?php

namespace Digitalis\Module\Oxy_Gut_Colors;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Tab;

class Oxy_Gut_Colors extends Module {
	
	public function run () {
		
		add_action('after_setup_theme', [$this, 'after_setup_theme']);
		
		$this->tabs('add_tab');
		
	}
	
	public function add_tab ($manager) {
		
		$tab = new Tab("CSS Color Codes", "oxy_gut_colors", false);
		$tab
		->set_capability(DIGITALIS_ADMIN_CAP)
		->add_action([$this, "render_tab"]);
		
		$manager->add_tab($tab);
		
	}

	public function render_tab () {
		
        $oxy_colors = oxy_get_global_colors()['colors'];
		$css = "/* GUTENBERG GLOBAL COLORS */\n\n";
		
        foreach ($oxy_colors as $color) {
			
			$css .= "body .has-oxy-" . $color['id'] . "-color { \n";
			$css .= "\tcolor: " . $color['value'] . ";\n";
			$css .= "}\n";
			$css .= "body .has-oxy-" . $color['id'] . "-background-color { \n";
			$css .= "\tbackground-color: " . $color['value'] . ";\n";
			$css .= "}\n";
			
		}
		
		echo "<pre>";
		echo $css;
		echo "</pre>";
		
	}
	
	public function after_setup_theme () {
		
		if (!is_callable('oxy_get_global_colors')) return;
	
		$colors = [];
        $oxy_colors = oxy_get_global_colors()['colors'];

        foreach ($oxy_colors as $color) {
	
			$colors[] = [
				'name' 		=> $color['name'],
				'slug' 		=> 'oxy-' . $color['id'],
				'color' 	=> $color['value']
			];

        }

		add_theme_support('editor-color-palette', $colors);
		
	}
	
}

new Oxy_Gut_Colors();