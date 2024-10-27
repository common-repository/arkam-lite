<?php

/**
 *
 * Plugin Main class
 *
 * This is the core plugin class responsible for including and
 * instantiating all of the code that composes the plugin.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite' ) ) {

    /**
     * TT Arkam Lite
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite {

        public $version;
        protected $settings;

        protected $loader;
        protected $renderer;
        protected $ajax;
        protected $counter;

        /**
         * Holds the instance
         *
         * Ensures that only one instance of TT Arkam Lite exists in memory at any one
         * time and it also prevents needing to define globals all over the place.
         *
         * TL;DR This is a static property property that holds the singleton instance.
         *
         * @since 1.0.0
         */
        private static $instance;

        /**
         * Get the instance and store the class inside it. This plugin utilises
         * the PHP singleton design pattern.
         *
         * @since 1.0.0
         *
         * @return object self::$instance Instance
         */
        public static function get_instance() {

            if ( !isset( self::$instance ) && !( self::$instance instanceof TT_Arkam_Lite ) ) {

                self::$instance = new TT_Arkam_Lite;
                self::$instance->version = '1.0.0';
                self::$instance->settings = tt_arkam_lite_settings_array();

                self::$instance->setup_globals();
                self::$instance->load_classes();

                self::$instance->load_dependencies();

                if ( is_admin() ) {
                    self::$instance->define_admin_hooks();

                } else {
                    self::$instance->define_frontend_hooks();
                }

                // Run
                self::$instance->run();
            }

            return self::$instance;
        }

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct() {

            self::$instance = $this;

            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        }

        /**
         * Sets up the constants/globals used
         *
         * @since 1.0.0
         */
        private function setup_globals() {

            // File Path and URL Information
            $this->file          = __FILE__;
            $this->basename      = apply_filters( 'tt_arkam_lite_plugin_basenname', plugin_basename( $this->file ) );
            $this->plugin_url    = plugin_dir_url( __FILE__ );
            $this->plugin_path   = plugin_dir_path( __FILE__ );
            $this->lang_dir      = apply_filters( 'tt_arkam_lite_lang_dir', trailingslashit( $this->plugin_path . 'languages' ) );

            // Assets
            $this->assets_dir    = apply_filters( 'tt_arkam_lite_assets_dir', trailingslashit( $this->plugin_path . 'assets' ) );
            $this->assets_url    = apply_filters( 'tt_arkam_lite_assets_url', trailingslashit( $this->plugin_url  . 'assets' ) );

            // Classes
            $this->classes_dir   = apply_filters( 'tt_arkam_lite_classes_dir', trailingslashit( $this->plugin_path . 'includes' ) );
            $this->classes_url   = apply_filters( 'tt_arkam_lite_classes_url', trailingslashit( $this->plugin_url  . 'includes' ) );
        }

        /**
         * Loads Classes
         *
         * @since 1.0.0
         */
        private function load_classes() {

            require_once( $this->classes_dir . 'class-loader.php' );
            require_once( $this->classes_dir . 'class-helper.php' );
            require_once( $this->classes_dir . 'class-counter.php' );
            require_once( $this->classes_dir . 'class-renderer.php' );

            if ( is_admin() ) {

                require_once( $this->classes_dir . 'admin/class-ajax.php' );
                require_once( $this->classes_dir . 'admin/class-admin.php' );

            } else {
                require_once( $this->classes_dir . 'frontend/class-frontend.php' );
            }

            require_once( $this->classes_dir . 'class-shortcode.php' );
            require_once( $this->classes_dir . 'class-widget.php' );
        }

        /**
         * Run the loader
         *
         * @since 1.0.0
         */
        public function run() {
            $this->loader->run();
        }

        /**
         * Reset the instance of the class
         *
         * @since 1.0.0
         * @access public
         * @static
         */
        public static function reset() {
            self::$instance = null;
        }

        /**
         * Load plugin dependencies
         *
         * @since 1.0.0
         */
        private function load_dependencies() {

            // Loader
            $this->loader = new TT_Arkam_Lite_Loader();

            // Load plugin textdomain
            $this->loader->add_action( 'init', $this, 'load_plugin_textdomain' );

            // Renderer & Counter
            $this->renderer = new TT_Arkam_Lite_Renderer( $this->get_settings() );
            $this->counter = new TT_Arkam_Lite_Counter( $this->get_settings(), $this->renderer->get_options() );

            // Register shortcode
            $shortcode = new TT_Arkam_Lite_Shortcode( $this->get_settings(), $this->assets_url );
            $this->loader->add_action( 'init', $shortcode, 'register_shortcode' );
            $this->loader->add_action( 'vc_before_init', $shortcode, 'vc_map_shortcode' );

            // Add shortcode button to TinyMCE editor
            $this->loader->add_action( 'init', $shortcode, 'tinymce_button' );

            // Register widget
            $this->loader->add_action( 'widgets_init', $this, 'register_widget' );
        }

        /**
         * Admin hooks
         *
         * @since 1.0.0
         */
        private function define_admin_hooks() {

            // Ajax
            $ajax = new TT_Arkam_Lite_Ajax();
            $this->loader->add_action( 'wp_ajax_arkam_get_twitter_token', $ajax, 'get_twitter_token' );

            // Register admin hooks
            $admin = new TT_Arkam_Lite_Admin( $this->get_renderer(), $this->get_version(), $this->get_settings(), $this->assets_url );
            $this->loader->add_action( 'admin_menu', $admin, 'add_admin_menu' );
            $this->loader->add_action( 'admin_footer', $admin, 'token_panel' );
            $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
        }

        /**
         * Frontend hooks
         *
         * @since 1.0.0
         */
        private function define_frontend_hooks() {

            // Register frontend hooks
            $frontend = new TT_Arkam_Lite_Frontend( $this->get_version(), $this->assets_url );
            $this->loader->add_action( 'wp_enqueue_scripts', $frontend, 'enqueue_scripts' );
        }

        /**
         * Load Plugin Text Domain
         *
         * @since 1.0.0
         */
        public function load_plugin_textdomain() {

            // Load the default language files
            load_plugin_textdomain( 'arkam-lite', false, $this->lang_dir );
        }

        /**
         * Register the plugin widget
         *
         * @since 1.0.0
         */
        public function register_widget() {
            register_widget( 'TT_Arkam_Lite_Widget' );
        }

        /**
         * Get plugin version
         *
         * @since 1.0.0
         */
        public function get_version() {
            return $this->version;
        }

        /**
         * Get plugin settings array
         * @since 1.0.0
         */
        public function get_settings() {
            return $this->settings;
        }

        /**
         * Get the renderer instance
         *
         * @since 1.0.0
         */
        public function get_renderer() {
            return $this->renderer;
        }

        /**
         * Handles the displaying of any notices in the admin area
         *
         * @since 1.0.0
         */
        public function admin_notices() {

            // Check transient, if available display notice
            if ( get_transient( 'tt_arkam_lite_activated' ) ) {
                echo '<div id="arkam-lite-msg" class="notice notice-info is-dismissible"><p>' . sprintf( esc_html__( 'Thank you for installing Arkam Lite! Please visit the plugin %s page to configure it.', 'arkam-lite' ), '<a href="'. admin_url( 'admin.php?page=arkam-lite' ) .'">'. esc_html__( 'settings', 'arkam-lite' ) .'</a>' ) . '</p></div>';

                // Delete transient, only display this notice once.
                delete_transient( 'tt_arkam_lite_activated' );
            }
        }
	}
}