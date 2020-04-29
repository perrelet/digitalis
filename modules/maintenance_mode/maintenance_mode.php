<?php

namespace Digitalis\Module\Maintenance_Mode;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Field;

class Maintenance_Mode extends Module {
	
	public function run () {

		$this->fields('add_options');
	
		add_action('wp', [$this, 'redirect']);
		
	}
	
	public function add_options ($manager) {
		
		$maintenance_pages = ["Message", "Blank", "404", "Redirect"];
		
		$manager->add_field(new Field(
			"Maintenance Page",
			"maintenance_page",
			"select",
			"message")
		)->get_current_field()->add_options($maintenance_pages, true);
		
		$mode = $this->get_option("maintenance_page");
		
		switch ($mode) {
			
			case "message":
				$manager->add_field(new Field(
					"Message",
					"maintenance_msg",
					"text",
					"<b>Sorry!</b> " . get_bloginfo('name') . " is currently unavailable due to scheduled website maintenance.<br>" .
					"We'll be back online as soon as possible.<br><br>" .
					"Thankyou for your understanding."
					
				));
				break;			
			
			case "redirect":
				$manager->add_field(new Field(
					"Redirect URL",
					"maintenance_url",
					"text",
					get_site_url()
				));
				break;
			
		}
		
		
	}
	
	private function has_access () {
		
		return (is_user_logged_in() || is_admin());
		
	}
	
	public function redirect () {
		
		if (!$this->has_access()) {
			
			$mode = $this->get_option("maintenance_page");
			
			switch ($mode) {
				
				case "message":
				
					wp_die($this->get_option("maintenance_msg"));
					break;
					
				case "blank":
				
					die();
					break;
					
				case "404":
				
					global $wp_query;
					$wp_query->set_404();
					status_header( 404 );
					nocache_headers();
					break;
					
				case "redirect":
					
					global $wp;
					$current_url = home_url( $wp->request );
					$redirect_url = $this->get_option("maintenance_url");
					
					if (!($current_url == $redirect_url)) {
						wp_redirect( $redirect_url );
						exit;
					}
					
					break;
				
			}
			
		}
		
	}
	
}

new Maintenance_Mode();