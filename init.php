<?php

/**
 * TT Arkam Lite
 *
 * Arkam Lite is a WordPress plugin that displays stylish social media buttons with counters.
 * This plugin comes with a shortcode and a widget. This particular
 * file is responsible for including the necessary dependencies 
 * and starting the plugin.
 *
 * @package   tt-arkam-lite
 * @copyright 2018 Themient.com, Asmi Khalil
 *
 * Plugin Name: Arkam Lite
 * Plugin URI:  http://themient.com/plugins/arkam-lite
 * Description: Responsive social media counters plugin for WordPress.
 * Version:     1.0.1
 * Author:      Themient
 * Author URI:  http://themient.com
 * License: GPLv3 or later
 * Text Domain: arkam-lite
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once( plugin_dir_path( __FILE__ ) . 'class-arkam.php' );
require_once( plugin_dir_path( __FILE__ ) . 'settings.php' );

/**
 * Loads a single instance of TT Arkam Lite
 *
 * This follows the PHP singleton design pattern.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @example <?php $tt_arkam_lite = tt_arkam_lite(); ?>
 *
 * @since 1.0.0
 *
 * @see TT_Arkam_Lite::get_instance()
 *
 * @return object Returns an instance of the TT_Arkam_Lite class
 */
function tt_arkam_lite() {
    return TT_Arkam_Lite::get_instance();
}

/**
 * Loads plugin after all the others have loaded and have registered their
 * hooks and filters
 */
add_action( 'plugins_loaded', 'tt_arkam_lite', apply_filters( 'tt_arkam_lite_action_priority', 10 ) );

/**
 * Register plugin activation Hook
 */
register_activation_hook( __FILE__, 'tt_arkam_lite_activation_hook' );
function tt_arkam_lite_activation_hook() {
	set_transient( 'tt_arkam_lite_activated', true, 5 );
}