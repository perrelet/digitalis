<?php

namespace Digitalis\Admin\Option;

class Tab {
	
	protected $blocks = [];
	
	protected $title;
	protected $group;
	
	protected $form;
	
	protected $capability = false;
	
	function __construct ($title, $group, $fields = []) {
		
		$this->title = $title;
		$this->group = DIGITALIS_OPTION_GROUP . $group;
		
		if ($fields === false) {
			$this->form = false;
		} else {
			$this->add_form($this->title, $this->group, $fields, true);
		}
		
	}
	
	public function set_capability ($capability) {
		
		$this->capability = $capability;
		return $this;
		
	}
	
	//SELF KNOWLEDGE
	
	public function has_form () {
		return (!($this->form === false));
	}
	
	public function get_title() {
		return $this->title;
	}
	
	public function get_group() {
		return $this->group;
	}
	
	public function get_form() {
		return $this->form;
	}
	
	public function has_access () {
		if (!$this->capability) return true;
		return current_user_can($this->capability);
	}
	
	//RENDER
	
	public function render () {
		
		echo "<div class='digitalis-tab'>";
		
		if (!$this->has_access()) {
			echo "<div class='digitalis-warning'>You do not have access to view this page.</div>";
			return false;
		}
		
		foreach ($this->blocks as $block) {

			if (is_a($block, "Digitalis\Admin\Option\Form")) {
				
				$block->render();
				
			} else if (is_callable($block)) {
				
				echo call_user_func($block);

			} else {
				
				echo $block;
				
			}
			
		}
		
		echo "</div>";
		
		return true;
		
	}
	
	//HTML
	
	public function add_action ($function) {
		
		if ($this->has_form()) {
			$this->form->add_action($function);
		} else {
			$this->blocks[] = $function;
		}
		
		return $this;
		
	}
	
	//FORMS
	
	public function add_form ($title = false, $group = false, $fields = [], $render_title = false) {
		
		$title = ($title ? $title : $this->title);
		$group = ($group ? $group : $this->group);
		
		$this->form = new Form($title, $group, $fields, $render_title);
		$this->blocks[] = $this->form;
		
		return $this;
		
	}
	
	public function register () {
		
		if ($this->has_form()) $this->form->register();
		return $this;
		
	}
	
	public function add_field(Field $field) {
		
		if ($this->has_form()) $this->form->add_field($field);
		return $this;
		
	}
	
	public function add_divider() {
		
		if ($this->has_form()) $this->form->add_divider();
		return $this;
		
	}
	
}