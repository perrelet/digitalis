<?php

namespace Digitalis\Admin\Option;

class Page {
	
	protected $title;
	protected $name;
	protected $tabs;
	
	function __construct ($title, $name, $tabs = []) {
		
		$this->title = $title;
		$this->name = $name;
		$this->tabs = $tabs;
		
	}
	
	public function add_page () {
		
		add_options_page(
			$this->title,
			$this->title,
			'administrator',
			$this->name,
			[$this, 'render'],
			null
		);
		
	}
	
	public function add_tab (Tab $tab) {
		
		$this->tabs[$tab->get_group()] = $tab;
		
	}
	
	public function render () {
		
		echo "<div class='wrap'>";
		
		if (count($this->tabs) > 0 ) {
			if (count($this->tabs) == 1) {
				
				reset($this->tabs)->Render();
				
			} else {
				
				$this->tab_menu();
				
				$tab_group = (isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : false);
				
				if ($tab_group) {
					
					foreach ($this->tabs as $tab) {
						if ($tab_group == $tab->get_group()) {
							$tab->Render();
							break;
						}
					}
					
				} else {
					
					reset($this->tabs)->Render();
					
				}
			}
		}
		
		echo "</div>";
		
	}
	
	public function register () {
		
		foreach($this->tabs as $tab) {
			$tab->register();
		}
		
	}
	
	//Self Knowledge
	
	public function get_name () {
		return $this->name;
	}
	
	public function get_tab ($key) {
		if (array_key_exists($key, $this->tabs)) {
			return $this->tabs[$key];
		}
	}
	
	//Page Components
	
	private function tab_menu () {
		
		echo "<nav class='digitalis-tab-menu'>";
		foreach($this->tabs as $tab) {
			if ($tab->has_access()) echo "<a href='?page={$this->name}&tab={$tab->get_group()}'>{$tab->get_title()}</a>";
		}
		echo "</nav>";
		
	}
	
}