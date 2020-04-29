<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Util\Media;

class Video extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		$videoID = $this->field_value($a['field']);
		$service = $a['service'];
		$settings = array();
		
		if ($a['background']) {
			$settings["autoplay"] = 1;
			$settings["loop"] = 1;
			$settings["modestbranding"] = 1;
			$settings["controls"] = 0;
		}
		
		foreach ($this->arrayify($a['settings']) as $s) {
			
			if (!$s) continue;
			
			$pair = explode("=", $s);
			if (count($pair) == 1) {
				$settings[$pair[0]] = "1";
			} else {
				$settings[$pair[0]] = $pair[1];
			}
		}

		//Workaround for looping youtube videos.
		//See: https://developers.google.com/youtube/player_parameters
		if ($service == "youtube" && array_key_exists("loop", $settings)) {
			if (!($settings["loop"] == "0")) {
				$settings["playlist"] = $videoID;
			}
		}
		
		$video = Media::video(
			$videoID,
			$a['service'],
			$a['responsive'],
			$a['background'],
			$a['aspect'],
			$settings
		);
		if ($video) return $video;	
		//if ($video) $html .= $element[0] . $video . $element[1];	
		
	}
	
	function get_options() {
		return array(
			'tag' => 'metavideo',
			'atts' => array(
				'field'			=> 'video_id',
				'aspect'		=> 16 / 9,
				'settings'		=> '',
				'responsive'	=> true,
				'background'	=> false,
				'service'		=> 'youtube'
			),
			'required' => array(
			),
		);
	}
	
}

/* 
	SETTINGS ARG
	YT & VIM:			autoplay, controls, loop, 
	YT only: 			modestbranding
	[metavideo field="youtube_id" settings="autoplay, loop=1,modestbranding=1,controls=0"]
	*/

?>