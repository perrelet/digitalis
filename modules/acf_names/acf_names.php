<?php

namespace Digitalis\Module\ACF_Names;

class ACF_Names {
	
	function __construct () {
		
		if (class_exists('acf') && is_admin()) add_action('acf/prepare_field', [$this, 'prepare_field']);
		
	}
	
	public function prepare_field( $field ) {
		
		if (current_user_can('administrator')) {
			
			$wrap = ["<span style='font-size: 1em; opacity: 0.6; margin-left: 12px;' class='digit-acf-name'>", "</span>"];
			
			$html = $wrap[0] . " " . $field["_name"] . $wrap[1];

			if (array_key_exists("wpml_cf_preferences", $field)) {
				
				$wpml_choices = array(
					WPML_IGNORE_CUSTOM_FIELD	=> __("Don't translate",'acfml'),
					WPML_COPY_CUSTOM_FIELD		=> __("Copy",'acfml'),
					WPML_COPY_ONCE_CUSTOM_FIELD => __("Copy once", 'acfml'),
					WPML_TRANSLATE_CUSTOM_FIELD => __("Translate", "acfml")
				);
				
				$html .= $wrap[0] . " " . $wpml_choices[$field['wpml_cf_preferences']] . $wrap[1];
				
			}
			
			$field['label'] .= $html;
			
		}

		return $field;
	}
	
}

new ACF_Names();