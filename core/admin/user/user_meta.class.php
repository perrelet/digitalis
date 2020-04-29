<?php

namespace Digitalis\Admin\User;

use Digitalis\Admin\Option\Field;

class User_Meta {
	
	private $fields = [];
	private $capabilities = [];
	private $title;
	
	public function __construct(Array $capabilities = [], $title = false) {
		
		$this->capabilities = $capabilities;
		$this->title = $title;
		
		add_action( 'show_user_profile', [$this, 'render'], 10, 1 );
		add_action( 'edit_user_profile', [$this, 'render'], 10, 1 );
		
		add_action( 'personal_options_update', [$this, 'save_meta'], 10, 1 );
		add_action( 'edit_user_profile_update', [$this, 'save_meta'], 10, 1 );
		
	}//current_user_can('administrator')
	
	public function add_fields($fields) {
		foreach ($fields as $field) {
			$this->add_field($field);
		}
	}
	
	public function add_field(Field $field) {
		
		$this->fields[] = $field;
		return $field;
		
	}
	
	public function render ($user) {
		
		foreach ($this->capabilities as $cap) if (!user_can($user, $cap)) return false;
		
		if (count($this->fields) == 0) return false;
			
		if ($this->title) echo "<h3>" . $this->title . "</h3>";
		
		echo "<table class='form-table'><tbody>";
		
		foreach($this->fields as $field) {
			echo "<tr>";
			$field->table_mode = true;
			$field->render($user->ID);
			echo "</tr>";
		}
		
		echo "</tbody></table>";			
			
		return true;
			
		
	}
	
	public function save_meta ($user_id) {
		
		if (!current_user_can('edit_user', $user_id)) return false;
		foreach ($this->capabilities as $cap) if (!user_can($user_id, $cap)) return false;
		
		foreach ($this->fields as $field) {
			if (isset($_POST[$field->name]) && $_POST[$field->name]) {
				update_user_meta( $user_id, $field->name, $_POST[$field->name] );
			} else {
				delete_user_meta( $user_id, $field->name );
			}
		}

		return true;
		
	}
	
}