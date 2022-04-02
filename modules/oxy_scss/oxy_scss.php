<?php

namespace Digitalis\Module\OXY_SCSS;

use Digitalis\Module\Module;
use Digitalis\Admin\Option\Tab;

class OXY_SCSS extends Module {

    protected $variables = [1];

    public function run () {

        $this->add_filters();
		$this->tabs('add_tab');

    }

    public function add_filters () {

        add_filter('scsslib_compiler_variables', [$this, 'compiler_variables'], 10, 1);
        add_filter('sassy-variables', [$this, 'compiler_variables'], 10, 1);

        add_action('admin_bar_menu', [$this, 'admin_bar_menu'], 10000, 1);

        if (isset($_GET['sassy-vars'])) add_action('wp_footer', [$this, 'print_variables']);
    
    }
	
	public function add_tab ($manager) {
		
		$tab = new Tab("SCSS Variables", "oxy_scss", false);
		$tab
		->set_capability(DIGITALIS_ADMIN_CAP)
		->add_action([$this, "render_tab"]);
		
		$manager->add_tab($tab);
		
	}
	
	public function render_tab () {
		
		wp_enqueue_style('digitalis_oxy_scss', plugin_dir_url( __FILE__ ) . 'css/oxy_scss.css', [], DIGITALIS_VERSION);
		
		$variables = $this->compiler_variables([]);
		
		echo "<h1>SCSS Variable Reference</h1>";
		
		echo "<p><b>Tip: </b>These variable references are also available in the browser console by navigating to any page and including 'sassy-vars' as a query parameter in the url. For example:</p>";
		
		$url = get_site_url() . "?sassy-vars=1";
		echo "<p><a href='$url' target='_blank'>$url</a></p>";
		
		echo "<table class='digitalis-scss-reference'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Name:</th>";
					echo "<th>Value:</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
			
			foreach ($variables as $name => $value) {
				
				echo "<tr>";
					echo "<td>$" . $name . "</td>";
					echo "<td>$value</td>";
				echo "</tr>";
				
			}
			
			echo "</tbody>";
		echo "</table>";
		
	}

    public function admin_bar_menu ($admin_bar) {

        if (!current_user_can('edit_theme_options')) return;

        $admin_bar->add_menu([
            'id'     => 'scss-vars',
            'parent' => 'sassy',
            'title'  => __('Log SCSS Variables', 'sassy'),
            'href'   => add_query_arg('sassy-vars', true),
        ]);

    }

    public function print_variables () {

        $variables = json_encode($this->variables);
        echo "<script>console.log($variables);</script>";

    }

    public function compiler_variables ($variables) {

        //error_log(print_r(oxy_get_global_colors(), true));

        $variables = $this->colors($variables);
        $variables = $this->breakpoints($variables);
        $variables = $this->fonts($variables);
		
        $this->variables = $variables;

        //error_log(print_r($variables, true));

        return $variables;

    }

    protected function colors ($variables) {

        if (!is_callable('oxy_get_global_colors')) return $variables;

        $colors = oxy_get_global_colors()['colors'];
        
        foreach ($colors as $color) {

            $name = $this->slug($color['name']);

            $variables["c-" . $color['id']] = $color['value'];
            $variables["c-" . $name] = $color['value'];

        }

        return $variables;

    }

    protected function breakpoints ($variables) {

        if (
            (!is_callable('ct_get_global_settings')) ||
            (!is_callable('oxygen_vsb_get_breakpoint_width')) ||
            (!is_callable('oxygen_vsb_get_page_width'))
        ) return $variables;

        //error_log(print_r(ct_get_global_settings(), true))
        
        $default_breakpoints = ct_get_global_settings(true)['breakpoints'];
        asort($default_breakpoints);

        $breakpoints = [];

        $i = 1;
        foreach ($default_breakpoints as $name => $default_width) {

            $name = $this->slug($name);

            $width = oxygen_vsb_get_breakpoint_width($name);

            $variables['b-' . $name]    = $width;
            $variables['b-' . $i]       = $width;
            $breakpoints[$name]         = $width . "px";
            $breakpoints['b-' . $i]     = $width . "px";

            $i++;

        }

        $page_width = oxygen_vsb_get_page_width();

        $variables['b-page']        = $page_width;
        $variables['b-' . $i]       = $page_width;
        $breakpoints['page']        = $page_width . "px";
        $breakpoints['b-' . $i]     = $page_width . "px";       

        $variables['breakpoints'] = $this->array_to_sass_map($breakpoints);

        return $variables;

    }

    protected function fonts ($variables) {

        if (!is_callable('ct_get_global_settings')) return $variables;

        $fonts = ct_get_global_settings()['fonts'];

        $i = 1;
        foreach ($fonts as $name => $font) {

            $name = $this->slug($name);
            
            $variables['f-' . $i]       = $font;
            $variables['f-' . $name]    = $font;

            $i++;

        }

        return $variables;

    }

    protected function slug ($string) {

        return trim(strtolower(str_replace(" ", "_", $string)));

    }

    protected function array_to_sass_map ($a) {

        $map = "(";
        $i = 0;

        foreach ($a as $k => $v) {

            $map .= "'" . $k . "': " . $v;
            if ($i < count($a) - 1) $map .= ", ";

            $i++;

        }

        $map .= ")";
        return $map;

    }

}

new OXY_SCSS();