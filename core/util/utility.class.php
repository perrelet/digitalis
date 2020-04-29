<?php

namespace Digitalis\Util;

class Utility {
	
	public static function get_file ($path, $filetype, $localize = true ) {
		
		$path = substr($path, 0, -1 * strlen($filetype)) . $filetype;
		
		if ($localize) $path = DIGITALIS_PATH . $path;
		
		//Should we check if resource exists first?
		return file_get_contents($path);
		
	}
	
}