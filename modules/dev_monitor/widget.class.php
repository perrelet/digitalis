<?php

namespace Digitalis\Module\Dev_Monitor;

class Widget {

	private $module;
	
	public function __construct ($module) {
		
		$this->module = $module;
		
		add_action('wp_dashboard_setup', [$this, 'widget']);
		
	}
	
	public function widget () {
		
		add_meta_box(
			'digitalis_productivity',
			'Digitalis Development Monitor',
			[$this, 'content'],
			'dashboard',
			'normal',
			'high'
		);
		
	}
	
	public function content () {
		
		$this->module->draw_table(
			$this->module->get_data(
				$this->module->get_option("monitor_widget_mode"),
				$this->module->get_option("monitor_merge_users")
			)
		);
		
	}
	
}