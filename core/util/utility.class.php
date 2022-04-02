<?php

namespace Digitalis\Util;

class Utility {
	
	public static function get_file ($path, $filetype, $localize = true, $replaces = []) {
		
		$path = substr($path, 0, -1 * strlen($filetype)) . $filetype;
		
		if ($localize) $path = DIGITALIS_PATH . $path;
		
		//Should we check if resource exists first?
		$content = file_get_contents($path);
		
		foreach ($replaces as $find => $replace) $content = str_replace($find, $replace, $content);
		
		return $content;
		
	}
	
}