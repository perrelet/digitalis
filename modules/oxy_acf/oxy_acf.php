<?php

namespace Digitalis\Module\OXY_ACF;

class OXY_ACF {
	
	public function __construct() {
		
		$this->add_filters();
		
	}
	
	private function add_filters() {
		
		add_filter('acf/location/rule_types', [$this, 'location_rules']);
		add_filter('acf/location/rule_values/oxy-template', [$this, 'location_values_oxy_template']);
		add_filter('acf/location/rule_match/oxy-template', [$this, 'location_match_oxy_template'], 10, 4);
		
	}
	
	public function location_rules ( $choices ) {
	
		$choices['Oxygen']['oxy-template'] = 'Oxygen Template';
		return $choices;
		
	}
	
	public function location_values_oxy_template ( $choices ) {
		
		global $wpdb;
		$templates = $wpdb->get_results(
		    "SELECT id, post_title
		    FROM $wpdb->posts as post
		    WHERE post_type = 'ct_template'
		    AND post.post_status IN ('publish')"
		);
		
		if( $templates ) {
			foreach($templates as $template) {
				
				$template_type = get_post_meta($template->id, 'ct_template_type', true);
				if (!($template_type == "reusable_part")) {
					$choices[ $template->id ] = $template->post_title;
				}
			}
		}

		return $choices;
	}
	
	public function location_match_oxy_template ( $match, $rule, $options, $field_group = false ) {
		
		if (array_key_exists('post_id', $options)) {
		
			$current_template = get_post_meta( $options['post_id'], 'ct_other_template', true );
			$selected_template = (int) $rule['value'];
			
			if($rule['operator'] == "=="){
				$match = ( $current_template == $selected_template );
			} elseif($rule['operator'] == "!=") {
				$match = ( $current_template != $selected_template );
			}
		
		}

		return $match;
		
	}

}

new OXY_ACF();
	