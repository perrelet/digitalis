<?php

namespace Digitalis\Module\Oxy_Organiser;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Tab;

class Oxy_Organiser extends Module {
	
	protected $desc_key = 'digitalis_notes';
	
	protected $templates = null;
	
	//$ct_parent_template = get_post_meta( $post->ID, 'ct_parent_template', true );
	
	public function run () {
		
		add_action('init', function () {

			if (current_user_can(DIGITALIS_ADMIN_CAP)) {
		
				//Oxy
				add_action('admin_menu', [$this, 'admin_menu'], 99);
		
				//Templates
				
				add_filter('manage_ct_template_posts_columns', [$this, 'ct_custom_views_columns'], 100);
				add_action('manage_ct_template_posts_custom_column' , [$this, 'ct_custom_view_column'], 100, 2 );
				add_action('add_meta_boxes', [$this, 'add_meta_box']);
				add_action('save_post', [$this, 'save_meta_box'], 1, 2);
				
				//Pages
				
				add_filter('manage_pages_columns', [$this, 'manage_pages_columns']);
				add_action('manage_pages_custom_column', [$this, 'pages_custom_column'], 10, 2);
			
			}
		
		});
		
	}
	
	public function admin_menu () {
		
		global $submenu;
		
		$submenu['ct_dashboard_page'][] = [
			'Filter Templates',
			'manage_options',
			admin_url('edit.php?s&post_status=all&post_type=ct_template&action=-1&m=0&ct_template_type=template&filter_action=Filter&orderby=title&order=asc')
		];
		
		$submenu['ct_dashboard_page'][] = [
			'Filter Reusable',
			'manage_options',
			admin_url('edit.php?s&post_status=all&post_type=ct_template&action=-1&m=0&ct_template_type=reusable_part&filter_action=Filter&orderby=title&order=asc')
		];
		
	}
	
	public function ct_custom_views_columns ($columns) {
		
		$offset = 2;
		
		$columns = array_merge(
			array_slice($columns, 0, $offset),
			[
				'digitalis_inherits' 	=> 'Inheritance',
				'digitalis_notes' => 'Notes',
			],
			array_slice($columns, $offset, null)
		);
		
		return $columns;
		
	}
	
	public function ct_custom_view_column ($column, $post_id) {
		
		switch ($column) {
			
			case 'digitalis_inherits':
				//echo $this->get_templates()[get_post_meta($post_id, 'ct_parent_template', true)];
				
				$inheritance = $this->get_inheritance($post_id);
				
				//if (!is_null($template)) echo "<a href='" . get_edit_post_link($template->id) . "'>{$template->post_title}</a>";

				if ($inheritance) {
					
					foreach ($inheritance as $i => $template) {
						
						if ($i > 0) echo " ";
						echo "&lArr; ";
						echo "<a href='" . get_edit_post_link($template->id) . "'>{$template->post_title}</a>";
						
					}
					
				}

				
				break;
				
			case 'digitalis_notes':
				echo wp_trim_words(get_post_meta($post_id, $this->desc_key, true));
				break;
			
		}
			
	}
	
	public function manage_pages_columns ($columns) {
		
		$offset = 2;
		
		$columns = array_merge(
			array_slice($columns, 0, $offset),
			[
				'digitalis_template' 	=> 'Template'
			],
			array_slice($columns, $offset, null)
		);
		
		return $columns;
		
		
	}
	
	public function pages_custom_column ($column, $post_id) {
		
		switch ($column) {
			
			case 'digitalis_template':
				
				$template_id = get_post_meta($post_id, 'ct_other_template', true );

				if (empty($template_id)) {
					$page_template = ct_get_posts_template($post_id);
					$template_id = $page_template->ID;
				}
				
				$inheritance = $this->get_inheritance($template_id);
				
				echo "<a href='" . get_edit_post_link($template_id) . "'>" . get_the_title($template_id) . "</a>";
				
				if ($inheritance) {
				
					foreach ($inheritance as $i => $template) {
						
						echo " &lArr; <a href='" . get_edit_post_link($template->id) . "'>{$template->post_title}</a>";
						
					}
				
				}
				
				break;
				
				
		}
		
	}
	
	public function add_meta_box () {
	
		add_meta_box(
			'ct_template_digitalis_info',
			'Info',
			[$this, 'render_meta_box'],
			'ct_template',
			'side',
			'default'
		);	
		
	}
	
	public function render_meta_box () {
		
		global $post;

		wp_nonce_field( basename( __FILE__ ), 'digitalis' );

		$notes = esc_textarea(get_post_meta($post->ID, $this->desc_key, true));
		
		echo "<label>Template Notes:</label>";
		echo "<textarea name=$this->desc_key class='widefat'>{$notes}</textarea>";

		
	}
	
	public function save_meta_box ($post_id, $post) {

		if (!current_user_can( 'edit_post', $post_id)) return $post_id;
		if ('revision' === $post->post_type) return $post_id;
	
	
		if (!isset($_POST[$this->desc_key]) || ! wp_verify_nonce($_POST['digitalis'], basename(__FILE__))) return $post_id;
	
		$meta = [];
		$meta[$this->desc_key] = esc_textarea($_POST[$this->desc_key]);
		
		foreach ($meta as $key => $value) {

			update_post_meta($post_id, $key, $value);

			//if (!$value) delete_post_meta( $post_id, $key );

		}

	}
	
	protected function get_inheritance ($post_id, $inheritance = []) {
		
		$parent = $this->get_parent_template($post_id);
		
		if (is_null($parent)) {
			return $inheritance;
		} else {
			$inheritance[] = $parent;
			$inheritance = $this->get_inheritance($parent->id, $inheritance);
		}
		
		return $inheritance;
		
	}
	
	protected function get_parent_template ($post_id) {
		
		$template_type = get_post_meta($post_id, 'ct_template_type', true);
		
		if ($template_type == 'reusable_part') return null;
		
		$parent_id = get_post_meta($post_id, 'ct_parent_template', true);
		$templates = $this->get_templates();
		
		foreach ($templates as $template) {
			
			if ($template->id == $parent_id) return $template;
			
		}
		
		return null;
		
	}
	
	protected function get_templates () {
		
		if (is_null($this->templates)) {
			global $wpdb;
			$this->templates = $wpdb->get_results(
				"SELECT id, post_title
				FROM $wpdb->posts as post
				WHERE post_type = 'ct_template'
				AND post.post_status IN ('publish')"
			);
		}
		
		return $this->templates;
	}
	
	
}

new Oxy_Organiser();