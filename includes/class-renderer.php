<?php

/**
 *
 * Renderer class
 *
 * This class take care of rendering settings page and social buttons HTML.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Renderer' ) ) {

    /**
     * TT Arkam Lite Renderer
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Renderer {

        protected $settings;
        protected $options;
        protected $counter;

        protected $helper;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct( $settings ) {

            $this->settings = $settings;
            $this->options = get_option( 'tt_arkam_lite' );

            $this->counter = new TT_Arkam_Lite_Counter( $this->settings, $this->options );
            $this->helper = new TT_Arkam_Lite_Helper();
        }

        /**
         * Render a list of channels counts
         *
         * @since 1.0.0
         */
        public function render_counts( $channels, $opts ) {

            $output = $class = '';
            $class .= isset( $opts['layout'] )? ' layout-' . esc_attr( $opts['layout'] ) : '';
            $class .= isset( $opts['spacing'] )? ' spacing-' . esc_attr( $opts['spacing'] ) : '';
            $class .= isset( $opts['size'] )? ' size-' . esc_attr( $opts['size'] ) : '';
            $class .= isset( $opts['color'] )? ' color-'. esc_attr( $opts['color'] ) : '';

            ob_start(); ?>

            <div class="arkam ark-lite-social-icons clearfix<?php echo $class; ?>">
            <ul>
                <?php foreach ( $channels as $channel ) { ?>

                    <li class="ark-lite-item social-<?php echo $channel; ?>">
                        <?php echo $this->render_count( $channel, $opts ); ?>
                    </li>

                <?php } ?>
            </ul>

            <?php if ( isset( $this->options['backlink'] ) && $this->options['backlink'] ) { ?>
                <div class="ark-lite-credit">
                    <?php printf( __( 'Powered by %s', 'arkam-lite'), '<a href="https://themient.com/plugins/arkam" target="_blank">'. esc_html__( 'Arkam', 'arkam-lite' ) .'</a>' ); ?>
                </div>
            <?php } ?>
            </div>

            <?php $output = ob_get_contents();

            ob_end_clean();

            return $output;
        }

        /**
         * Render single channel count
         *
         * @since 1.0.0
         */
        public function render_count( $channel, $opt = array() ) {

            $output = '';
            $link = $this->helper->get_channel_url( $channel, $this->options );
            $style = !empty( $this->options[$channel]['color'] )? ' style="color:'. esc_attr( $this->options[$channel]['color'] ) .';background-color:'. esc_attr( $this->options[$channel]['color'] ) .';border-color:'. esc_attr( $this->options[$channel]['color'] ) .';"': '';

            $attr = '';

            if ( $link != '' ) {
                $el_start = '<a href="'. $link .'"'. $attr .'>';
                $el_end = '</a>';

            } else {
                $el_start = $el_end = '';
            }

            ob_start(); ?>

            <?php echo $el_start; ?>
                <div class="ark-lite-inner"<?php echo $style; ?>>
                    <span class="ark-lite-icon">
                        <i class="icon <?php echo $this->settings['profiles'][$channel]['icon']; ?>" aria-hidden="true"></i>
                    </span>

                    <span class="ark-lite-count">
                        <?php echo $this->counter->get_cached_count( $channel ); ?>

                        <?php if ( !empty( $this->options[$channel]['text'] ) ) { ?>
                            <span class="ark-lite-text">
                                <?php echo esc_attr( $this->options[$channel]['text'] ); ?>
                            </span>
                        <?php } ?>
                    </span>
                </div>
            <?php echo $el_end; ?>

            <?php $output = ob_get_contents();

            ob_end_clean();

            return $output;
        }

        /**
         * Render settings page
         *
         * @since 1.0.0
         */
        public function render_settings( $args ) {

            $sorted = !empty( $this->options )? $this->options: $args;
            unset( $sorted['cache'] );
            unset( $sorted['backlink'] );

            foreach ( $sorted as $key => $value ) {

                $data = $args[$key];

                $style = isset( $data['color'] )? ' style="background-color:'. esc_attr( $data['color'] ) .';"': '';
                $icon = isset( $data['icon'] )? '<span class="boxicon"'. $style .'><i class="icon '. esc_attr( $data['icon'] ) .'" aria-hidden="true"></i></span>': '';

                $scheme = $style != ''? ' dark-lite-color': ''; ?>

                <div id="<?php echo $key; ?>" class="arkam-lite-box postbox">
                    <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text"><?php esc_html_e( 'Toggle panel:', 'arkam-lite' ); ?> <?php echo esc_attr( $data['label'] ); ?></span>
                        <span class="toggle-indicator<?php echo $scheme; ?>" aria-hidden="true"></span>
                    </button>

                    <h2 class="header arkam-handle">
                        <?php echo $icon; ?>
                        <span class="boxlabel">
                            <?php echo esc_attr( $data['label'] ); ?>
                        </span>
                    </h2>

                    <div class="inside">
                        <?php if ( !empty( $data['alerts'] ) ) {
                            foreach ( $data['alerts'] as $alert ) { ?>
                                <div class="tt-arkam-lite-alert tt-arkam-lite-alert-<?php echo $alert['type']; ?>">
                                    <?php echo $alert['msg']; ?>
                                </div>
                            <?php }
                        } ?>
                        <table>
                            <tbody>
                                <?php foreach ( $data['fields'] as $field ) {
                                     $this->render_field( $field, $key );
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php }
        }

        /**
         * Render a single field
         *
         * @since 1.0.0
         */
        public function render_field( $args, $channel = null ) { ?>

            <tr>
                <th><?php $this->render_label( $args['label'] ); ?></th>
                <td><?php $this->render_option( $args['option'], $channel ); ?></td>
            </tr>

        <?php }

        /**
         * Render field's label
         *
         * @since 1.0.0
         */
        public function render_label( $label ) {

            if ( $label != '' ) {  ?>
                <label><?php echo $label; ?></label>
            <?php } ?>

        <?php }

        /**
         * Render field's option
         *
         * @since 1.0.0
         */
        public function render_option( $args, $channel = null ) {

            if ( isset( $args['name'] ) && $args['name'] ) {

                if ( !is_null( $channel ) ) {
                    $name = 'tt_arkam_lite['. $channel .']['. $args['name'] .']';
                    $value = isset( $this->options[$channel][$args['name']] )? $this->options[$channel][$args['name']]: '';

                } else {
                    $name = 'tt_arkam_lite['. $args['name'] .']';
                    $value = isset( $this->options[$args['name']] )? $this->options[$args['name']]: '';
                }
            }

            if ( isset( $value ) && $value == '' && isset( $args['default'] ) ) {
                $value = $args['default'];
            }

            if ( $args['type'] == 'text' ) {
                $this->render_text_option( $args, $name, $value );

            } else if ( $args['type'] == 'color' ) {
                $this->render_color_option( $name, $value );

            } else if ( $args['type'] == 'select' ) {
                $this->render_select_option( $args, $name, $value );

            } else if ( $args['type'] == 'button' ) {
                $this->render_button_option( $args );
            }

        }

        /**
         * Render Text Option
         *
         * @since 1.0.0
         */
        public function render_text_option( $args, $name = null, $value = null ) {

            $type = isset( $args['input_type'] )? $args['input_type']: 'text';
            $placeholder = isset( $args['placeholder'] )? $args['placeholder']: ''; ?>

            <input class="text-field" type="<?php echo $type; ?>" name="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo esc_attr( $value ); ?>">

        <?php }

        /**
         * Render Color Option
         *
         * @since 1.0.0
         */
        public function render_color_option( $name = null, $value = null ) { ?>

            <input class="color-field" type="text" name="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>">

        <?php }

        /**
         * Render Checkbox Option
         *
         * @since 1.0.0
         */
        public function render_checkbox_option( $name = null, $value = null, $setting_name = null, $label = null ) {

            $input_id = 'checkbox-' . rand( 999, 99999 );
            $is_checked = $setting_name && isset( $this->options[$setting_name] ) && $this->options[$setting_name] == '1'? true: $this->settings[$setting_name]; ?>

            <input id="<?php echo $input_id; ?>" class="checkbox" type="checkbox" name="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>"<?php if ( $is_checked ) { echo ' checked'; } ?> >
            <?php if ( $label ) { ?>
                <label for="<?php echo $input_id; ?>"><?php echo esc_html( $label ); ?></label>
            <?php } ?>

        <?php }

        /**
         * Render Select Option
         *
         * @since 1.0.0
         */
        public function render_select_option( $args, $name = null, $value = null ) { ?>

            <select class="select-field" name="<?php echo $name; ?>">

                <?php foreach ( $args['choices'] as $key => $label ) { ?>
                    <option value="<?php echo $key; ?>" <?php selected( $key, $value ); ?>><?php echo $label; ?></option>
                <?php } ?>

            </select>

        <?php }

        /**
         * Render Button Option
         *
         * @since 1.0.0
         */
        public function render_button_option( $args ) { 

            $id = isset( $args['id'] )? 'id="'. $args['id'] .'"': '';
            $class = isset( $args['class'] )? $args['class']: '';

            $atts = '';
            if ( isset( $args['atts'] ) && is_array( $args['atts'] ) ) {
                foreach ( $args['atts'] as $att => $value ) {
                    $atts .= $att .'="'. $value .'" ';
                }
            } ?>

            <button <?php echo $id; ?> class="button button-primary <?php echo $class; ?>" <?php echo $atts; ?>>
                <?php echo $args['title']; ?>
            </button>

        <?php }

        /**
         * Get options array
         *
         * @since 1.0.0
         */
        public function get_options() {
            return $this->options;
        }

        /**
         * Set options array
         *
         * @since 1.0.0
         */
        public function set_options( $options ) {
            $this->options = $options;
        }
	}
}