<?php

namespace Digitalis\Struct;

class JS_Loader {
	
	public $directory;		// The directory of the asset.
	public $file;			// The assets file name - Also used as a unique identifier for the asset.
	public $relative;		// Whether $directory is relative to the plugin root.
	public $inline;			// Whether the script should be loaded inline.
	public $params;			// An array of parameters to localize / instantiate the script with.
	public $params_name;	// Name given to parameter object / instantiated object (auto increment).
	public $handle;			// Handle for wp_enqueue_script - If none given, $file is used.
	public $instantiate;	// Whether to instantiate the script by defining a unique object.
	
	public function __construct($directory, $file) {
		
		$this->directory 	= $directory;
		$this->file 		= $file;
		$this->relative 	= true;
		$this->inline 		= true;
		$this->params 		= [];
		$this->params_name 	= false;
		$this->handle 		= false;
		$this->instantiate 	= false;
		
	}
	
}

?>