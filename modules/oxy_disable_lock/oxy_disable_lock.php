<?php

namespace Digitalis\Module\Oxy_Disable_lock;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Tab;

class Oxy_Disable_lock extends Module {
	
	public function run () {
		
        add_action('admin_head', [$this, 'disable_edit_locking']);
		
	}

    public function disable_edit_locking () {

        global $post;

        $transient = "oxygen_post_edit_lock";
        add_filter("pre_transient_{$transient}", "intval");

        if ($post && $post->ID) {

            add_filter("pre_transient_{$transient}_{$post->ID}", "intval");

            ?>
            <script>
                jQuery.ajaxSetup({
                    beforeSend: function(jqXHR, settings) {
                        if (settings.data && typeof settings.data === 'string' && settings.data.includes("oxygen_edit_post_lock_transient")) {
                            console.log("Edit locking is disabled");
                            jqXHR.abort();
                        }
                    }
                });
            </script>
            <?php

        }

    }
	
}

new Oxy_Disable_lock();