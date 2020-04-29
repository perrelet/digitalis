<?php

namespace Digitalis\Util;

class Marker extends Utility {
	
	public static function get_flex_container () {
		$style = "width: 100%; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: center;";
		return array("<div style='$style'>", "</div>");
	}
	
	public static function get_flex_element ($columns = 3, $margin = 0, $minwidth = 300, $style = "") {
		$styles = 
			"width: calc(" . 100 / $columns . "% - " . $margin . "px - " . $margin . "px);" .
			"margin: " . $margin . "px;" .
			"min-width: " . $minwidth . "px;" .
			"flex: auto;";
			
		return array("<div style='$styles $style'>", "</div>");
	}
}

?>