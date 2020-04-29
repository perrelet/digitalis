<?php

namespace Digitalis\Module\Hide_WP_Menu;

class Hide_WP_Menu {
	
	function __construct () {

		add_action('wp_head', [$this, 'style']);
		
	}
	
	public function style() {
		if (is_admin_bar_showing()) {
			
?><style>
	body.admin-bar { margin-top: -32px !important; }
	@media screen and (max-width: 782px) { body.admin-bar { margin-top: 0px !important; } }
	@media screen and (min-width: 782px) { #wpadminbar { transition: 0.5s; opacity: 0; } #wpadminbar:hover { opacity: 1; } }
</style>
<?php

		}
	}
	
}

new Hide_WP_Menu();

