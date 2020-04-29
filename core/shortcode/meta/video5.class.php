<?php

namespace Digitalis\Shortcode\Meta;

use Digitalis\Struct\JS_Loader;

class Video5 extends \Digitalis\Shortcode\Meta {
	
	function shortcode($a, $content = null, $name = null) {
		
		$static = $a['static'];
		$cover = $a['cover'];
		$mode = $a['mode'];
		
		$js_loader = new JS_Loader("assets/js/", "html5-video.min.js");
		$js_loader->params_name = "html5_video_params";
		$js_loader->params = ["cover" => $cover];
		$this->load_js($js_loader);
		
		//$video_url = "https://www.fractalteapot.com/mp4/fractals/Fractal-Teapot-(c)2016-Hyperbolic-Flower-of-Life-Loop-2-1080p_1.mp4";//$this->static_field_value($a['video'], $static);
		$video_url = $this->static_field_value($a['video'], $static);
		if ($cover) {
			$cover_url = $this->static_field_value($a['cover'], $static);
			$cover_color = $a['color'];
			
			$cover_style  = "background-image: url($cover_url);";
			$cover_style .= "background-color: $cover_color;";
			$cover_style .= "transition: opacity ease " . $a['fade'] . ";";
			$cover_style .= "background-size: cover;";
			$cover_style .= "background-position: center center;";
			$cover_style .= "pointer-events: none;";
			
			if (($mode == 'fill-screen-fixed') || ($mode == 'fill-screen')) {
				$cover_style .= "width: 100vw;";
				$cover_style .= "height: 100vh;";
				$cover_style .= "max-width:100%;";
				$cover_style .= "top: 0;";
				$cover_style .= "left: 0;";
				$cover_style .= "z-index:-1;";
			} else {
				$cover_style .= "width: 100%;";
				$cover_style .= "height: 100%;";
			}
			
			if ($mode == 'fill-screen-fixed') {
				$cover_style .= "position: fixed;";
			} else {
				$cover_style .= "position: absolute;";
			}
			
		}
		
		$video_style = "";
		$wrap_style = "height:100%;";
		
		switch ($mode) {
			case 'fill-screen-fixed':
				$video_style .= "left: 50%;position: fixed;top: 50%;transform: translate(-50%, -50%);min-width: 100%;min-height: 100%;z-index:-2;";
				break;
			case 'fill-screen':
				$video_style .= "width: 100vw; height: 100vh; position: absolute;left: 0;top: 0;object-fit: cover;z-index: -2;";
				break;
			case 'fill-parent':
				$video_style .= "width:100%;height: 100%;object-fit: cover;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);";
				$wrap_style .= "overflow: hidden; position: relative; width:100%;";
				break;
			default:
				$video_style .= "width:100%";
		}
		


		$video_atts = "";
		if ($a['autoplay']) 	$video_atts .= " autoplay";
		if ($a['mute']) 		$video_atts .= " muted";
		if ($a['loop']) 		$video_atts .= " loop";
		if ($a['controls']) 	$video_atts .= " controls";
		//if ($cover) 	$video_atts .= " poster='" . $cover_url . "'"; //Doesn't fill div
		
		$id = ($a['id'] ? $a['id'] : "html5_video_" . $this->instance);
		
		$classes = "html5_video";
		if ($a['class']) $classes .= " " . $a['class'];
	
		$html  = "";
		$html .= "<div class='html5_video_wrap' style='$wrap_style'>";
		$html .= "<video $video_atts class='$classes' id='$id' style='$video_style'>";
		$html .= "<source src='$video_url' type='video/mp4'>";
		$html .= "</video>";
		if ($cover) $html .= "<div class='html5_video_cover' style='$cover_style'></div>";
		$html .= "</div>";
		
		
		return $html;
	}
	
	//fill-screen 	fill-screen-fixed 	fill-parent 	normal//
	
	function get_options() {
		return array(
			'tag' => 'metavideo5',
			'atts' => array(
				'video'			=> 'video_url',
				'cover'			=> false,
				'color'			=> '#ffffff',
				'fade'			=> '1s',
				'mode'			=> 'fill-screen-fixed',
				'id'			=> false,
				'class'			=> false,
				'static'		=> false,
				'autoplay'		=> true,
				'loop'			=> true,
				'mute'			=> true,
				'controls'		=> false,
				
			),
			'required' => array(
			),
		);
	}
}

/*Note: If using as fullscreen background video -> set shortcode / codeblock = height: 100vh, overflow: hidden*/
