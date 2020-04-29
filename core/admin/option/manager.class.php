<?php

namespace Digitalis\Admin\Option;

use Digitalis\Admin\Option\Field;
use Digitalis\Admin\Option\Page;
use Digitalis\Admin\Option\Tab;

class Manager {

	private $pages = [];
	private $current_page = false;
	private $current_tab = false;
	private $current_field = false;

	function __construct () {
		
	}
	
	public function add_page (Page $page) {
		$page_name = $page->get_name();
		$this->current_page = $page_name;
		$this->pages[$page_name] = $page;
		
		add_action('admin_menu', [$page, 'add_page']);
		add_action('admin_init', [$page, 'register']);
		
		return $this;
	}
	
	public function add_tab (Tab $tab) {
		
		if ($this->current_page) {
			$tab_name = $tab->get_group();
			$this->current_tab = $tab_name;
			$this->get_current_page()->add_tab($tab);	
		}
		return $this;
		
	}
	
	public function add_action ($function) {
		
		if ($this->current_tab) {
			$this->get_current_tab()->add_action ($function);
		}
		return $this;
		
	}
	
	public function add_field(Field $field) {
		
		if ($this->current_tab) {
			$this->current_field = $field;
			$this->get_current_tab()->add_field($field);		
		}
		return $this;
		
	}
	
	public function add_divider() {
		if ($this->current_tab) {
			$this->get_current_tab()->add_divider();		
		}
		return $this;
	}
	
	public function get_current_page() {
		return $this->pages[$this->current_page];
	}
	
	public function get_current_tab() {
		return $this->get_current_page()->get_tab($this->current_tab);
	}

	public function get_current_field() {
		return $this->current_field;
	}
	
}
