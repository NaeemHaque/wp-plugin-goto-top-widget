<?php

defined('ABSPATH') or die;

/**
 * Plugin Name: WPSniper Goto Top
 * Plugin URI: https://wordpress.org/plugins/wpsniper-goto-top
 * Description: Add a customizable 'goto top' button to your WordPress website.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: naeemhaque
 * Author URI: https://profiles.wordpress.org/naeemhaque/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpsniper-gttop
 * Domain Path: /languages
 *
 */
/*
  * The main plugin class
  */

final class WPSniper_GoToTop
{

    /*
     * Plugin version
     */
    const version = '1.0.0';

    /*
     * Class constructor
     */
    private function __construct()
    {
        $this->define_constant();

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('plugins_loaded', [$this, 'init_plugin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_footer', [$this, 'wpSniperGoToTopButton']);
    }

    /*
     * Initialize a singleton instance
     * @return \WPSniper_GoToTop
     */
    public static function init()
    {
        static $instance = false;

        if ( ! $instance) {
            $instance = new self();
        }

        return $instance;
    }

    /*
     * Define all constant
     * @return void
     */
    public function define_constant()
    {
        define('WPSNIPER_GTTOP_VERSION', self::version);
        define('WPSNIPER_GTTOP_FILE', __FILE__);
        define('WPSNIPER_GTTOP_PATH', __DIR__);
        define('WPSNIPER_GTTOP_URL', plugins_url('', WPSNIPER_GTTOP_FILE));
        define('WPSNIPER_GTTOP_ASSETS', WPSNIPER_GTTOP_URL . '/assets');
    }

    /*
     * Do stuff upon plugin activation
     * @return void
     */
    public function activate()
    {
        update_option('wpsniper_gttop_version', WPSNIPER_GTTOP_VERSION);
    }

    /*
     * Initialize the plugin classes
     * @return void
     */
    public function init_plugin()
    {
        add_action('customize_register', [$this, 'wpSniperGoToTopCustomizerSettings']);
        add_action('wp_head', [$this, 'wpSniperGoToTopCustomizerStyles']);
    }

    /*
     * Enqueue scripts
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('wpsniper-gttop', WPSNIPER_GTTOP_ASSETS . '/js/wpsniper-gttop-script.js', ['jquery'],
            WPSNIPER_GTTOP_VERSION, true);
    }

    /*
     * Enqueue styles
     * @return void
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('wpsniper-gttop', WPSNIPER_GTTOP_ASSETS . '/css/wpsniper-gttop-style.css', [],
            WPSNIPER_GTTOP_VERSION);
    }

    /*
     * Add the "go to top" button
     * @return void
     */
    function wpSniperGoToTopButton()
    {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $.scrollUp();
            });
        </script>
        <?php
    }

    /*
     * Add customizer settings
     * @return void
     */
    function wpSniperGoToTopCustomizerSettings($wp_customize)
    {
        // Adding Section for customization
        $wp_customize->add_section('wpsniper_gttop_section', array(
            'title'       => __('WPSniper Goto Top', 'wpsniper-gttop'),
            'priority'    => 20,
            'description' => __('Customize the "Goto Top" button.', 'wpsniper-gttop'),
        ));

        // Adding Background Color
        $wp_customize->add_setting('wpsniper_gttop_setting', array(
            'default'   => '#000000',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'wpsniper_gttop_control', array(
            'label'    => __('Background Color', 'wpsniper-gttop'),
            'section'  => 'wpsniper_gttop_section',
            'settings' => 'wpsniper_gttop_setting',
            'type'     => 'color',
        )));

        // Adding Rounded Corner
        $wp_customize->add_setting('wpsniper_gttop_rounded_corner', array(
            'default'     => '5px',
            'description' => 'If you need fully rounded or circular then use 25px here.',
        ));
        $wp_customize->add_control('wpsniper_gttop_rounded_corner', array(
            'label'   => 'Rounded Corner',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'text',
        ));

        // Adding position of the button
        $wp_customize->add_setting('wpsniper_gttop_bottom', array(
            'default'     => '5px',
            'description' => 'Spacing from bottom of the page.',
        ));
        $wp_customize->add_control('wpsniper_gttop_bottom', array(
            'label'   => 'Bottom',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'text',
        ));
        $wp_customize->add_setting('wpsniper_gttop_right', array(
            'default'     => '5px',
            'description' => 'Spacing from right of the page.',
        ));
        $wp_customize->add_control('wpsniper_gttop_right', array(
            'label'   => 'Right',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'text',
        ));

        // Adding size of the button
        $wp_customize->add_setting('wpsniper_gttop_width', array(
            'default'     => '40px',
            'description' => 'Width of the button.',
        ));
        $wp_customize->add_control('wpsniper_gttop_width', array(
            'label'   => 'Width',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'text',
        ));
        $wp_customize->add_setting('wpsniper_gttop_height', array(
            'default'     => '40px',
            'description' => 'Height of the button.',
        ));
        $wp_customize->add_control('wpsniper_gttop_height', array(
            'label'   => 'Height',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'text',
        ));

        // Adding border styles of the button

        $wp_customize->add_setting('wpsniper_gttop_border_style', array(
            'default'     => 'none',
            'description' => 'Style of the border.',
        ));
        $wp_customize->add_control('wpsniper_gttop_border_style', array(
            'label'   => 'Border Style',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'select',
            'choices' => array(
                'none'   => 'None',
                'solid'  => 'Solid',
                'dotted' => 'Dotted',
                'dashed' => 'Dashed',
                'double' => 'Double',
                'groove' => 'Groove',
                'ridge'  => 'Ridge',
                'inset'  => 'Inset',
                'outset' => 'Outset',
                'hidden' => 'Hidden',
            ),
        ));

        $wp_customize->add_setting('wpsniper_gttop_border_color', array(
            'default'   => '#000000',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'wpsniper_gttop_border_color', array(
            'label'    => __('Border Color', 'wpsniper-gttop'),
            'section'  => 'wpsniper_gttop_section',
            'settings' => 'wpsniper_gttop_border_color',
            'type'     => 'color',
        )));

        $wp_customize->add_setting('wpsniper_gttop_border_width', array(
            'default'     => '1px',
            'description' => 'Width of the border.',
        ));
        $wp_customize->add_control('wpsniper_gttop_border_width', array(
            'label'   => 'Border Width',
            'section' => 'wpsniper_gttop_section',
            'type'    => 'text',
        ));
    }

    /*
     * Apply customizer styles
     * @return void
     */
    function wpSniperGoToTopCustomizerStyles()
    {
        ?>
        <style type="text/css">
            #scrollUp {
                background-color: <?php echo get_theme_mod('wpsniper_gttop_setting', '#000000'); ?> !important;
                border-radius: <?php echo get_theme_mod('wpsniper_gttop_rounded_corner', '5px'); ?> !important;
                bottom: <?php echo get_theme_mod('wpsniper_gttop_bottom', '5px'); ?> !important;
                right: <?php echo get_theme_mod('wpsniper_gttop_right', '5px'); ?> !important;
                width: <?php echo get_theme_mod('wpsniper_gttop_width', '40px'); ?> !important;
                height: <?php echo get_theme_mod('wpsniper_gttop_height', '40px'); ?> !important;
                border-width: <?php echo get_theme_mod('wpsniper_gttop_border_width', '1px'); ?> !important;
                border-color: <?php echo get_theme_mod('wpsniper_gttop_border_color', '#000000'); ?> !important;
                border-style: <?php echo get_theme_mod('wpsniper_gttop_border_style', 'none'); ?> !important;
            }
        </style>

        <?php
    }
}


/*
 * Initialize the main plugin
 * @return \WPSniper_GoToTop
 */
function wpSniperGoToTop()
{
    return WPSniper_GoToTop::init();
}

wpSniperGoToTop();

?>
