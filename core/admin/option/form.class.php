<?php

namespace Digitalis\Admin\Option;

class Form {
	
	private $title;
	private $group;
	private $fields;
	
	private $render_title;
	
	function __construct ($title, $group, $fields = [], $render_title = true) {
		
		$this->title = $title;
		$this->group = $group;
		$this->fields = $fields;
		
		$this->render_title = $render_title;
		
	}
	
	public function add_fields($fields) {
		foreach ($fields as $field) {
			$this->add_field($field);
		}
	}
	
	public function add_field(Field $field) {
		$this->fields[] = $field;
	}
	
	public function add_divider() {
		$this->add_field(new Field(false, false, "divider"));
	}
	
	public function get_fields() {
		return $this->fields;
	}
	
	public function add_action ($function) {
		$this->fields[] = $function;
	}
	
	public function register () {
		
		foreach ($this->fields as $field) {
			
			if (is_a($field, "Digitalis\Admin\Option\Field")) {
				
				add_option($field->get_name(), $field->value );
				register_setting($this->group, $field->get_name());
				
			}
			
		}
		
	}
	
	public function render () {
		
		if ($this->render_title) echo "<h1>" . $this->title . "</h1>";
		echo "<form method='post' action='options.php'>";
		
			settings_fields($this->group);
			//do_settings_sections($this->group);
			
			echo "<div class='digitalis-admin-fields'>";
			foreach($this->fields as $field) {
				
				if (is_a($field, "Digitalis\Admin\Option\Field")) {
					
					$field->render();	
					
				} else if (is_callable($field)) {
					
					echo call_user_func($field);
					
				} else {
					
					echo $field;
					
				}
				
			}
			echo "</div>";
		
		submit_button();
		echo "</form>";
		
	}
	
}