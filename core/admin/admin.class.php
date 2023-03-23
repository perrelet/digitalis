<?php

namespace Digitalis\Admin;

use Digitalis\Admin\Option\Field;
use Digitalis\Admin\Option\Page;
use Digitalis\Admin\Option\Tab;

use Digitalis\Admin\Update\Updater;

class Admin {
	
	private $digitalis;
	private $updater;
	private $page_manager;
	
	function __construct ($digitalis) {
		
		$this->digitalis = $digitalis;
		$this->updater = new Updater();
		$this->page_manager = new Option\Manager();

	}
	
	public function run () {
		
		$this->add_actions();
		$this->add_pages();
		
	}
	
	private function add_actions() {
		
		add_action( 'admin_enqueue_scripts', [$this, 'style'] );
		
	}
	
	public function style() {
		wp_enqueue_style(
			'digitalis_admin',
			DIGITALIS_URI . 'assets/admin/style.css',
			[],
			DIGITALIS_VERSION
		);
	}
	
	private function add_pages () {
		
		$this->page_manager->add_page(new Page("Digitalis", "digitalis"));

		$this->page_manager->add_tab(new Tab("Modules", "modules"));

		do_action( 'Digitalis\Options\Modules\Field', $this->page_manager );
		
		$this->page_manager->add_tab(new Tab("Test Tab", "test_tab"))
		->add_field(new Field("Test Option 1", "this_test_option", "checkbox", DIGITALIS_VALUE_TRUE))
		->add_field(new Field("Test Option 2", "this_test_option_2", "checkbox", DIGITALIS_VALUE_TRUE));
		
		do_action( 'Digitalis\Options\Modules\Tab', $this->page_manager );
		
		$this->page_manager->add_tab(new Tab("Updates", "updates", false))
		->add_action([$this, "check_for_updates"]);
		
	}
	
	public function check_for_updates () {
		
		delete_transient( 'update_plugins' );
		
		echo "<h1>Updates</h1>";
		
		/* $this->updater->delete_transient();

		dprint($this->updater->request()); */

		$update = $this->updater->check_for_updates(false);
		if ($update) {
			
			echo "<h3>A new version of digitalis is available!</h3>";
			echo "<div>Current: " . DIGITALIS_VERSION . "</div>";
			echo "<div>Update: " . $update->version . "</div>";
			echo "<div><a href='/wp-admin/update-core.php'>UPDATE PAGE</a></div>";
			
			//print_r($update);
			
			
			
		} else {
			
			echo "<div>You're all up to date Sunny Jim!</div>";
			
		}
		
	}

}