<?php

namespace Digitalis\Module\Log_JS;

class Log_JS {
	
	private $log_file;
	
	public function __construct() {
		
		$this->log_file = WP_CONTENT_DIR . '/debug.js.log';
		
		add_action( 'wp_enqueue_scripts', 			[$this, 'enqueue_script'] );
		add_action( 'admin_enqueue_scripts', 		[$this, 'enqueue_script'] );
		add_action( 'wp_ajax_js_log_error', 		[$this, 'log_js'] );
		add_action( 'wp_ajax_nopriv_js_log_error', 	[$this, 'log_js'] );
		
	}

	public function enqueue_script() {
		
		wp_enqueue_script( 'js-error-log', plugin_dir_url( __FILE__ ) . 'log_js.js', [], DIGITALIS_VERSION );
		wp_localize_script( 'js-error-log', 'admin_ajax_object', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		
	}

	public function log_js() {
		
		$params = ['msg', 'line', 'url', 'width', 'height', 'platform', 'vendor'];
		
		$valid = true;
		foreach ($params as $param) {
			if (!isset( $_REQUEST[$param] )) {
				$valid = false;
				break;
			}
		}
		
		if ($valid) {
			
			$filter = [];
			foreach ($params as $param) {
				$filter[$param] = FILTER_SANITIZE_STRING;
			}
			
			$error = filter_input_array( INPUT_POST, $filter);
			
			$timestamp = date("[H:i:s d-M-Y]");
			
			$txt = $timestamp;
			$txt .= ' ' . $error['url'] . ':' . $error['line'];
			$txt .= ' >>> JavaScript Error: ' . html_entity_decode( $error['msg'], ENT_QUOTES );
			$txt .= ' [ Screen: ' . $error['width'] . ' x ' . $error['height'];
			$txt .= ' | Platform: ' . $error['platform'];
			$txt .= ' | Vendor: ' . $error['vendor'] . " ]";
			$txt .= "\n";
			
			error_log($txt, 3, $this->log_file);
			wp_send_json( $error );
		}
		//wp_die();
		
	}
	
}

new Log_JS();

