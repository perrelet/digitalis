<?php

namespace Digitalis\Module\Welcome_Message;

use Digitalis\Admin\Option\Field;

class Welcome_Message {
	
	function __construct () {

		add_action('Digitalis\Options\Modules\Field', [$this, "add_options"]);
		add_action('wp_dashboard_setup', [$this, "widget"]);
	}	
	
	function add_options ($manager) {
		$manager->add_field(new Field("Welcome Text - Line 1", "welcome_text_1", "text", "Welcome, " . get_bloginfo('name')));
		$manager->add_field(new Field("Welcome Text - Line 2", "welcome_text_2", "text", "You're Amazing."));
		$manager->add_field(new Field("Welcome Icon", "welcome_icon", "text", DIGITALIS_URI . "assets/svg/digitalis.lockup.01.white.svg"));
	}
	
	function widget () {
		add_meta_box( 'digitalis_welcome', 'Digitalis Web Design', [$this, 'content'], 'dashboard', 'normal', 'high' );
		wp_enqueue_style('digitalis_admin', plugin_dir_url( __FILE__ ) . 'style.css', [], DIGITALIS_VERSION );
	}
	
	function content () {
		$html = "";
		$html .= "<div class='digitalis-welcome'>";
		$html .= "<h2 class='welcome-text'>" . get_option(DIGITALIS_OPTION . 'welcome_text_1') . "</h2>";
		$html .= "<img src='" . get_option(DIGITALIS_OPTION . 'welcome_icon') . "'>";
		$html .= "<h2 class='welcome-text'>" . get_option(DIGITALIS_OPTION . 'welcome_text_2') . "</h2>";
		//We &hearts; you guys.
		$html .= "</div>";
		echo $html;
	}
	
}

new Welcome_Message();