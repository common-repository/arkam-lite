<?php

/**
 *
 * Ajax class
 *
 * This class is wrapper for the plugin's Ajax action handlers.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Ajax' ) ) {

    /**
     * TT Arkam Lite Ajax
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Ajax {

        protected $helper;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct() {

            $this->helper = new TT_Arkam_Lite_Helper();
        }

        /**
         * Get twitter access token
         *
         * @since 1.0.0
         */
        public function get_twitter_token() {

            // Check nonce
            check_ajax_referer( 'tt-arkam-lite-ajax' );

            // Build $args
            $cons_key = $_POST['consumerKey'];
            $cons_secret = $_POST['consumerSecret'];

            $toSend = base64_encode( $cons_key . ':' . $cons_secret );
 
            // HTTP post arguments
            $args = array(
                'method' => 'POST',
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => 'Basic ' . $toSend,
                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                ),
                'body' => array( 'grant_type' => 'client_credentials' )
            );

            $api_url = 'https://api.twitter.com/oauth2/token';

            $token = $this->helper->remote_post( $api_url, true, $args );

            if ( isset( $token['access_token'] ) ) {
                echo $token['access_token'];
            }

            wp_die();
        }
	}
}