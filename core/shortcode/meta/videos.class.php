<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Util\Marker;
use Digitalis\Util\Media;

class Videos extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		if(have_rows($a['field'])) {
			
			$container = Marker::get_flex_container();
			$element = Marker::get_flex_element($a['columns'], $a['margin'], $a['min-width']);
			
			$html = $container[0];
			
			$i = 0;
			while (have_rows($a['field'])) {
				the_row();
				
				$video = Media::video(
					$this->field_value($a['id'], true),
					$this->field_value($a['service'], true),
					true,
					false,
					$a['aspect'],
					[]
				);
				if ($video) {
					if (($i == 0) && ($a['feature'])) {
						$feature = Marker::get_flex_element($a['columns'], $a['margin'], $a['min-width'], "flex-basis: 100%;");
						$html .= $feature[0] . $video . $feature[1];
					} else {
						$html .= $element[0] . $video . $element[1];
					}
					
				}					
				
				$i++;
			}
			
			$html .= $container[1];
			
			return $html;
			
		}
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metavideos',
			'atts' => array(
				'field'		=> 'video_gallery',
				'title'		=> 'video_title',
				'id'		=> 'video_id',
				'service'	=> 'video_service',
				'aspect'	=> 16 / 9,
				'columns'	=> 3,
				'margin'	=> 10,
				'min-width'	=> 300,
				'feature'	=> false
			),
			'required' => array(
			),
		);
	}
	
}

?>