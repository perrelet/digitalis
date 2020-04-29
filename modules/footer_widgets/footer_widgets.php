<?php

namespace Digitalis\Module\Footer_Widgets;

use Digitalis\Admin\Option\Field;

class Footer_Widgets {
	
	function __construct () {
		
		add_action('Digitalis\Options\Modules\Field', [$this, "add_options"]);
		add_action( 'widgets_init', [$this, 'widgets'] );
		
	}
	
	function add_options ($manager) {
		$manager->add_field(new Field("Number of Columns in Footer", "widget_count", "number", 4));
	}
	
	public function widgets() {
		
		$cols = get_option(DIGITALIS_OPTION . 'widget_count');
		
		for ($i = 1; $i <= $cols; $i++) {
			register_sidebar( array(
				'name' => "Footer Widget $i",
				'id' => "footer_widget_$i",
				'description' => "<b>Footer Column " . ucfirst(strval($i)),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>',
			) );		
		}
		
	}
}

new Footer_Widgets();