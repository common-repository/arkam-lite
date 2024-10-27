<?php

/**
 *
 * Frontend class
 *
 * This class handles plugin's frontend dependencies.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Frontend' ) ) {

    /**
     * TT Arkam Lite Frontend
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Frontend {

		protected $version;
		protected $assets_url;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
		function __construct( $version, $assets_url ) {

			$this->version = $version;
			$this->assets_url = $assets_url;
		}

		/**
		 * Frontend styles/scripts
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {

			// Stylesheet
			wp_enqueue_style(
				'arkam-lite',
				$this->assets_url . 'css/style.min.css',
				array(),
				$this->version
			);
		}
	}
}