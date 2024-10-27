<?php

/**
 *
 * Admin class
 *
 * This class defines all functionalities for the dashboard
 * of the plugin.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'TT_Arkam_Lite_Admin' ) ) {

    /**
     * TT Arkam Lite Admin
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Admin {

        protected $version;
        protected $settings;
        protected $assets_url;

        protected $renderer;
        protected $helper;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
		public function __construct( $renderer, $version, $settings, $assets_url ) {

            $this->version = $version;
            $this->settings = $settings;
            $this->assets_url = $assets_url;

            $this->renderer = $renderer;
            $this->helper = new TT_Arkam_Lite_Helper();
		}

        /**
         * Add Arkam to admin menu
         *
         * @since 1.0.0
         */
        public function add_admin_menu() {

            add_menu_page(
                esc_html__( 'Arkam Lite', 'arkam-lite' ),   // page title
                esc_html__( 'Arkam Lite', 'arkam-lite' ),   // menu title
                'manage_options',                           // capability
                'arkam-lite',                               // menu slug
                array( $this, 'settings_page_callback' ),   // callback
                $this->assets_url . 'img/icon_32.svg',      // icon url
                110                                         // position
            );
        }

        /**
         * Admin page callback
         *
         * @since 1.0.0
         */
        public function settings_page_callback() { ?>

            <div class="arkam-lite-outer wrap">

            <div class="arkam-lite-wrap">

                <h1><?php esc_html_e( 'Arkam Settings', 'arkam-lite' ); ?></h1>

                <div class="arkam-notice notice notice-info"><p><?php esc_html_e( 'If you change any options here, the plugin cache will be cleared and the counts will be fetched and cached again on the next page load. So this process might take a little while to complete depending on the number of social media channels you have enabled and the number of plugin instances on your page.', 'arkam-lite' ); ?></p></div>

                <?php // Show error/update notices
                settings_errors( 'arkam-lite-notices' );
                
                $this->settings_form_handler(); ?>

                <form method="POST">

                    <div class="cache">
                        <table>
                            <tbody>
                                <?php $cache_field = array(
                                    'label' => esc_html__( 'Cache Results for', 'arkam-lite' ),
                                    'option' => array(
                                        'type' => 'select',
                                        'name' => 'cache',
                                        'choices' => array(
                                            '1' => esc_html__( '1 Minute', 'arkam-lite' ),
                                            '2' => esc_html__( '2 Minutes', 'arkam-lite' ),
                                            '5' => esc_html__( '5 Minutes', 'arkam-lite' ),
                                            '10' => esc_html__( '10 Minutes', 'arkam-lite' ),
                                            '30' => esc_html__( '30 Minutes', 'arkam-lite' ),
                                            '60' => esc_html__( '1 Hour', 'arkam-lite' ),
                                            '300' => esc_html__( '5 Hours', 'arkam-lite' ),
                                            '1440' => esc_html__( '1 Day', 'arkam-lite' ),
                                            '4320' => esc_html__( '3 Days', 'arkam-lite' ),
                                            '10080' => esc_html__( '1 Week', 'arkam-lite' )
                                        ),
                                        'default' => $this->settings['cache']
                                    )
                                );
                                $this->renderer->render_field( $cache_field ); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="arkam-lite-boxes settings-arkam-lite-boxes">
                        <?php $this->renderer->render_settings( $this->settings['profiles'] ); ?>
                    </div>

                    <div class="backlink">
                        <?php $this->renderer->render_checkbox_option( 'tt_arkam_lite[backlink]', '1', 'backlink', esc_html__( 'Support us by displaying "Powered by Arkam" link?', 'arkam-lite' ) ); ?>
                    </div>

                    <?php wp_nonce_field( 'tt_arkam_lite_nonce_action', 'tt_arkam_lite_nonce' ); ?>
                    <?php submit_button(); ?>
                    
                </form>

            </div>

            <div class="arkam-lite-sidebar">

                <div>
                    <a href="https://themient.com/plugins/arkam/?utm_source=Arkam+Lite&utm_medium=Banner&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank">
                        <img src="<?php echo $this->assets_url; ?>img/arkam.jpg">
                    </a>
                </div>

                <div class="arkam-lite-help">

                    <h3 style="margin-top: 0"><?php esc_html_e( 'Documentation', 'arkam-lite' ); ?></h3>
                    <p><?php printf( __( 'Don\'t forget to read the plugin documentation to get the most out of Arkam Lite. An online version can be found %s.', 'arkam-lite' ), '<a href="https://themient.com/support-s/arkam-lite/?utm_source=Arkam+Lite&utm_medium=Link+CPC&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank">'. esc_html__( 'here', 'arkam-lite' ) .'</a>' ); ?></p>
                    <a class="button button-secondary" href="https://themient.com/support-s/arkam-lite/?utm_source=Arkam+Lite&utm_medium=Link+CPC&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank"><?php esc_html_e( 'Documentation', 'arkam-lite' ); ?></a>

                    <h3><?php esc_html_e( 'Need Help?', 'arkam-lite' ); ?></h3>
                    <p><?php printf( __( 'We provide support for this plugin through our %s. Post your question in the right section and we will be happy to assist you.', 'arkam-lite' ), '<a href="https://forum.themient.com/?utm_source=Arkam+Lite&utm_medium=Link+CPC&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank">'. esc_html__( 'Support Forum', 'arkam-lite' ) .'</a>' ); ?></p>
                    <a class="button button-secondary" href="https://forum.themient.com/?utm_source=Arkam+Lite&utm_medium=Link+CPC&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank"><?php esc_html_e( 'Support Forum', 'arkam-lite' ); ?></a>

                    <h4><?php esc_html_e( 'Have a moment? Please share your review', 'arkam-lite' ); ?></h4>
                    <p><?php printf( __( 'We hope you had a great experience, and we are eager to hear your feedback. Please take a minute and leave your valuable review on this %s.', 'arkam-lite' ), '<a href="https://wordpress.org/support/plugin/arkam-lite/reviews/?utm_source=Arkam+Lite&utm_medium=Link+CPC&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank">'. esc_html__( 'page', 'arkam-lite' ) .'</a>' ); ?></p>
                    <a class="button button-primary" href="https://wordpress.org/support/plugin/arkam-lite/reviews/?utm_source=Arkam+Lite&utm_medium=Link+CPC&utm_content=Arkam+Lite+WP+Admin&utm_campaign=WordPressOrg" target="_blank"><?php esc_html_e( 'Leave Your Review', 'arkam-lite' ); ?></a>

                </div>

            </div>

            </div>

        <?php }

        /**
         * Register Settings
         *
         * @since 1.0.0
         */
        public function settings_form_handler() {

            // Form submitted
            if ( isset( $_POST['tt_arkam_lite'] ) ) {

                // Verify/validate nonce
                if ( !isset( $_POST['tt_arkam_lite_nonce'] ) || !wp_verify_nonce( $_POST['tt_arkam_lite_nonce'], 'tt_arkam_lite_nonce_action' ) ) {
                    wp_die( esc_html__( 'Sorry, nonce did not verify.', 'arkam-lite' ) );
                }

                // Check user capability
                if ( !current_user_can( 'manage_options' ) ) {
                    wp_die( esc_html__( 'Unauthorized user', 'arkam-lite' ) );
                }

                // Add/Update settings
                $options = array();
                $submitted = $_POST['tt_arkam_lite'];

                // sanitize cahce option
                $options['cache'] = absint( $submitted['cache'] );

                // sanitize backlink option
                if ( isset( $submitted['backlink'] ) ) {
                    $options['backlink'] = $this->helper->sanitize_checkbox( $submitted['backlink'] );
                } else {
                    unset( $options['backlink'] );
                }

                // Sanitize channel settings
                foreach ( $submitted as $key => $data ) {

                    if ( array_key_exists( $key, $this->settings['profiles'] ) ) {

                        // Sanitize ID field
                        if ( isset( $data['id'] ) ) {
                            $options[$key]['id'] = $this->helper->sanitize_url_var( $data['id'] );
                        }

                        // Sanitize Access Token field
                        if ( isset( $data['access'] ) ) {
                            $options[$key]['access'] = $this->helper->sanitize_url_var( $data['access'] );
                        }

                        // Sanitize Type field
                        if ( isset( $data['type'] ) ) {
                            $options[$key]['type'] = esc_attr( $data['type'] );
                        }

                        // Sanitize URL field
                        if ( isset( $data['url'] ) ) {
                            $options[$key]['url'] = esc_url_raw( $data['url'] );
                        }

                        // Sanitize Label (Text) field
                        if ( isset( $data['text'] ) ) {
                            $options[$key]['text'] = esc_attr( $data['text'] );
                        }

                        // Sanitize Fallback field
                        if ( isset( $data['fallback'] ) ) {
                            $options[$key]['fallback'] = esc_attr( $data['fallback'] );
                        }
                    }
                }

                // All data were sanitized, lets save them
                update_option( 'tt_arkam_lite', $options );

                $class = 'notice notice-success is-dismissible';
                $message = esc_html__( 'Settings Saved', 'arkam-lite' );

                printf( '<div class="%s"><p>%s</p></div>', $class, $message );

                // Set new options
                $this->renderer->set_options( $_POST['tt_arkam_lite'] );

                // Clear transients
                foreach ( $this->settings['profiles'] as $key => $value ) {
                    delete_transient( 'tt_arkam_lite_'. $key );
                }
            }
        }

        /**
         * Token Panel
         *
         * @since 1.0.0
         */
        public function token_panel() { ?>

            <div id="arkam-token-box" class="arkam-lite-panel arkam-token-box panel panel-default" style="display: none;">
                <div class="panel-dialog">
                    <div class="panel-inner">
                        <div class="panel-content">

                            <div class="panel-header">
                                <?php esc_html_e( 'Get Access Token', 'arkam-lite' ); ?>
                                <a href="#" class="close"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </div>

                            <div class="panel-body">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th><?php esc_html_e( 'App ID', 'arkam-lite' ); ?></th>
                                            <td><?php $this->renderer->render_text_option( array( 'name' => '' ) ); ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'App Secret', 'arkam-lite' ); ?></th>
                                            <td><?php $this->renderer->render_text_option( array( 'name' => '') ); ?></td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td><?php $this->renderer->render_button_option(
                                            array(
                                                'title' => esc_html__( 'Generate', 'arkam-lite' ),
                                                'class' => 'arkam-sub-btn'
                                            )
                                        ); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }

        /**
         * Admin scripts/styles
         *
         * @since 1.0.0
         */
        public function enqueue_scripts() {

            $channels = array();

            foreach ( $this->settings['profiles'] as $key => $data ) {
                $channels[$key] = $data['label'];
            }

            $ajaxurl = in_array( 'sitepress-multilingual-cms/sitepress.php', get_option( 'active_plugins' ) )? admin_url( 'admin-ajax.php?lang='. ICL_LANGUAGE_CODE ): admin_url( 'admin-ajax.php' );

            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'ark-lite-icons',
                $this->assets_url . 'css/fontello.css',
                null,
                $this->version
            );

            // admin.css
            wp_enqueue_style(
                'arkam-lite-admin',
                $this->assets_url . 'css/admin.css',
                array(),
                $this->version
            );

            // theia-sticky-sidebar.min.js
            wp_enqueue_script(
                'theia-sticky-sidebar',
                $this->assets_url . 'js/theia-sticky-sidebar.min.js',
                array( 'jquery' ),
                '1.7.0',
                true
            );

            // admin.js
            wp_enqueue_script(
                'arkam-lite-admin',
                $this->assets_url . 'js/admin.js',
                array( 'jquery', 'wp-color-picker' ),
                $this->version,
                true
            );

            wp_localize_script( 'arkam-lite-admin', 'arkamLiteAdmin', array(
                'arkam-lite' => esc_html__( 'Arkam Lite', 'arkam-lite' ),
                'ajaxurl' => $ajaxurl,
                'nonce' => wp_create_nonce( 'tt-arkam-lite-ajax' ),
                'channels' => json_encode( $channels ),
                'mce' => array(
                    'arkam' => esc_html__( 'Arkam Lite', 'arkam-lite' ),
                    'settings' => esc_html__( 'Arkam Lite Settings', 'arkam-lite' ),
                    'error' => esc_html__( 'An error accured.', 'arkam-lite' ),
                    'layout' => esc_html__( 'Layout', 'arkam-lite' ),
                    'grid' => esc_html__( 'Grid', 'arkam-lite' ),
                    'mosaic' => esc_html__( 'Mosaic', 'arkam-lite' ),
                    'block' => esc_html__( 'Block', 'arkam-lite' ),
                    'spacing' => esc_html__( 'Spacing', 'arkam-lite' ),
                    'pixels_0' => esc_html__( '0 Pixels', 'arkam-lite' ),
                    'pixel_1' => esc_html__( '1 Pixel', 'arkam-lite' ),
                    'pixels_2' => esc_html__( '2 Pixels', 'arkam-lite' ),
                    'pixels_3' => esc_html__( '3 Pixels', 'arkam-lite' ),
                    'pixels_4' => esc_html__( '4 Pixels', 'arkam-lite' ),
                    'pixels_5' => esc_html__( '5 Pixels', 'arkam-lite' ),
                    'size' => esc_html__( 'Size', 'arkam-lite' ),
                    'medium' => esc_html__( 'Medium', 'arkam-lite' ),
                    'small' => esc_html__( 'Small', 'arkam-lite' ),
                    'large' => esc_html__( 'Large', 'arkam-lite' ),
                    'color' => esc_html__( 'Color Scheme', 'arkam-lite' ),
                    'colored' => esc_html__( 'Colored', 'arkam-lite' ),
                    'light' => esc_html__( 'Light', 'arkam-lite' ),
                    'dark' => esc_html__( 'Dark', 'arkam-lite' ),
                    'el_class' => esc_html__( 'Extra Class Name', 'arkam-lite' ),
                )
            ) );
        }
	}
}