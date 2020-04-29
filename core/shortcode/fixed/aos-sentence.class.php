<?php

namespace Digitalis\Shortcode\Fixed;

use Digitalis\Util\Styler;

class AOS_Sentence extends \Digitalis\Shortcode\Fixed {
	
	private $index, $ani_delay, $char_delay;
	
	function shortcode($a, $content = null, $name = null) {
		
		$base_class = "aos-sentence";
		$animation_class = "ani";
		$unique_class = $base_class . "-" . $this->instance;
		
		if ($this->is_first()) {
			Styler::inline (".$base_class span", "display", "inline-block", false);
		}
		
		Styler::inline (".$unique_class span", "transition-duration", $a["duration"]."ms", false);
		Styler::inline (".$unique_class span", "transition-timing-function", $a["easing"], false);
		
		$word_mode = ($a['mode'] == "word");
		
		$html = "<div class='$base_class $unique_class' ";
		$html .= "data-aos='" . 				$a["ani"] 		. "' ";
		$html .= "data-aos-offset='" . 			$a["offset"] 	. "' ";
		$html .= "data-aos-once='" . 			$a["once"] 		. "' ";
		$html .= "data-aos-mirror='" . 			$a["mirror"] 	. "' ";
		$html .= "data-aos-anchor-placement='".	$a["anchor"] 	. "' ";
		$html .= ">";
		
		$charArr = str_split(html_entity_decode($content));
		$tag = false;
		
		$i = 0;
		
		$this->index = 0;
		$this->ani_delay = $a['ani-delay'];
		$this->char_delay = $a['delay'];

		$word = "";
		$word_class = ($word_mode ? $animation_class : false);
		
		foreach ($charArr as $char) {
			
			if ($char == "<") {
				
				$tag = true;
				
				if (!($word == "")) {
					if ($word_mode) {
						$html .= " " . $this->span_wrap($word, $animation_class);
					} else {
						$html .= " " . "<span>" . $word . "</span>";
					}
				}
				$word = "";
			}
			
			if ($tag) {
				
				$html .= $char;
				
			} else {
				
				if ($char == " ") {
					if ($word_mode) { 
						$html .= " " . $this->span_wrap($word, $animation_class);
					} else {
						$html .= " " . "<span>" . $word . "</span>";
					}
					$word = "";
				} else {
					
					if ($word_mode) { 
						$word .= htmlentities($char);
					} else {
						$word .= $this->span_wrap(htmlentities($char), $animation_class);
					}
					
				}
			}
			
			if ($char == ">") $tag = false;

		}
		
		return $html . "</div>";
		
	}
	
	private function span_wrap($txt, $class = false) {
		
		$delay = $this->ani_delay + $this->index * $this->char_delay;
		$this->index++;	
		$style = "transition-delay: " . $delay . "ms;";
		$style .= "animation-delay: " . $delay . "ms;";
		
		$class = ($class ? "class='$class'" : "");
		
		return "<span $class style='$style'>" . $txt . "</span>";
		
	}
	
	function get_options() {
		return array(
			'tag' => 'aos-sentence',
			'atts' => array(
				'ani'		=> '',
				'mode'		=> 'char',
				'delay'		=> '25',
				'offset'	=> '120',
				'ani-delay' => '0',
				'duration' 	=> '400',
				'easing' 	=> 'ease',
				'mirror' 	=> 'false',
				'once' 		=> 'false',
				'anchor' 	=> 'top-bottom',
			),
			'required' => array(
				'ani'
			),
		);
	}
	
}

