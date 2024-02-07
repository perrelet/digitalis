<?php

namespace Digitalis\Module\Page_Loader;

use Digitalis\Module\Module;
use Digitalis\Struct\JS_Loader;
use Digitalis\Admin\Option\Field;
use Digitalis\Util\Utility;

// Based On: https://www.primative.net/blog/how-to-get-rid-of-the-flash-of-unstyled-content/


class Page_Loader extends Module {
    
    public function __construct () {
        
        $this->fields('add_options');
        
        $this->no_js();
        
        add_action('wp_enqueue_scripts', [$this, 'scripts']);
        
        add_action('wp_head',     [$this, 'no_js_bypass']);
        add_action('init',         [$this, 'init']);
        
        add_action('update_option_' . $this->get_option_key("loader_spinner"), [$this, 'save'], 10, 3);
        
    }
    
    public function init () {
        
        if ($this->get_option('loader_do_screen'))  add_action('ct_before_builder', [$this, 'inject_screen'], 100);
        if ($this->get_option('loader_do_spinner')) add_action('Digitalis/Module/Page_Loader/Content', [$this, 'spinner'], 100);
        
        
    }
    
    public function add_options ($manager) {
        
        $spinners = [
            "bars"       => "Bars",
            "default"    => "Default",
            "dots"       => "Dots",
            "dotty"      => "Dotty",
            "faded"      => "Faded",
            "simple"     => "Simple",
            "sweep-dots" => "Sweep Dots",
            "sweeper"    => "Sweeper",
            "triple"     => "Triple"
        ];
        
        $manager->add_field(new Field("Inject Loading Screen?", "loader_do_screen",  "checkbox", DIGITALIS_VALUE_TRUE));
        $manager->add_field(new Field("Render a Spinner?",      "loader_do_spinner", "checkbox", DIGITALIS_VALUE_TRUE));
        $manager->add_field(new Field(
            "Spinner",
            "loader_spinner",
            "select",
            "default"
        ))->get_current_field()->add_options($spinners);
        
        $manager->add_field(new Field("Spinner Speed (s)",          "loader_spinner_speed", "text", "1"));
        $manager->add_field(new Field("Entry Transition Speed (s)", "loader_trans_speed", "text", "1"));
        $manager->add_field(new Field("Exit Transition Speed (s)",  "loader_exit_speed", "text", ""));
        $manager->add_field(new Field("Spinner Color",              "loader_spinner_color", "text", "#555555"));
        $manager->add_field(new Field("Background Style",           "loader_bg", "text", "#ffffff"));

    }
        
    public function inject_screen () {

        $params = $this->get_params();

        $styles = [
            'position'        => 'fixed',
            'left'            => 0,
            'top'             => 0,
            'width'           => '100vw',
            'height'          => '100vh',
            'z-index'         => 999,
            'display'         => 'flex',
            'align-items'     => 'center',
            'justify-content' => 'center',
            'pointer-events'  => 'none',
        ];

        if ($bg = $this->get_option('loader_bg')) $styles['background'] = $bg;

        $styles = apply_filters('Digitalis\Module\Page_Loader\Styles', $styles, $this);

        if (!$params['entry']) $styles['display'] = 'none';

        $style = '';
        if ($styles) foreach ($styles as $prop => $value) $style .= "{$prop}: {$value};";

        $animation = "{$params['entry_speed']}s ease 0s 1 normal forwards running page_loader";

        do_action('Digitalis/Module/Page_Loader/Before');

        echo "<div id='page_loader' style='{$style}'>";
            do_action('Digitalis/Module/Page_Loader/Content');
        echo "</div>";

        echo "<style>";
            echo "@keyframes page_loader { 0% { opacity: 1; } 100% { opacity: 0; } }";
            if ($params['entry']) echo "body.loaded #page_loader { animation: {$animation}; }";
            if ($params['exit'])  echo "body.unloaded #page_loader { animation: {$animation}; animation-duration: {$params['exit_speed']}s; animation-direction: reverse; }";
        echo "</style>";
        
        do_action('Digitalis/Module/Page_Loader/After');
        
    }
    
    public function spinner () {
        
        echo "<div class='spinner'></div>";
        
        $upload_path = wp_upload_dir();
        $dir = 'digitalis';
        $filename = 'spinner.css';
        $path = $upload_path['basedir'] . "/" . $dir . "/";

        $asset_manager = $this->get_asset_manager();
        $asset_manager->load_css($filename, $path, true, false);    

    }
    
    public function save ($old_value, $value, $option) {

        $spinner = "modules/page_loader/spinners/" . $value . ".css";

        $css = Utility::get_file($spinner, "css", true, [
            "%%COLOR%%" => $this->get_option('loader_spinner_color'),
            "%%BG%%"     => $this->get_option('loader_bg'),
            "%%SPEED%%" => $this->get_option('loader_spinner_speed'),
        ]);

        $upload_path = wp_upload_dir();
        $dir = 'digitalis';
        $path = $upload_path['basedir'] . "/" . $dir;
        if (!file_exists($path)) mkdir($path, 0775, true);

        $file = 'spinner.css';

        file_put_contents($path . "/" . $file, $css);

    }
    
    public function no_js () {

        add_filter('body_class',        [$this, 'add_no_js_class'], 10, 2);
        add_action('ct_before_builder', [$this, 'js_remove_no_js_class']);

    }
    
    public function no_js_bypass () {

        echo "<style>.no-js #page_loader { display: none !important; } </style>";

    }
    
    public function add_no_js_class ($classes, $class) {

        $classes[] = "no-js";
        return $classes;

    }
    
    public function js_remove_no_js_class () {

        echo "<script>document.body.classList.remove('no-js');</script>";

    }
    
    public function scripts () {

        $asset_manager = $this->digitalis()->get_asset_manager();

        $transition_speed = (float) $this->get_option('loader_trans_speed');

        $loader = new JS_Loader("modules/page_loader/", "page_loader.js");

        $loader->params_name = "page_loader_params";
        $loader->params      = [$this->get_params()]; // We embed the params in an array to prevent this: https://wordpress.stackexchange.com/questions/237854/wp-localize-script-with-boolean-and-init
        
        $asset_manager->load_js($loader);

    }

    protected function get_params () {

        return apply_filters('Digitalis/Module/Page_Loader/Params', [
            "entry"         => $this->get_option('loader_trans_speed') ? 1 : 0,
            "exit"          => $this->get_option('loader_exit_speed')  ? 1 : 0,
            "entry_speed"   => (float) $this->get_option('loader_trans_speed'),
            "exit_speed"    => (float) $this->get_option('loader_exit_speed'),
        ], $this);

    }

}

new Page_Loader();