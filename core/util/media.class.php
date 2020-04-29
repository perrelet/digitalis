<?php

namespace Digitalis\Util;

class Media extends Utility {
	
	public static function video ($id, $service = 'youtube', $responsive = true, $background = false, $aspect = (16 / 9), $settings = array()) {
		
		$wrap = array("", "");
		
		if ($background) {
			$wrap = array("<div class='video-background'><div class='video-foreground'>", "</div></div>");
			$responsive = false;
			
			Styler::inline_file("assets/css/bgvideo.css");
			
		} else if ($responsive) {
			$wrap = array("<div style='position: relative; width: 100%; height: 0; padding-bottom: " . (100 / $aspect) . "%;'>", "</div>");
		}
		
		if ($service == "youtube") {
			return $wrap[0] . Media::youtube($id, $responsive, $settings) . $wrap[1];
		} elseif ($service == "vimeo") {
			return $wrap[0] . Media::vimeo($id, $responsive, $settings) . $wrap[1];
		}
		
		return false;
		
	}
	
	public static function youtube ($id, $responsive = true, $settings = array()) {

		$params = http_build_query($settings);
		
		$style = ($responsive) ? "position: absolute; top: 0; left: 0; width: 100%; height: 100%;" : "";
		return "<iframe style='$style' width='1252' height='704' src='https://www.youtube-nocookie.com/embed/$id?$params' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
		
	}
	
	public static function vimeo ($id, $responsive = true, $settings = array()) {
		
		$params = array("dnt" => "true");
		$params = array_merge($params, $settings);
		$params = http_build_query($params);
		
		$style = ($responsive) ? "position: absolute; top: 0; left: 0; width: 100%; height: 100%;" : "";
		return "<iframe style='$style' src='https://player.vimeo.com/video/$id?$params' width='1252' height='704' frameborder='0' allow='autoplay; fullscreen' allowfullscreen></iframe>";
			
	}	
	
	public static function SVG ($url = "", $inline = false, $id = "", $class = "", $style = "") {
		if ($inline) {
			//Should remove this functionality as it equates to another http call via get_file_contents
			return preg_replace("<!DOCTYPE((.|\n|\r)*?)\">", "", self::css(self::get_file($url, "svg", false)), 1);
		} else {
			return "<object id='$id' class='$class' style='width: 100%; $style' type='image/svg+xml' data='$url'></object>";
		}
	}
	
}

?>