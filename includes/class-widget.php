<?php

/**
 *
 * Widget class
 *
 * This class handle widget registration.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Widget' ) ) {

    /**
     * TT Arkam Lite Widget
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Widget extends WP_Widget {

        protected $defaults;
        protected $settings;

        protected $renderer;
        protected $helper;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct() {

            $this->settings = tt_arkam_lite_settings_array();

            $this->renderer = new TT_Arkam_Lite_Renderer( $this->settings );
            $this->helper = new TT_Arkam_Lite_Helper();

            // Defaults
            $this->defaults = array(
                'title'     => '',
                'layout'    => 'mosaic',
                'spacing'   => '2',
                'size'      => 'small',
                'color'     => 'colored'
            );

            // Loop through all social channels
            foreach ( $this->settings['profiles'] as $key => $data ) {

                // All channels are disabled by default
                $this->defaults[$key] = 0;
            }

            parent::__construct(
                'arkam_lite_widget',
                esc_html__( 'Arkam Lite: Social Counters', 'arkam-lite' ),
                array( 'description' => esc_html__( 'Add social widget with counters.', 'arkam-lite' ) )
            );
        }

        /**
         * WP_Widget form
         *
         * @since 1.0.0
         */
        public function form( $instance ) {

            // Merge with defaults.
            $instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_attr_e( 'Title:', 'arkam-lite' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php esc_attr_e( 'Layout', 'arkam-lite' ); ?>
                    <select class="widefat" id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>">
                        <option value="grid" <?php if ( $instance[ 'layout' ] == 'grid' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Grid', 'arkam-lite' ); ?></option>
                        <option value="mosaic" <?php if ( $instance[ 'layout' ] == 'mosaic' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Mosaic', 'arkam-lite' ); ?></option>
                        <option value="block" <?php if ( $instance[ 'layout' ] == 'block' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Block', 'arkam-lite' ); ?></option>
                    </select>
                </label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'spacing' ); ?>"><?php esc_attr_e( 'spacing', 'arkam-lite' ); ?>
                    <select class="widefat" id="<?php echo $this->get_field_id( 'spacing' ); ?>" name="<?php echo $this->get_field_name( 'spacing' ); ?>">
                        <option value="0" <?php if ( $instance[ 'spacing' ] == '0' ) echo 'selected="selected"'; ?>><?php esc_attr_e( '0 Pixels', 'arkam-lite' ); ?></option>
                        <option value="1" <?php if ( $instance[ 'spacing' ] == '1' ) echo 'selected="selected"'; ?>><?php esc_attr_e( '1 Pixel', 'arkam-lite' ); ?></option>
                        <option value="2" <?php if ( $instance[ 'spacing' ] == '2' ) echo 'selected="selected"'; ?>><?php esc_attr_e( '2 Pixels', 'arkam-lite' ); ?></option>
                        <option value="3" <?php if ( $instance[ 'spacing' ] == '3' ) echo 'selected="selected"'; ?>><?php esc_attr_e( '3 Pixels', 'arkam-lite' ); ?></option>
                        <option value="4" <?php if ( $instance[ 'spacing' ] == '4' ) echo 'selected="selected"'; ?>><?php esc_attr_e( '4 Pixels', 'arkam-lite' ); ?></option>
                        <option value="5" <?php if ( $instance[ 'spacing' ] == '5' ) echo 'selected="selected"'; ?>><?php esc_attr_e( '5 Pixels', 'arkam-lite' ); ?></option>
                    </select>
                </label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php esc_attr_e( 'Size', 'arkam-lite' ); ?>
                    <select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
                        <option value="small" <?php if ( $instance[ 'size' ] == 'small' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Small', 'arkam-lite' ); ?></option>
                        <option value="medium" <?php if ( $instance[ 'size' ] == 'medium' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Medium', 'arkam-lite' ); ?></option>
                        <option value="large" <?php if ( $instance[ 'size' ] == 'large' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Large', 'arkam-lite' ); ?></option>
                    </select>
                </label>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php esc_attr_e( 'Color Scheme', 'arkam-lite' ); ?>
                    <select class="widefat" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>">
                        <option value="light" <?php if ( $instance[ 'color' ] == 'light' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Light', 'arkam-lite' ); ?></option>
                        <option value="dark" <?php if ( $instance[ 'color' ] == 'dark' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Dark', 'arkam-lite' ); ?></option>
                        <option value="colored" <?php if ( $instance[ 'color' ] == 'colored' ) echo 'selected="selected"'; ?>><?php esc_attr_e( 'Colored', 'arkam-lite' ); ?></option>
                    </select>
                </label>
            </p>

            <hr style="background: #ccc; border: 0; height: 1px; margin: 20px 0;" />

            <div class="arkam-lite-boxes widget-arkam-lite-boxes">
                <?php foreach ( $this->settings['profiles'] as $key => $data ) { ?>

                    <div class="arkam-lite-box postbox">
                        <label>
                        <h2 class="header arkam-handle">    
                            <input type="checkbox" name="<?php echo $this->get_field_name( $key ); ?>" value="1" <?php checked( 1, $instance[$key] ); ?>>
                            <span class="boxlabel">
                                <?php echo esc_attr( $data['label'] ); ?>
                            </span>
                        </h2>
                        </label>
                    </div>

                <?php } ?>
            </div>

        <?php }

        /**
         * WP_Widget update
         *
         * @since 1.0.0
         */
        public function update( $new_instance, $old_instance ) {

            $instance = array();
            $instance['title'] = esc_attr( $new_instance['title'] );
            $instance['layout'] = esc_attr( $new_instance['layout'] );
            $instance['spacing'] = esc_attr( $new_instance['spacing'] );
            $instance['size'] = esc_attr( $new_instance['size'] );
            $instance['color'] = esc_attr( $new_instance['color'] );

            foreach ( $this->settings['profiles'] as $key => $data ) {

                // Sanitize channels.
                $instance[$key] = $this->helper->sanitize_checkbox( $new_instance[$key] );
            }

            return $instance;
        }

        /**
         * WP_Widget widget
         *
         * @since 1.0.0
         */
        public function widget( $args, $instance ) {

            extract( $args );

            // Merge with defaults.
            $instance = wp_parse_args( (array) $instance, $this->defaults );

            $enabled_channels = array();

            foreach ( $this->settings['profiles'] as $key => $data ) {
                if ( $instance[$key] == true ) {
                    $enabled_channels[] = $key;
                }
            }

            $opts = array(
                'layout' => $instance['layout'],
                'spacing' => $instance['spacing'],
                'size' => $instance['size'],
                'color' => $instance['color']
            );

            echo $before_widget;

            if ( !empty( $instance['title'] ) ) {
                echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
            }

            echo $this->renderer->render_counts( $enabled_channels, $opts );

            echo $after_widget;
        }
	}
}