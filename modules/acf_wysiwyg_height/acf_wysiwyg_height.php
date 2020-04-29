<?php

namespace Digitalis\Module\ACF_WYSIWYG_Height;

use Digitalis\Admin\Option\Field;

class ACF_WYSIWYG_Height {
	
	function __construct () {
		if (class_exists('acf')) {
			add_action('acf/render_field_settings/type=wysiwyg', [$this, 'wysiwyg_settings'], 10, 1 );
			add_action('acf/render_field/type=wysiwyg', [$this, 'wysiwyg_render'], 10, 1 );
		}
	}
	
	public function wysiwyg_settings($field) {
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Height of Editor'),
			'instructions'	=> __('Height of Editor after Init'),
			'name'			=> 'wysiwyg_height',
			'type'			=> 'number',
		));
		
	}
	
	public function wysiwyg_render($field) {
		
		$field_class = '.acf-'.str_replace('_', '-', $field['key']);
		if (array_key_exists('wysiwyg_height', $field)) {
			
			echo "
			<style type='text/css'>
				$field_class iframe {
					min-height: " . $field['wysiwyg_height'] . "px; 
				}
			</style>
			<script type='text/javascript'>
				jQuery(window).load(function() {
					jQuery('$field_class').each(function() {
						jQuery('#'+jQuery(this).find('iframe').attr('id')).height(" . $field['wysiwyg_height'] .");
					});
				});
			</script>";
		}

	}
	
}

new ACF_WYSIWYG_Height;

