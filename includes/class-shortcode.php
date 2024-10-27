<?php

/**
 *
 * Shortcode class
 *
 * This class handle shortcode rendering and registration. Also, here we map the shortcode to
 * Visual Composer's shortcode list and we add the Arkam button to the TinyMCE editor.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Shortcode' ) ) {

    /**
     * TT Arkam Lite Shortcode
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Shortcode {

        protected $settings;
        protected $assets_url;

        protected $renderer;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct( $settings, $assets_url ) {

            $this->settings = $settings;
            $this->assets_url = $assets_url;

            $this->renderer = new TT_Arkam_Lite_Renderer( $settings );
        }

        /**
         * Register the shortcode
         *
         * @since 1.0.0
         */
        public function register_shortcode() {

            add_shortcode( 'arkam_lite', array( $this, 'render_shortcode' ) );
        }

        /**
         * Shortcode callback
         *
         * @since 1.0.0
         */
        public function render_shortcode( $atts ) {

            $output = $class = '';
            $channels = $enabled_channels = array();

            foreach ( $this->settings['profiles'] as $key => $data ) {

                // By default, all channels are disabled
                $channels[$key] = 0;
            }

            $default = array(
                'layout'    => 'mosaic',
                'spacing'   => 2,
                'size'      => 'small',
                'color'     => 'colored',
                'el_class'  => '',
                'css'       => '',

            );

            // Merge defaults
            $default = array_merge( $default, $channels );

            // Get attributes
            $atts = shortcode_atts( $default, $atts, 'arkam_lite' );

            // Create a list of enabled channels
            foreach ( $this->settings['profiles'] as $key => $data ) {
                if ( $atts[$key] == true ) {
                    $enabled_channels[] = $key;
                }
            }

            if ( empty( $enabled_channels ) ) {
                return '';
            }

            // Custom Class
            if ( $atts['el_class'] != '' ) {
                $class .= ' '. esc_attr( $atts['el_class'] );
            }

            // Custom CSS
            if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
                $class .= ' '. vc_shortcode_custom_css_class( esc_attr( $atts['css'] ) );
            }

            ob_start(); ?>

                <div class="arkam arkam-shortcode<?php echo $class; ?>">
                    <?php echo $this->renderer->render_counts( $enabled_channels, $atts ); ?>
                </div>

            <?php $output = ob_get_contents();

            ob_end_clean();

            return $output;
        }

        /**
         * Add Arkam to the TinyMCE editor
         *
         * @since 1.0.0
         */
        public function tinymce_button() {

            // Don't bother doing this if current user lacks permissions
            if ( !current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
                return;
            }

            // Add only in Rich Editor mode
            if ( get_user_option( 'rich_editing' ) == 'true' ) {

                // filter the TinyMCE buttons and add our own   
                add_filter( 'mce_external_plugins', array( $this, 'add_shortcode_button' ) );
                add_filter( 'mce_buttons', array( $this, 'register_shortcode_button' ) );
            }
        }

        /**
         * mce_external_plugins callback
         *
         * @since 1.0.0
         */
        public function add_shortcode_button( $plugin_array ) {
            $plugin_array['arkam_lite_script'] = $this->assets_url . 'js/shortcode.js';
            return $plugin_array;
        }

        /**
         * mce_buttons callback
         *
         * @since 1.0.0
         */
        public function register_shortcode_button( $buttons ) {
            array_push( $buttons, 'tt_arkam_lite' );
            return $buttons;
        }

        /**
         * Map shortcode to VC's shortcode list
         *
         * @since 1.0.0
         */
        public function vc_map_shortcode() {

            $params = array(

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Layout', 'arkam-lite' ),
                    'param_name' => 'layout',
                    'value' => array(
                        esc_html__( 'Grid', 'arkam-lite' )   => 'grid',
                        esc_html__( 'Mosaic', 'arkam-lite' ) => 'mosaic',
                        esc_html__( 'Block', 'arkam-lite' )  => 'block',
                    ),
                    'std' => 'grid',
                ),

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Spacing', 'arkam-lite' ),
                    'param_name' => 'spacing',
                    'value' => array(
                        esc_html__( '0 Pixels', 'arkam-lite' ) => '0',
                        esc_html__( '1 Pixel', 'arkam-lite' )  => '1',
                        esc_html__( '2 Pixels', 'arkam-lite' ) => '2',
                        esc_html__( '3 Pixels', 'arkam-lite' ) => '3',
                        esc_html__( '4 Pixels', 'arkam-lite' ) => '4',
                        esc_html__( '5 Pixels', 'arkam-lite' ) => '5',
                    ),
                    'std' => '2',
                ),

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Size', 'arkam-lite' ),
                    'param_name' => 'size',
                    'value' => array(
                        esc_html__( 'Small', 'arkam-lite' )  => 'small',
                        esc_html__( 'Medium', 'arkam-lite' ) => 'medium',
                        esc_html__( 'Large', 'arkam-lite' )  => 'large',
                    ),
                    'std' => 'medium',
                ),

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Design', 'arkam-lite' ),
                    'param_name' => 'design',
                    'value' => array(
                        esc_html__( 'Flat', 'arkam-lite' )       => 'flat',
                        esc_html__( 'Material', 'arkam-lite' )   => 'material',
                        esc_html__( '3D', 'arkam-lite' )         => '3d',
                    ),
                    'std' => 'flat',
                ),

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Style', 'arkam-lite' ),
                    'param_name' => 'style',
                    'value' => array(
                        esc_html__( 'Simple', 'arkam-lite' )     => 'simple',
                        esc_html__( 'Bordered', 'arkam-lite' )   => 'bordered',
                        esc_html__( 'Filled', 'arkam-lite' )     => 'filled',
                    ),
                    'std' => 'filled',
                ),

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Shape', 'arkam-lite' ),
                    'param_name' => 'shape',
                    'value' => array(
                        esc_html__( 'Square', 'arkam-lite' )     => 'square',
                        esc_html__( 'Rounded', 'arkam-lite' )    => 'rounded',
                    ),
                    'std' => 'square',
                ),

                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Color Scheme', 'arkam-lite' ),
                    'param_name' => 'color',
                    'value' => array(
                        esc_html__( 'Colored', 'arkam-lite' )    => 'colored',
                        esc_html__( 'Light', 'arkam-lite' )      => 'light',
                        esc_html__( 'Dark', 'arkam-lite' )       => 'dark',
                    ),
                    'std' => 'colored',
                ),

                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__( 'Open links in a new tab?', 'arkam-lite' ),
                    'param_name' => 'new_tab',
                    'std' => '',
                ),

                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__( 'Add NoFollow Tag?', 'arkam-lite' ),
                    'param_name' => 'no_follow',
                    'std' => '',
                ),

                array(
                    'type' => 'textfield',
                    'heading' => esc_html__( 'Extra Class Name', 'arkam-lite' ),
                    'param_name' => 'el_class',
                    'description' => esc_html__( 'Use this field to add an extra class name and then refer to it in your css code.', 'arkam-lite' ),
                ),

                array(
                    'type' => 'css_editor',
                    'heading' => esc_html__( 'Custom CSS', 'arkam-lite' ),
                    'param_name' => 'css',
                    'group' => esc_html__( 'Design Options', 'arkam-lite' )
                ),
            );

            // Add channel fields
            foreach ( $this->settings['profiles'] as $key => $data ) {

                $params[] = array(
                    'type' => 'checkbox',
                    'heading' => $data['label'],
                    'param_name' => $key,
                    'std' => '',
                );
            }

            vc_map( array(
                'name' => esc_html__( 'Arkam Lite: Social Counters', 'arkam-lite' ),
                'icon' => $this->assets_url . 'img/icon.png',
                'description' => esc_html__( 'Add social media counters.', 'arkam-lite' ),
                'base' => 'arkam-lite',
                'category' => 'Social',
                'params' => $params,
            ));
        }
	}
}