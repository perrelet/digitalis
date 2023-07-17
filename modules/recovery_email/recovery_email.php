<?php

namespace Digitalis\Module\Page_Loader;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Field;

class Recovery_Email extends Module {

	public function __construct () {
		
		$this->fields('add_options');

        add_filter('recovery_mode_email', [$this, 'recovery_mode_email'], 10, 2);
		
	}

    public function add_options ($manager) {
		
        $manager->add_field(new Field(
            "Recovery Email Address",
            "recovery_email_address",
            "text",
            ""
        ));
		
	}

    public function recovery_mode_email ($email, $url) {
        
        if (isset($email['to'])) $email['to'] = get_option(DIGITALIS_OPTION . 'recovery_email_address');

        return $email;
        
    }

}

new Recovery_Email();