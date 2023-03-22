<?php

namespace Digitalis\Admin\Update;

use stdClass;

class Updater {
	
	//private $api = 'https://digitaliswebdesign.com/update/digitalis.json';
	private $api = 'https://digitalis.ca/plugins/update/digitalis/info';
	private $transient_name = 'digitalis_upgrade';
	
	function __construct() {
		
		//add_filter('plugins_api', [$this, 'update_info'], 20, 3); //No longer works 'plugin not found'
		add_filter('pre_set_site_transient_update_plugins', [$this, 'update'] );
		add_action('upgrader_process_complete', [$this, 'after_update'], 10, 2 );

	}
	
	public function get_remote ($use_transient = true) {
		
		/*if ( empty($transient->checked ) ) {
			return $transient;
		} else {
			remove_filter('pre_set_site_transient_update_plugins', [$this, 'update'] );
		}*/
		
		$remote = get_transient($this->transient_name);
		
		if((false == $remote) || !$use_transient) {
			
			//set_transient($this->transient_name, false, 0 );
			
			$remote = wp_remote_get(
				$this->api,
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json'
					)
				)
			);
			
			if (is_wp_error( $remote )) {
				
				return false;
				
			} else {
				
				if (isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
					set_transient($this->transient_name, $remote, 43200 );
				}

			}

		}
		
		if (isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
			return $remote;
		} else {
			return false;
		}
		
	}
	
	public function update_info( $res, $action, $args ){
		
		if($action !== 'plugin_information') return false;
		
		if($args->slug  !== DIGITALIS_PLUGIN_SLUG) return $res;
		
		$remote = $this->get_remote();
		
		if( $remote ) {
			
			$remote = json_decode( $remote['body'] );
			
			$res = new stdClass();
			
			$res->name = $remote->name;
			$res->slug = 'YOUR_PLUGIN_SLUG';
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = '<a href="https://rudrastyh.com">Misha Rudrastyh</a>'; // I decided to write it directly in the plugin
			$res->author_profile = 'https://profiles.wordpress.org/rudrastyh'; // WordPress.org profile
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->last_updated = $remote->last_updated;
			$res->sections = array(
				'description' => $remote->sections->description, // description tab
				'installation' => $remote->sections->installation, // installation tab
				'changelog' => $remote->sections->changelog, // changelog tab
				// you can add your custom sections (tabs) here 
			);
return $res;
			// in case you want the screenshots tab, use the following HTML format for its content:
			// <ol><li><a href="IMG_URL" target="_blank" rel="noopener noreferrer"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
			if( !empty( $remote->sections->screenshots ) ) {
				$res->sections['screenshots'] = $remote->sections->screenshots;
			}

			$res->banners = array(
				'low' => 'https://YOUR_WEBSITE/banner-772x250.jpg',
				'high' => 'https://YOUR_WEBSITE/banner-1544x500.jpg'
			);
			return $res;
			
		}
		
		return false;
		
	}
	
	public function is_new_version ($json) {
		
		if (!$json || !property_exists($json, "version")) return false;
		
		if (version_compare(DIGITALIS_VERSION, $json->version, '<')) {

			if (property_exists($json, "requires") && !version_compare($json->requires, get_bloginfo('version'), '<' )) {

				return false;

			} else {

				return true;

			}

			return true;

		} else {

			return false;

		}
		
	}
	
	public function remote_to_json ($remote) {
		return json_decode( $remote['body'] );
	}
	
	public function check_for_updates ($use_transient = true, $delete_transient = false) {
		
		if ($delete_transient) delete_transient($this->transient_name);
		
		$remote = $this->get_remote($use_transient);
		if(!$remote ) return false;
		
		$json = $this->remote_to_json($remote);
		if(!$json ) return false;
		
		if ($this->is_new_version($json)) {
			return $json;
		} else {
			return false;
		}
		
	}
	
	public function update ($transient) {
		
		$update = $this->check_for_updates(true);
		
		if ( $update ) {

			$res = new stdClass();
			
			$res->slug = DIGITALIS_PLUGIN_SLUG;
			$res->plugin = DIGITALIS_PLUGIN_BASE;
			$res->new_version = $update->version;
			if (property_exists($update, 'tested')) $res->tested = $update->tested;
			if (property_exists($update, 'download_url')) $res->package = $update->download_url;
			
			$transient->response[$res->plugin] = $res;
			//$transient->checked[$res->plugin] = $update->version;

		}
		
		return $transient;
		
	}
	
	function after_update ($upgrader_object, $options) {
		
		if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
			delete_transient($this->transient_name);
		}
		
	}
	
}