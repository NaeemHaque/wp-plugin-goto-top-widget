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

function wpsniper_gttop_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('wpsniper-gttop', plugin_dir_url(__FILE__) . 'assets/js/wpsniper-gttop-script.js',
        array('jquery'), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'wpsniper_gttop_enqueue_scripts');

function wpsniper_gttop_enqueue_styles()
{
    wp_enqueue_style('wpsniper-gttop', plugin_dir_url(__FILE__) . 'assets/css/wpsniper-gttop-style.css', array(),
        '1.0.0', 'all');
}

add_action('wp_enqueue_scripts', 'wpsniper_gttop_enqueue_styles');

function wpsniper_gttop_script()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.scrollUp();
        });
    </script>
    <?php
}

add_action('wp_footer', 'wpsniper_gttop_script');


// register customization menu option in theme customizer
function wpsniper_gttop_customize_register($wp_customize)
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

    // add option for rounded corners
    $wp_customize->add_setting('wpsniper_gttop_rounded_corners', array(
        'default'   => false,
        'transport' => 'refresh',
    ));
    // Adding Rounded Corner
    $wp_customize ->add_setting('wpsniper_gttop_rounded_corner', array(
        'default' => '5px',
        'description' => 'If you need fully rounded or circular then use 25px here.',
    ));
    $wp_customize->add_control('wpsniper_gttop_rounded_corner', array(
        'label'   => 'Rounded Corner',
        'section' => 'wpsniper_gttop_section',
        'type'    => 'text',
    ));


}

add_action('customize_register', 'wpsniper_gttop_customize_register');

// apply customizations
function wpsniper_gttop_customize_css()
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

add_action('wp_head', 'wpsniper_gttop_customize_css');


?>
