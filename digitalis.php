<?php

/**
 * Plugin Name:       Digitalis Web Design
 * Plugin URI:        http://www.digitaliswebdesign.com/
 * Description:       “Foxglove, Foxglove, What do you see?” The cool green woodland, The fat velvet bee; Hey, Mr Bumble, I’ve honey here for thee!
 * Version:           2.7.9
 * Author:            Digitalis Web Design
 * Author URI:        http://www.digitaliswebdesign.com/
 * Text Domain:       digitalis
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/* DEFINES */
 
define( 'DIGITALIS_VERSION', 			'2.7.9' );

define(	'DIGITALIS_PATH', 				plugin_dir_path( __FILE__ ) );
define( 'DIGITALIS_URI',				plugin_dir_url( __FILE__ ) );
define( 'DIGITALIS_ROOT_FILE',			__FILE__ );
define( 'DIGITALIS_PLUGIN_BASE',		plugin_basename(__FILE__) );					//digitalis/digitalis.php
define( 'DIGITALIS_PLUGIN_SLUG', 		basename( DIGITALIS_PLUGIN_BASE, '.php' ));		//digitalis
define( 'DIGITALIS_MODULE_PATH',		DIGITALIS_PATH . "modules/" );

define( 'DIGITALIS_REPEAT_DELIMITER',	"%%%");
define( 'DIGITALIS_ARRAY_DELIMITER',	",");
define( 'DIGITALIS_VALUE_DELIMITER',	":");

define( 'DIGITALIS_SYMBOL_FIELD',		"!");
define( 'DIGITALIS_SYMBOL_FIELD_ID',	"?");

define( 'DIGITALIS_HANDLE',				"digitalis_");
define( 'DIGITALIS_MODULE_HANDLE',		"module");

define( 'DIGITALIS_USER_META',			"digitalis_user_meta_");
define( 'DIGITALIS_OPTION',				"digitalis_option_");
define( 'DIGITALIS_OPTION_GROUP',		"digitalis_option_group_");
define( 'DIGITALIS_VALUE_TRUE',			"true");
define( 'DIGITALIS_VALUE_FALSE',		"");

define( 'DIGITALIS_ADMIN_CAP',			"digitalis");

//

require_once DIGITALIS_PATH . 'core/digitalis.class.php';

function _D() {
	global $Digitalis;
	return $Digitalis;
}

$Digitalis = new Digitalis\Digitalis();
$Digitalis->run();

//

if (!function_exists('dprint')) {
	function dprint ($content, $pre = true) {
		Digitalis\Util\Debugger::printer($content, $pre);
	}
}

if (!function_exists('jprint')) {
	function jprint ($content, $label = false) {
		Digitalis\Util\Debugger::console($content, $label);
	}
}

if (!function_exists('dlog')) {
	function dlog ($content, $label = false) {
		Digitalis\Util\Debugger::logger($content, $label);
	}
}
