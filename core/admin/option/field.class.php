<?php

namespace Digitalis\Admin\Option;

class Field {
	
	public $name;
	public $label;
	public $type;
	public $value;
	public $context;
	
	public $description = false;
	public $table_mode = false;
	public $options = [];
	
	
	public function __construct ($label, $name, $type = "checkbox", $value = "", $context = "option") {
		
		$this->label 	= $label;
		$this->type 	= $type;
		$this->value 	= $value;
		$this->context  = $context;
		
		$this->set_name($name);
		
		return $this;
		
	}
	
	public function add_options ($options = [], $auto_values = false) {
		
		if ($auto_values == false) {
			
			$this->options = $options;
			
		} else {
			
			foreach ($options as $option) {
				$this->options[sanitize_title_with_dashes($option)] = $option;
			}
			
		}
		
	}
	
	public function add_description ($description) {
		
		$this->description = $description;
		return $this;
		
	}
	
	public function render ($id = false) {
		
		$html = "";
		
		if ($this->type == "divider") {
			
			$html .= "<div class='digitalis-admin-divider'></div>";
			
		} else {
			
			if ($this->table_mode) {
				$html .= "<th><label for='". $this->name . "'>" . $this->label . "</label></th>";
				$html .= "<td>";
			} else {
				$html .= "<div class='digitalis-admin-field-row'>";
				$html .= "<div><label for='". $this->name . "'>" . $this->label . "</label></div>";
				$html .= "<div>";				
			}
			
			switch ($this->context) {
				
				case "user_meta":
					$meta = get_user_meta($id, $this->name, true);
					break;
					
				case "option":
				default:
					$meta = get_option($this->name, $this->value);
					break;	
					
			}
			
			switch ($this->type) {
				case "checkbox":
					$html .= "<input type='checkbox' id='{$this->name}' name='{$this->name}' value='".DIGITALIS_VALUE_TRUE."' " . checked($meta, DIGITALIS_VALUE_TRUE, false) . ">";
					break;
				case "select":
					$html .= "<select id='{$this->name}' name='{$this->name}'>";
					foreach ($this->options as $value => $label) {
						$selected = ($value == $meta ? " selected" : "");
						$html .= "<option value='$value'$selected>$label</option>";
					}
					$html .= "</select>";
					break;
				default:
					$html .= "<input type='" . $this->type . "' id='" .$this->name . "' name='" . $this->name ."' value='" . esc_attr($meta) . "'>";
					
			}
				
			if ($this->table_mode) {
				
				if ($this->description) $html .= "<p class='description'>{$this->description}</p>";
				$html .= "</td>";
				
			} else {
				
				$html .= "</div></div>";	
				
			}
				
		}
		
		echo $html;
		
	}
	
	public function get_name () {
		
		return $this->name;
		
	}
	
	public function set_name ($name) {
		
		switch ($this->context) {
			
			case "user_meta":
				$this->name = DIGITALIS_USER_META . $name;
				break;
				
			case "option":
			default:
				$this->name = DIGITALIS_OPTION . $name;
				break;
			
		}
		
		return $this->name;
		
	}
	
}