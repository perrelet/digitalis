<?php

namespace Digitalis\Module\ACF_Style;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Field;

class ACF_Style extends Module {
	
	public function run () {
		
		$this->fields('add_options');
		
		add_action( 'admin_init', [$this, 'init'] );
		
	}
	
	public function add_options ($manager) {
		
		$themes = [
			"soft_shadow" => "Soft Shadow"
		];
		
		$manager->add_field(new Field(
			"Select Theme",
			"acf_theme",
			"select",
			"soft_shadow"
		))->get_current_field()->add_options($themes);
		
	}
	
	public function init () {
		
		if (!is_admin() ||  wp_doing_ajax()) return false;
		
		wp_enqueue_style('acf_theme', plugin_dir_url( __FILE__ ) . 'themes/' . $this->get_option("acf_theme") . '.css', [], DIGITALIS_VERSION );
		
		
	}
	
}

new ACF_Style();