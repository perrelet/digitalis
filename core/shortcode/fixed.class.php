<?php

namespace Digitalis\Shortcode;

class Fixed {
	
	protected $plugin, $instance, $options;
	
	public function __construct($digitalis) {
		$this->plugin = $digitalis;
		$this->options = $this->get_options();
		$this->init( $this->options );
		add_shortcode($this->options['tag'], [$this, 'run']);
	}
	
	protected function init( $options ) {
		$this->options = $options;
		$this->instance = 0;
		//$this->
	}
	
	//Public Methods
	
	public function run ($atts, $content = null, $name = null){
		$a = $this->get_atts($atts);
		if ($error = $this->invalidate($a)) return $error;
		$this->instance++;
		
		if ($this->is_dynamic()) {
			return $this->shortcode($this->dynamic_atts($a), $content, $name);
		} else {
			return $this->shortcode($a, $content, $name);
		}		
        
    }
	
	public function load_css() {
		return call_user_func_array(array($this->plugin->get_asset_manager(), __FUNCTION__), func_get_args());
	}
	
	public function load_js () {
		return call_user_func_array(array($this->plugin->get_asset_manager(), __FUNCTION__), func_get_args());
	}
	
	//SELF KNOWLEDGE
	
	protected function is_first() {
		return ($this->instance == 1);
	}
	
	protected function get_instance() {
		return $this->instance;
	}
	
	protected function is_dynamic() {
		return is_subclass_of($this, "Digitalis\Shortcode\Dynamic");
	}
	
	//SHORTCODE VALIDATION
	
	protected function get_atts ( $atts ) {
		return shortcode_atts($this->options['atts'], $atts, $this->options['tag']);
	}

	protected function invalidate ( $atts ) {
		if (array_key_exists("required", $this->options)) {
			$missing = $this->check_atts($atts, $this->options["required"]);
			return (count($missing) == 0) ? false : "The '" . implode("', '", $missing) . "' shortcode attribute(s) are required.";
		} else {
			return false;
		}
	}

	protected function check_atts ( $atts, $required ){
		$missing = [];
		foreach ($required as $key) if (!$atts[$key]) $missing[] = $key;
		return $missing;
	}
	
	protected function create_array ( $string, $labelled = false ) {
		$split = explode(DIGITALIS_ARRAY_DELIMITER, $string);
		$data = [];
		foreach ($split as $item) {
			if ($labelled) {
				$pair = explode(DIGITALIS_VALUE_DELIMITER, $item);
				if (count($pair) >= 2) {
					$data[trim($pair[0])] = trim($pair[1]);
				} else {
					array_push($data, trim($item));
				}
			} else {
				array_push($data, trim($item));
			}
		}
		return $data;
	}
	
	//ATTRIBUTE CONTROL
	
	protected function arrayify ($value) {
		return explode(",", str_replace(" ", "", $value));
	}
	
}

?>