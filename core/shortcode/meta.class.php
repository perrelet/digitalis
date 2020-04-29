<?php

namespace Digitalis\Shortcode;

class Meta extends Fixed {
	
	//SHORTCODE VALIDATION
	
	protected function is_builder () {
		$current_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		parse_str(parse_url($current_url, PHP_URL_QUERY), $params);
		return (array_key_exists("action", $params)) ? ($params["action"] == "ct_render_shortcode") : false;
	}
	
	//CLASSES
	
	protected function repeater_class () {
		return call_user_func_array("\Digitalis\Util\Meta::" . __FUNCTION__, func_get_args());
	}
	
	//ACF
	
	protected function static_field_value () {
		return call_user_func_array("\Digitalis\Util\Meta::" . __FUNCTION__, func_get_args());
	}
	
	protected function field_value () {
		return call_user_func_array("\Digitalis\Util\Meta::" . __FUNCTION__, func_get_args());
	}
	
	protected function remove_http () {
		return call_user_func_array("\Digitalis\Util\Meta::" . __FUNCTION__, func_get_args());
	}
	
	protected function loop_subfields() {
		return call_user_func_array("\Digitalis\Util\Meta::" . __FUNCTION__, func_get_args());
	}
	
	protected function parse_subfields () {
		return call_user_func_array("\Digitalis\Util\Meta::" . __FUNCTION__, func_get_args());
	}
	
}

?>