<?php

namespace Digitalis\Module\Site_Admin;

use Digitalis\Module\Module;

class Site_Admin extends Module {
	
	private $role_name = 'site-admin';
	private $role_label = 'Site Admin';
	
	public function run () {
		
		register_activation_hook  ( DIGITALIS_ROOT_FILE, [$this, 'register_role'] );
		register_deactivation_hook  ( DIGITALIS_ROOT_FILE, [$this, 'unregister_role'] );
		
		add_action('set_user_role', 		[$this, 'set_user_role'], 10, 3);
		add_action('activated_plugin', 		[$this, 'sync_capabilities']);
		add_action('deactivated_plugin', 	[$this, 'sync_capabilities']);
		
		if (class_exists('acf')) add_filter('acf/settings/show_admin', [$this, 'acf_admin']);	
		
	}
	
	public function register_role () {
		
		global $wp_roles;
		if ( !isset( $wp_roles ) ) $wp_roles = new WP_Roles();

		$admin_caps = $wp_roles->get_role('administrator')->capabilities;
		
		$admin_caps['administrator'] = 1;
		if (isset($admin_caps[DIGITALIS_ADMIN_CAP])) unset($admin_caps[DIGITALIS_ADMIN_CAP]);
		
		$wp_roles->add_role($this->role_name, $this->role_label, $admin_caps);
		
		$users = get_users();
		foreach ($users as $user) {
			
			if ( in_array( $this->role_name, (array) $user->roles ) ) {
				$this->update_user_meta($user->ID, 'site_admin', 1);
			}
			
			if ($this->get_user_meta($user->ID, 'site_admin', true)) {
				$user->add_role($this->role_name);
				$user->remove_role("administrator");
			}
			
		}

	}
	
	public function sync_capabilities () {
		
		global $wp_roles;
		if ( !isset( $wp_roles ) ) $wp_roles = new \WP_Roles();
		
		$site_admin = get_role($this->role_name);
		if (!$site_admin) return;
		
		foreach ($wp_roles->get_role('administrator')->capabilities as $capability => $value) {
			
			if ($capability == DIGITALIS_ADMIN_CAP) continue;
			$site_admin->add_cap($capability, $value);
			
		}
		
	}
	
	public function unregister_role () {
		
		$users = get_users();
		foreach ($users as $user) {
			
			if ( in_array( $this->role_name, (array) $user->roles ) ) {
				$user->remove_role($this->role_name);
				$user->add_role("administrator");
				$this->update_user_meta($user->ID, 'site_admin', 1);
			}
			
		}
		
		global $wp_roles;
		if ( !isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		
		$wp_roles->remove_role($this->role_name);
		
	}
	
	public function set_user_role ($user_id, $role, $old_roles) {
				
		if ($role == $this->role_name) {
			$this->update_user_meta($user_id, 'site_admin', 1);
		} else {
			foreach ($old_roles as $old_role) {
				if ($old_role == $this->role_name) {
					if ($this->get_user_meta($user_id, 'site_admin', true)) {
						$this->update_user_meta($user_id, 'site_admin', 0);
					}
				}
			}			
		}
				
	}
	
	//Vendor Specific
	
	public function acf_admin () {
		
		$user = wp_get_current_user();
		$site_admin = in_array($this->role_name, (array) $user->roles );

		return current_user_can('manage_options') && !$site_admin;
		
	}
	
}

new Site_Admin();