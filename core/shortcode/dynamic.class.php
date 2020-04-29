<?php

namespace Digitalis\Shortcode;

class Dynamic extends Meta {
	
	protected function dynamic_atts ($atts) {
		
		if (!array_key_exists("dynamic", $this->options)) return $atts;
		
		$values = [];
		foreach ($this->options['dynamic'] as $key) $values[$key] = $this->dynamic($atts[$key]);
		
		return array_merge($atts, $values);
	} 
	
	protected function dynamic ($value) {
		if ($value) {
			$symbol = $value[0];
			
			switch ($symbol) {
				case DIGITALIS_SYMBOL_FIELD:
					return $this->field_value(trim(substr($value, 1)), false);
					
				case DIGITALIS_SYMBOL_FIELD_ID:		
					$pair = explode(DIGITALIS_ARRAY_DELIMITER, substr($value, 1));
					
					if (count($pair) >= 2) {
						return $this->field_value(trim($pair[0]), false, trim($pair[1]));
					} else {
						return $value;
					}
					
				default:					
					return $value;
					
			}
		}
		return false;		
	}
	
	
}

?>