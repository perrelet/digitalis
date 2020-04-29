<?php

namespace Digitalis\Util;

use DOMDocument;

class Meta extends Utility {
	
	public static function repeater_class ($name, $number) {
		return $name . "-" . $number;
	}
	
	public static function static_field_value ($field, $static, $id = "") {
		if ($static) {
			return $static;
		} else {
			return self::field_value ($field, false, $id);
		}
	}
	
	public static function field_value ($field, $subfield = false, $id = "") {
		
		if ($subfield) {
			if ($id == "") {
				$field_obj = get_sub_field_object($field);
			} else {
				$field_obj = get_sub_field_object($field, $id);
			}
		} else {
			if ($id == "") {
				$field_obj = get_field_object($field);
			} else {
				$field_obj = get_field_object($field, $id);
			}
		}
		
		//print_r($field_obj);
		
		if ($field_obj) {
			if (is_array($field_obj['value'])) {
				switch ($field_obj['type']) {
					case 'file':
						return $field_obj['value']['url'];
					case 'image':
						return $field_obj['value']['url'];
					case 'link':
						return self::remove_http($field_obj['value']['url']);
					case 'page_link':
						return self::remove_http($field_obj['value']);
					default:
						return $field_obj['value'];	
				}
			} else {
				return $field_obj['value'];	
			}
		} else {
			return false;
		}
	}
	
	public static function remove_http ($string) {
		return str_replace("http://", "", str_replace("https://", "", $string));
	}
	
	public static function loop_subfields($field, $content, $styleDepth = 0, $wrap = array("", "")) {
		
		$template = explode(DIGITALIS_REPEAT_DELIMITER, $content);
		$html = "";
		
		$n = 0;
		
		while (have_rows($field)) {	
			the_row();
			$parts = self::parse_subfields ($template);
			
			if ($styleDepth > 0) {
			
				libxml_use_internal_errors(true);
				$doc = new DOMDocument();
				$doc->loadHTML(implode("", $parts), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
				
				$child = $doc->childNodes->item(0);
				for ($i = 1; $i < $styleDepth; $i++) { $child = $child->childNodes->item(0); }
				
				$classes = $child->getAttribute("class");
				$child->setAttribute("class", $classes . " " . self::repeater_class($field, $n));
				
				$html .= $wrap[0] . $doc->saveHTML() . $wrap[1];
				
			} else {
				
				$html .= $wrap[0] . implode("", $parts) . $wrap[1];
			}
			
			$n++;
		}
		
		return $html;
		
	}
	
	public static function parse_subfields ($template) {
		
		for ($i = 1; $i < count($template); $i+=2) {
			$template[$i] = self::field_value($template[$i], true);
		}	
		return $template;
	}
	
	public static function RGBA ($rgb_field, $a_field) {
		
		$rgb = self::field_value($rgb_field);		
		$a = self::field_value($a_field);
		
		if (is_numeric($a)) {
			$a = max(min($a, 1), 0);
			number_format((float) $a, 2, '.', ''); 
		}
		
		list($r, $g, $b) = sscanf($rgb, "#%02x%02x%02x");

		return "rgba($r, $g, $b, $a)";
	
	}
	
}

?>