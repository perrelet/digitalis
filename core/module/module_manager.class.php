<?php

namespace Digitalis\Module;

class Module_Manager {
	
	//private $label;
	//private $name;
	//public $loaded;
	
	private $plugin;
	private $module_dir;
	private $modules;
	
	function __construct ($plugin, $module_dir) {
		
		$this->plugin = $plugin;
		$this->module_dir = $module_dir;
		$this->modules = [];
		
		$search = glob($module_dir . '*' , GLOB_ONLYDIR);
		
		foreach ($search as $dir) {

			$json = $dir . "/module.json";
			if (file_exists($json)) {
				
				$module_name = basename($dir);
				$module_config = json_decode(file_get_contents($json));
				$this->modules[$module_name] = new Module_Loader($module_name, $module_config);

			}
		}
		
	}
	
}