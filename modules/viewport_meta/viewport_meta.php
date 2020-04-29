<?php

namespace Digitalis\Module\Viewport_Meta;

class Module {
	
	function __construct () {

		add_action('wp_head', [$this, 'meta']);
		
	}	
	
	function meta () {
		echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
	}
}

new Module();