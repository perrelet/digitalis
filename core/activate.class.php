<?php

namespace Digitalis;

class Activate {
	
	function __construct() {
		
		$this->admin_capability();
		
	}
	
	private function admin_capability () {
		
		get_role( 'administrator' )->add_cap(DIGITALIS_ADMIN_CAP);
		
	}
	
}