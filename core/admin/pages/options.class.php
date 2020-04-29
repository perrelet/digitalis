<?php

namespace Digitalis\Admin\Pages;

use Digitalis\Admin\Option\Field;
use Digitalis\Admin\Option\Form;

class Options {
	
	private $title;
	private $group;
	private $fields;
	
	private $form;
	
	function __construct ($title, $group) {
		
		$this->title = $title;
		$this->group = DIGITALIS_OPTION_GROUP . $group;
		
		$this->form = new Form($this->title, $this->group);
		$this->form->add_field(new Field("Test Option 1", "this_test_option", "checkbox", DIGITALIS_VALUE_TRUE));
		$this->form->add_field(new Field("Test Option 2", "this_test_option_2", "checkbox", DIGITALIS_VALUE_TRUE));
		
	}
	
	public function add_page () {
		
		add_options_page(
			'Digitalis',
			'Digitalis',
			'administrator',
			'digitalis',
			[$this, 'render'],
			null
		);
		
	}
	
	public function render () {
		
		$this->form->Render();
		
	}
	
	public function register () {
		
		foreach ($this->form->get_fields() as $field) {
			
			add_option($field->get_name(), $field->value );
			register_setting($this->group, $field->get_name());
			
		}
		
	}
	
}