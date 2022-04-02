<?php

namespace Digitalis\Module\Page_Loader;

use Digitalis\Module\Module;
use Digitalis\Struct\JS_Loader;
use Digitalis\Admin\Option\Field;
use Digitalis\Util\Utility;

// Based On: https://www.primative.net/blog/how-to-get-rid-of-the-flash-of-unstyled-content/


class Page_Loader extends Module {
	
	public function __construct () {
		
		$this->fields('add_options');
		
		$this->no_js();
		
		add_action('wp_enqueue_scripts', [$this, 'scripts']);
		
		add_action('wp_head', 	[$this, 'no_js_bypass']);
		add_action('init', 		[$this, 'init']);
		
		add_action('update_option_' . $this->get_option_key("loader_spinner"), [$this, 'save'], 10, 3);
		
	}
	
	public function init () {
		
		if ($this->get_option('loader_do_screen')) 	add_action('ct_before_builder', [$this, 'inject_screen'], 100);
		if ($this->get_option('loader_do_spinner')) add_action('Digitalis\Module\Page_Loader\Content', [$this, 'spinner'], 100);
		
		
	}
	
	public function add_options ($manager) {
		
		$spinners = [
			"bars"			=> "Bars",
			"default"		=> "Default",
			"dots"			=> "Dots",
			"dotty"			=> "Dotty",
			"faded"			=> "Faded",
			"simple"		=> "Simple",
			"sweep-dots"	=> "Sweep Dots",
			"sweeper"		=> "Sweeper",
			"triple"		=> "Triple"
		];
		
		$manager->add_field(new Field("Inject Loading Screen?", "loader_do_screen", "checkbox", DIGITALIS_VALUE_TRUE));
		$manager->add_field(new Field("Render a Spinner?", 		"loader_do_spinner", "checkbox", DIGITALIS_VALUE_TRUE));
		$manager->add_field(new Field(
			"Spinner",
			"loader_spinner",
			"select",
			"default"
		))->get_current_field()->add_options($spinners);
		
		$manager->add_field(new Field("Spinner Speed (s)", 		"loader_spinner_speed", "text", "1"));
		$manager->add_field(new Field("Transition Speed (s)", 	"loader_trans_speed", "text", "1"));
		$manager->add_field(new Field("Spinner Color", 			"loader_spinner_color", "text", "#555555"));
		$manager->add_field(new Field("Background Style", 		"loader_bg", "text", "#ffffff"));

	}
		
	public function inject_screen () {
		
		$transition_speed = $this->get_option('loader_trans_speed') . "s";
		$style = "position: fixed; left: 0; top: 0; width: 100vw; height: 100vh; z-index: 999; display: flex; align-items: center; justify-content: center; pointer-events: none; ";
		$style .= "background: " . $this->get_option('loader_bg') . "; ";
		
		do_action('Digitalis\Module\Page_Loader\Before');
		
		echo "<div id='page_loader' style='{$style}'>";
		do_action('Digitalis\Module\Page_Loader\Content');
		echo "</div>";
		
		?><style>
		@keyframes page_loader { 0% { opacity: 1; } 100% { opacity: 0; } }
		@-webkit-keyframes page_loader { 0% { opacity: 1; } 100% { opacity: 0; } }
		body.loaded #page_loader {
			animation: <?php echo $transition_speed; ?> ease 0s 1 normal forwards running page_loader;
			-webkit-animation: <?php echo $transition_speed; ?> ease 0s 1 normal forwards running page_loader;
		}
		</style><?php
		
		do_action('Digitalis\Module\Page_Loader\After');
		
	}
	
	public function spinner () {
		
		echo "<div class='spinner'></div>";
		
		$upload_path = wp_upload_dir();
		$dir = 'digitalis';
		$filename = 'spinner.css';
		$path = $upload_path['basedir'] . "/" . $dir . "/";
		
		$asset_manager = $this->get_asset_manager();
		$asset_manager->load_css($filename, $path, true, false);	
		
		/* $spinner = $this->get_option('loader_spinner');
		if (!$spinner) return;
		$spinner_parts = pathinfo($spinner);
		
		if ($spinner_parts['extension'] == 'css') {
			
			$filename = $spinner_parts['filename'] . "." . $spinner_parts['extension'];
			$path = $spinner_parts['dirname'] . "/";
			
			$asset_manager = $this->get_asset_manager();
			$asset_manager->load_css($filename, $path, true, true, [
				"%%COLOR%%" => $this->get_option('loader_spinner_color'),
				"%%BG%%" 	=> $this->get_option('loader_bg'),
			]);	
			
		} else {
			
			if (!$spinner_parts['dirname'] || ($spinner_parts['dirname'] == '.')) {
				$spinner = get_site_url() . "/wp-content/plugins/digitalis/modules/page_loader/spinners/gif/" . $spinner;
			}
			
			echo "<img src='{$spinner}' class='hub-spinner'>";
			
		} */
		
	}
	
	public function save ($old_value, $value, $option) {
		
		//$spinner = $value;
		//if (!$spinner) return;
		//$spinner_parts = pathinfo($spinner);
		
		//modules\page_loader\spinners\
		
		$spinner = "modules/page_loader/spinners/" . $value . ".css";
		
		//if ($spinner_parts['extension'] == 'css') {
		
			//$filename = $spinner_parts['filename'] . "." . $spinner_parts['extension'];
			//$path = $spinner_parts['dirname'] . "/";
			
			$css = Utility::get_file($spinner, "css", true, [
				"%%COLOR%%" => $this->get_option('loader_spinner_color'),
				"%%BG%%" 	=> $this->get_option('loader_bg'),
				"%%SPEED%%" => $this->get_option('loader_spinner_speed'),
			]);
			
			$upload_path = wp_upload_dir();
			$dir = 'digitalis';
			$path = $upload_path['basedir'] . "/" . $dir;
			if (!file_exists($path)) mkdir($path, 0775, true);
			
			$file = 'spinner.css';
			
			file_put_contents($path . "/" . $file, $css);

		//}
		
	}
	
	public function no_js () {
		
		add_filter('body_class', 			[$this, 'add_no_js_class'], 10, 2);
		add_action('ct_before_builder', 	[$this, 'js_remove_no_js_class']);
		
	}
	
	public function no_js_bypass () {
		
		echo "<style>.no-js #page_loader { visibility: visible; } </style>";
		
	}
	
	public function add_no_js_class ($classes, $class) {
		
		$classes[] = "no-js";
		return $classes;
		
	}
	
	public function js_remove_no_js_class () {
		
		echo "<script>document.body.classList.remove('no-js');</script>";
		
	}
	
	public function scripts () {
		
		$asset_manager = $this->digitalis()->get_asset_manager();
		
		$loader = new JS_Loader("modules/page_loader/", "page_loader.js");
		$loader->params_name = "page_loader_params";
		$loader->params = [
			"transition_speed"	=>	$this->get_option('loader_trans_speed')
		];
		$asset_manager->load_js($loader);
	
		
	}
	
}

new Page_Loader();