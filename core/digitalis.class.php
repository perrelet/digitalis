<?php

namespace Digitalis;
use Digitalis\Manager\Asset_Manager;
use Digitalis\Admin\Admin;
use Digitalis\Module\Module_Manager;

class Digitalis {

	private $plugin_name = DIGITALIS_PLUGIN_SLUG;
	private $version;
	private $admin = false;
	private $asset_manager;
	private $shortcodes;
	private $layouts;
	private $module_manager;

	public function __construct() {
		
		$this->version = (defined('DIGITALIS_VERSION') ? DIGITALIS_VERSION : '1.0.0');
		$this->shortcodes = [];
		$this->layouts = [];
		
	}
	
	public function run() {
		
		$this->register_hooks();
		
		$this->define_structs();
		$this->manage_assets();
		$this->load_utilities();
		$this->load_shortcodes();
		$this->load_globals();
		
		if (is_admin()) $this->load_admin();
		$this->load_modules();
		
		if (is_admin()) add_action( 'init', [$this->admin, 'run'] );
		
		$this->load_vendors();
		
	}
	
	private function define_structs() {
		
		require_once(DIGITALIS_PATH . "core/struct/js_loader.class.php");
		
	}
	
	private function manage_assets() {
		
		require_once(DIGITALIS_PATH . "core/manager/asset_manager.class.php");
		$this->asset_manager = new Asset_Manager();
		
	}
	
	private function load_utilities() {
		
		require_once(DIGITALIS_PATH . "core/util/utility.class.php");
		
		require_once(DIGITALIS_PATH . "core/util/coder.class.php");		
		require_once(DIGITALIS_PATH . "core/util/debugger.class.php");		
		require_once(DIGITALIS_PATH . "core/util/marker.class.php");
		require_once(DIGITALIS_PATH . "core/util/media.class.php");
		require_once(DIGITALIS_PATH . "core/util/meta.class.php");
		require_once(DIGITALIS_PATH . "core/util/styler.class.php");
		
	}
	
	private function load_shortcodes() {
			
		require_once(DIGITALIS_PATH . "core/shortcode/fixed.class.php");
		require_once(DIGITALIS_PATH . "core/shortcode/meta.class.php");
		require_once(DIGITALIS_PATH . "core/shortcode/dynamic.class.php");
		
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Audio"			, "core/shortcode/meta/audio.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Hide"			, "core/shortcode/meta/hide.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Hyperlink"		, "core/shortcode/meta/hyperlink.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Remove"		, "core/shortcode/meta/remove.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Repeat"		, "core/shortcode/meta/repeat.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Style"			, "core/shortcode/meta/style.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Style_Repeat"	, "core/shortcode/meta/style-repeat.class.php");	
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Unslider"		, "core/shortcode/meta/unslider.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Value"			, "core/shortcode/meta/value.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Video"			, "core/shortcode/meta/video.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Video5"		, "core/shortcode/meta/video5.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Meta\\" , "Videos"		, "core/shortcode/meta/videos.class.php");
		
		$this->instantiate_shortcode("Digitalis\Shortcode\Fixed\\", "AOS"			, "core/shortcode/fixed/aos.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Fixed\\", "AOS_Sentence"	, "core/shortcode/fixed/aos-sentence.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Fixed\\", "Repeat_Slider"	, "core/shortcode/fixed/repeat-slider.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Fixed\\", "Tilt"			, "core/shortcode/fixed/tilt.class.php");
		
		$this->instantiate_shortcode("Digitalis\Shortcode\Dynamic\\", "Canvas"		, "core/shortcode/dynamic/canvas.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Dynamic\\", "SVG"			, "core/shortcode/dynamic/svg.class.php");
		$this->instantiate_shortcode("Digitalis\Shortcode\Dynamic\\", "Vivus"		, "core/shortcode/dynamic/vivus.class.php");
		
	}
	
	private function instantiate_shortcode($prefix, $target, $path) {
		
		require_once(DIGITALIS_PATH . $path);
		$class = $prefix . $target;
		$this->shortcodes[$target] = new $class($this);
		
	}
	
	private function load_globals() {
		
		require_once(DIGITALIS_PATH . "core/global/utils.class.php");	
		
	}
	
	private function load_vendors() {
				
	}
	
	private function load_modules() {
		
		require_once(DIGITALIS_PATH . "core/module/module_manager.class.php");	
		require_once(DIGITALIS_PATH . "core/module/module_loader.class.php");	
		require_once(DIGITALIS_PATH . "core/module/module.class.php");	
		
		$this->module_manager = new Module_Manager ($this, DIGITALIS_MODULE_PATH);
		
	}
	
	private function load_admin() {
		
		require_once(DIGITALIS_PATH . "core/admin/admin.class.php");
		require_once(DIGITALIS_PATH . "core/admin/option/manager.class.php");
		require_once(DIGITALIS_PATH . "core/admin/option/field.class.php");
		require_once(DIGITALIS_PATH . "core/admin/option/form.class.php");
		require_once(DIGITALIS_PATH . "core/admin/option/tab.class.php");
		require_once(DIGITALIS_PATH . "core/admin/option/page.class.php");
		
		require_once(DIGITALIS_PATH . "core/admin/user/user_meta.class.php");
		
		require_once(DIGITALIS_PATH . "core/admin/update/updater.class.php");
		
		$this->admin = new Admin($this);
		
	}
	
	private function register_hooks (){
		register_activation_hook( DIGITALIS_ROOT_FILE, [$this, 'activate'] );
		register_deactivation_hook( DIGITALIS_ROOT_FILE, [$this, 'deactivate'] );
	}
	
	//SELF KNOWLEDGE
	
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}
	
	public function get_asset_manager() {
		return $this->asset_manager;
	}
	
	public function get_admin() {
		return $this->admin;
	}
	
	//PUBLIC
	
	public function Shortcode($shortcode, $atts = [], $content = "") {
		if (array_key_exists($shortcode, $this->shortcodes)){
			echo $this->shortcodes[$shortcode]->run($atts, $content);
			return true;
		} else {
			return false;
		}
	}
	
	public function layout ($layout) {
		
		$this->layouts[$layout] = true;
		require (DIGITALIS_PATH . "core/layout/" . $layout . ".php");
		
	}
	
	public function has_access () {
		
		return current_user_can(DIGITALIS_ADMIN_CAP);
		
	}
	
	// HOOKS
	
	public function activate () {
		require (DIGITALIS_PATH . "core/activate.class.php");
		new Activate();
	}
	
	public function deactivate () {
		require (DIGITALIS_PATH . "core/deactivate.class.php");
		new Deactivate();
	}

	// BRANDING

	public function branding ($options = []) {

		$options = wp_parse_args([
			'message'		=> false,
			'color'			=> '#ffffff',
			'hover_color'	=> '#ffffff',
			'logo'			=> 'digitalis-web-build-co.current',
			'width'			=> 200,
			'opacity' 		=> 0.8,
			'hover_opacity' => 0.9,
			'transition'	=> 0.1,
		], $options);

		$ref = get_site_url();

		echo "<a class='digitalis-branding' href='https://digitalis.ca/?ref={$ref}' target='_blank'>";
			if ($options['message']) echo "<div class='digitalis-branding--message'>{$options['message']}</div>";
			if ($options['logo']) echo file_get_contents(DIGITALIS_PATH . "assets/logo/{$options['logo']}.svg");
		echo "</a>";

		echo "<style>";

			echo ".digitalis-branding {";
				echo "display: block;";
				echo "width: 100%;";
				echo "max-width: {$options['width']}px;";
				echo "transition: {$options['transition']}s !important;";
				echo "opacity: {$options['opacity']};";
				echo "color: {$options['color']} !important;";
			echo "}";

			echo ".digitalis-branding .digitalis-branding--message {";    
				echo "text-transform: uppercase;";
				echo "font-size: 12px;";
				echo "letter-spacing: 1px;";
				echo "margin-bottom: 2px;";
				echo "line-height: 1.4;";
				echo "padding-left: 12%;";
			echo "}";

			echo ".digitalis-branding svg {";
				echo "display: block;";
				echo "width: 100%;";
				echo "height: auto;";
			echo "}";

			echo ".digitalis-branding:hover, .digitalis-branding:active {";
				echo "opacity: {$options['hover_opacity']};";
				echo "color: {$options['hover_color']} !important;";
			echo "}";

		echo "</style>";

	}

}
