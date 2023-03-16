<?php

defined('ABSPATH') or die;

/**
 * WPSniper Goto Top
 *
 * @package WPSniper_Goto_Top
 * @author WPSniper
 * @copyright Copyright (c) WPSniper
 * @license GPL-2.0-or-later
 *
 * Plugin Name: WPSniper Goto Top
 * Plugin URI: https://wordpress.org/plugins/wpsniper-goto-top
 * Description: Adds a "go to top" button to your WordPress site.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: WPSniper
 * Author URI: https://example.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:  https://github.com/NaeemHaque/wpsniper-goto-top
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

//        add_action('customize_register', [$this, 'wpSniperGoToTopCustomizerSettings']);
//        add_action('wp_head', [$this, 'wpSniperGoToTopCustomizerStyles']);
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
        $wp_customize->add_section('wpsniper_gttop_section', array(
            'title'       => __('WPSniper Goto Top', 'wpsniper-gttop'),
            'priority'    => 200,
            'description' => __('Customize the "go to top" button.', 'wpsniper-gttop'),
        ));

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
