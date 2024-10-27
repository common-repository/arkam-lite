<?php

/**
 *
 * Helper class
 *
 * This class defines few useful validation and misc methods to be used all over the plugin's code.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Helper' ) ) {

    /**
     * TT Arkam Lite Helper
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Helper {

        /**
         * Sanitize url parameter
         *
         * @since 1.0.0
         */
        public function sanitize_url_var( $var ) {

            return esc_attr( preg_replace('/\s+/', '', $var ) );
        }

        /**
         * Sanitize checkbox
         *
         * @since 1.0.0
         */
        public function sanitize_checkbox( $input ) {
            if ( $input == '1' ) {
                return '1';
            } else {
                return '0';
            }
        }

        /**
         * Make WP get request
         *
         * @since 1.0.0
         */
        public function remote_get( $url, $json = true, $args = array() ) {

            //add_filter( 'https_ssl_verify', '__return_false' );

            $res = wp_remote_get( $url , $args );

            if ( is_wp_error( $res ) ) {
                return 0;
            }

            $res = wp_remote_retrieve_body( $res );

            if ( $json ) {
                $res = json_decode( $res , true );
            }

            return $res;
        }

        /**
         * Make WP post request
         *
         * @since 1.0.0
         */
        public function remote_post( $url, $json = true, $args = array() ) {

            add_filter( 'https_ssl_verify', '__return_false' );

            $res = wp_remote_post( $url , $args );

            if ( is_wp_error( $res ) ) {
                return 0;
            }

            $res = wp_remote_retrieve_body( $res );

            if ( $json ) {
                $res = json_decode( $res , true );
            }

            return $res;
        }

        /**
         * Get channel url if exists
         *
         * @since 1.0.0
         */
        public function get_channel_url( $channel, $options ) {

            if ( !empty( $options[$channel]['url'] ) ) {
                return esc_url( $options[$channel]['url'] );

            } else {
                return '';
            }
        }
	}
}