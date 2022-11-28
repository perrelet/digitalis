<?php

namespace Digitalis\Module\Oxy_Better_Builder;

class Oxy_Better_Builder {
	
	public function __construct() {
		
		//add_action( 'oxygen_enqueue_ui_scripts', [$this, 'scripts']);
		add_action( 'wp_enqueue_scripts', [$this, 'scripts']);

	}

	public function scripts () {

		if (!defined("SHOW_CT_BUILDER")) return;

		if (defined("OXYGEN_IFRAME")) {

			wp_enqueue_script( 'oxy-better-builder', plugin_dir_url( __FILE__ ) . 'oxy_better_builder.js', [], DIGITALIS_VERSION, true);

		}

	}

}

new Oxy_Better_Builder();
	