<?php

namespace Digitalis\Shortcode\Meta;

use DOMDocument;

class Unslider extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		if ($this->is_builder()) return $content;
		
		if(!have_rows($a['field'])) {
			return $content;
		} else {
			
			libxml_use_internal_errors(true);
			$doc = new DOMDocument();
			$doc->loadHTML($content);
			$sliders = $doc->getElementsByTagName("ul");
			$slider = $sliders->item(0);
			
			if ($sliders->length == 0 ) {
				return $content . "A slider could not be found.";
			} else {
				$slides = $slider->getElementsByTagName('li');
				if ($slides->length == 0 ) {
					return $content . "A template slide could not be found.";
				} else {
										
					$html = "";
					if ($a['subfield']) {
						$n = count(get_sub_field($a['field']));
					} else {
						$n = count(get_field($a['field']));
					}
					
					$slide0 = $slides->item(0);
					
					/* $slider->setAttribute("style", $slider->getAttribute("style") .
						"width: " . 100 * $n . "%"
					); 
					$slide0->setAttribute("style", $slide0->getAttribute("style") .
						"width: " . 100 / $n . "%"
					); */
					
					$slidesToRemove = [];
					foreach ($slides as $slide) $slidesToRemove[] = $slide;
					foreach ($slidesToRemove as $slide) $slider->removeChild($slide);					
					
					$html = $this->loop_subfields($a['field'], $doc->saveHTML($slide0), 2);
					
					$fragment = $doc->createDocumentFragment();
					$fragment->appendXML($html);
					
					$slider->appendChild($fragment);
					
					$classes = $slider->getAttribute('class');	
					$classes .= " slider-instance-" . $this->get_instance();
					if ($a['class']) $classes .= " " . $a['class'];
					$slider->setAttribute('class', $classes);
					
					return $doc->saveHTML();
					
					//$placeholder = $doc->createTextNode(DIGITALIS_REPEAT_DELIMITER);
					//$slider->appendChild($placeholder);
					//return str_replace(DIGITALIS_REPEAT_DELIMITER, $html, $doc->saveHTML());

				}
			}
		}
	}
	
	function get_options() {
		return array(
			'tag' => 'metaunslider',
			'atts' => array(
				'field'		=> '',
				'subfield'	=> false,
				'class'		=> false,
			),
			'required' => array(
				'field',
			),
		);
	}
}