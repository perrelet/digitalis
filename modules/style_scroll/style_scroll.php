<?php

namespace Digitalis\Module\Scroll_Style;

use Digitalis\Admin\Option\Field;
use Digitalis\Util\Styler;
use Digitalis\Module\Module;

class Scroll_Style extends Module {
	
	function run () {
		
		$this->fields('add_options');
		$this->enqueue_script('style');
		
	}
	
	function add_options ($manager) {
		
		$manager->add_field(new Field("Scroll Bar Width", "scroll_bar_width", "text", "auto"));
		$manager->add_field(new Field("Scroll Track Color", "scroll_track_color", "text", "#f1f1f1"));
		$manager->add_field(new Field("Scroll Thumb Color", "scroll_thumb_color", "text", "#888"));
		$manager->add_field(new Field("Scroll Thumb Hover Color", "scroll_thumb_hover_color", "text", "#555"));
		$manager->add_field(new Field("Scroll Thumb Border Radius", "scroll_thumb_radius", "text", "0px"));
		
	}
	
	function style () {

		$data = [
			"::-webkit-scrollbar" => [
				"width" => $this->get_option('scroll_bar_width'),
				"height" => $this->get_option('scroll_bar_width'),
			],
			"::-webkit-scrollbar-track" => [
				"background" => $this->get_option('scroll_track_color'),
			],
			"::-webkit-scrollbar-thumb" => [
				"background" => $this->get_option('scroll_thumb_color'),
				"border-radius" => $this->get_option('scroll_thumb_radius'),
			],
			"::-webkit-scrollbar-thumb:hover" => [
				"background" => $this->get_option('scroll_thumb_hover_color'),
			],
		];
		
		$handle = $this->get_handle();
		
		wp_register_style 	($handle, false, [], DIGITALIS_VERSION);
		wp_enqueue_style 	($handle);
		wp_add_inline_style ($handle, Styler::build_block($data, false));
		
	}
	
}

new Scroll_Style();