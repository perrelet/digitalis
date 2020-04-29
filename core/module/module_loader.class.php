<?php

namespace Digitalis\Module;

use Digitalis\Admin\Option\Field;

class Module_Loader {
	
	protected $name;
	protected $config;
	protected $loaded;
	
	function __construct ($name, $config) {
		
		$this->name 	= $name;
		$this->config 	= $config;
		$this->loaded 	= false;
		
		add_action('Digitalis\Options\Modules\Field', [$this, "add_module_field"]);
		
		if (get_option(DIGITALIS_OPTION . $this->name)) {
			require_once(DIGITALIS_MODULE_PATH . $this->name . "/". $this->name . ".php");
			$this->loaded = true;
		}
		
	}
	
	public function add_module_field ($manager) {
		
		if (property_exists($this->config, "activate")) {
			$active = ($this->config->activate ? DIGITALIS_VALUE_TRUE : DIGITALIS_VALUE_FALSE);
		} else{
			$active = DIGITALIS_VALUE_FALSE;
		}
		
		$manager->add_divider();
		$manager->add_field(new Field("<b>" . $this->config->label . "</b>", $this->name, "checkbox", $active));
		
	}
	
}