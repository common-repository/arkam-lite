<?php

/**
 *
 * Counter class
 *
 * This is the class where all the magic happens! It handles making API requests, fetching results and managing transients.
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */

if ( ! defined( 'WPINC' ) ) { exit; } // Exit if accessed directly

if( ! class_exists( 'TT_Arkam_Lite_Counter' ) ) {

    /**
     * TT Arkam Lite Counter
     *
     * @since 1.0.0
     */
	class TT_Arkam_Lite_Counter {

        protected $settings;
        protected $options;
        protected $helper;

        /**
         * Constructor
         *
         * @since 1.0.0
         */
        public function __construct( $settings, $options ) {

            $this->settings = $settings;
            $this->options = $options;

            $this->helper = new TT_Arkam_Lite_Helper();
        }

        /**
         * Get formatted count (up to Billions!)
         *
         * @since 1.0.0
         */
        public function get_short_count( $n, $precision = 1 ) {

            if ( $n < 900 ) {
                // 0 - 900
                $n_format = number_format($n, $precision);
                $suffix = '';

            } else if ( $n < 900000 ) {
                // 0.9k-850k
                $n_format = number_format($n / 1000, $precision);
                $suffix = esc_html__( 'K', 'arkam-lite' );

            } else if ( $n < 900000000 ) {
                // 0.9m-850m
                $n_format = number_format( $n / 1000000, $precision );
                $suffix = esc_html__( 'M', 'arkam-lite' );

            } else if ( $n < 900000000000 ) {
                // 0.9b-850b
                $n_format = number_format( $n / 1000000000, $precision );
                $suffix = esc_html__( 'B', 'arkam-lite' );

            } else {
                // 0.9t+
                $n_format = number_format( $n / 1000000000000, $precision );
                $suffix = esc_html__( 'T', 'arkam-lite' );
            }

            // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
            // Intentionally does not affect partials, eg "1.50" -> "1.50"
            if ( $precision > 0 ) {
                $dotzero = esc_html__( '.', 'arkam-lite' ) . str_repeat( '0', $precision );
                $n_format = str_replace( $dotzero, '', $n_format );
            }

            return $n_format . $suffix;
        }

        /**
         * Get cached count
         *
         * @since 1.0.0
         */
        public function get_cached_count( $channel ) {

            // Get transient
            $count = absint( get_transient( 'tt_arkam_lite_'. $channel ) );

            // Transient not found
            if ( !$count ) {
                
                // Get new count
                $count = $this->get_count( $channel );

                // Set transient
                $exp = !empty( $this->options['cache'] )? absint( $this->options['cache'] ): 5;
                set_transient( 'tt_arkam_lite_'. $channel, $count, $exp * MINUTE_IN_SECONDS );
            }

            // Return count
            if ( $count == 0 && !empty( $this->options[$channel]['fallback'] ) ) {
                $count = absint( $this->options[$channel]['fallback'] );
            }

            return  $this->get_short_count( $count );
        }

        /**
         * Get live count
         *
         * @since 1.0.0
         */
        public function get_count( $channel ) {

            $count = 0;

            switch ( $channel ) {
                case 'facebook':

                    $count = $this->get_facebook_count();
                    break;

                case 'twitter':

                    $count = $this->get_twitter_count();
                    break;

                case 'pinterest':

                    $count = $this->get_pinterest_count();
                    break;

                case 'reddit':

                    $count = $this->get_reddit_count();
                    break;

                case 'vimeo':

                    $count = $this->get_vimeo_count();
                    break;

                case 'dribbble':

                    $count = $this->get_dribbble_count();
                    break;

                case 'mixcloud':

                    $count = $this->get_mixcloud_count();
                    break;

                case 'steam':

                    $count = $this->get_steam_count();
                    break;

                case 'wp_comments':

                    $count = $this->get_wp_count( 'comments' );
                    break;

                case 'buddypress_groups':

                    $count = $this->get_buddypress_count( 'groups' );
                    break;
            }

            return absint( $count );
        }

        /**
         * Get facebook count
         *
         * @since 1.0.0
         */
        public function get_facebook_count() {

            if ( !empty( $this->options['facebook']['id'] ) && !empty( $this->options['facebook']['access'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['facebook']['id'] );
                $access = $this->helper->sanitize_url_var( $this->options['facebook']['access'] );
                $api_url = sprintf( 'https://graph.facebook.com/%s?access_token=%s&fields=name,fan_count', $id, $access );

                $count = $this->helper->remote_get( $api_url );

                if ( isset( $count['fan_count'] ) ) {
                    return $count['fan_count'];
                }
            }

            return 0;
        }

        /**
         * Get twitter count
         *
         * @since 1.0.0
         */
        public function get_twitter_count() {

            if ( !empty( $this->options['twitter']['id'] ) && !empty( $this->options['twitter']['access'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['twitter']['id'] );
                $access = $this->helper->sanitize_url_var( $this->options['twitter']['access'] );
                $api_url = sprintf( 'https://api.twitter.com/1.1/users/show.json?screen_name=%s', $id );

                $args = array(
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => sprintf( 'Bearer %s', $access ) 
                    )
                );

                $count = $this->helper->remote_get( $api_url, true, $args );

                if ( isset( $count['followers_count'] ) ) {
                    return $count['followers_count'];
                }
            }

            return 0;
        }

        /**
         * Get pinterest count
         *
         * @since 1.0.0
         */
        public function get_pinterest_count() {

            if ( !empty( $this->options['pinterest']['id'] ) && !empty( $this->options['pinterest']['access'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['pinterest']['id'] );
                $access = $this->helper->sanitize_url_var( $this->options['pinterest']['access'] );

                $api_url = sprintf( 'https://api.pinterest.com/v1/users/%s/?access_token=%s&fields=counts', $id, $access ); 

                $count = $this->helper->remote_get( $api_url );

                if ( isset( $count['data']['counts']['followers'] ) ) {
                    return $count['data']['counts']['followers'];
                }
            }

            return 0;
        }

        /**
         * Get reddit count
         *
         * @since 1.0.0
         */
        public function get_reddit_count() {

            if ( !empty( $this->options['reddit']['id'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['reddit']['id'] );

                $url = sprintf( 'https://www.reddit.com/r/%s/about.json', $id ); 

                $count = $this->helper->remote_get( $url );

                if ( isset( $count['data']['subscribers'] ) ) {
                    return $count['data']['subscribers'];
                }
            }

            return 0;
        }

        /**
         * Get vimeo count
         *
         * @since 1.0.0
         */
        public function get_vimeo_count() {

            if ( !empty( $this->options['vimeo']['id'] ) && !empty( $this->options['vimeo']['access'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['vimeo']['id'] );
                $access = $this->helper->sanitize_url_var( $this->options['vimeo']['access'] );

                $api_url = sprintf( 'https://api.vimeo.com/channels/%s/?access_token=%s', $id, $access ); 

                $count = $this->helper->remote_get( $api_url );

                if ( isset( $count['metadata']['connections']['users']['total'] ) ) {
                    return $count['metadata']['connections']['users']['total'];
                }
            }

            return 0;
        }

        /**
         * Get dribbble count
         *
         * @since 1.0.0
         */
        public function get_dribbble_count() {

            if ( !empty( $this->options['dribbble']['id'] ) /*&& !empty( $this->options['dribbble']['access'] )*/ ) {

                $id = $this->helper->sanitize_url_var( $this->options['dribbble']['id'] );

                /* 
                 * Dribbble API v1 is deprecated and V2 require authentication before requesting the user.
                 * For now, we are forced to use a hacky method to fetch followers count until Dribbble make
                 * it possible to fetch it using their new API.
                 */

                /*
                $access = $this->helper->sanitize_url_var( $this->options['dribbble']['access'] );

                $api_url = sprintf( 'https://api.dribbble.com/v2/users/%s?access_token=%s', $id, $access ); 

                $count = $this->helper->remote_get( $api_url );

                if ( isset( $count['followers_count'] ) ) {
                    return $count['followers_count'];
                }*/

                // Alternative method
                $url = sprintf( 'https://dribbble.com/%s', $id );

                try {
                    $html = $this->helper->remote_get( $url , false );
                    $doc = new DOMDocument();

                    @$doc->loadHTML( $html );

                    $xpath = new DOMXPath( $doc );
                    $data = $xpath->evaluate('string(//li[@class="followers"]//*[contains(@class, "count")])');
                    $count = (int) preg_replace( '/[^0-9.]+/', '', $data );
                
                } catch (Exception $e) {
                    $count = 0;
                }

                return $count;
            }

            return 0;
        }

        /**
         * Get mixcloud count
         *
         * @since 1.0.0
         */
        public function get_mixcloud_count() {

            if ( !empty( $this->options['mixcloud']['id'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['mixcloud']['id'] );
                $api_url = sprintf( 'http://api.mixcloud.com/%s/', $id ); 

                $count = $this->helper->remote_get( $api_url );

                if ( isset( $count['follower_count'] ) ) {
                    return $count['follower_count'];
                }
            }

            return 0;
        }

        /**
         * Get steam count
         *
         * @since 1.0.0
         */
        public function get_steam_count() {

            if ( !empty( $this->options['steam']['id'] ) ) {

                $id = $this->helper->sanitize_url_var( $this->options['steam']['id'] );

                $url = sprintf( 'https://steamcommunity.com/groups/%s/memberslistxml?xml=1', $id );

                $count = $this->helper->remote_get( $url, false );

                try {

                    $count   = @new SimpleXmlElement( $count );           
                    $count = $count->groupDetails->memberCount;   

                } catch ( Exception $e ) {
                    $count = 0;
                }

                return $count;
            }

            return 0;
        }

        /**
         * Get WP count
         *
         * @since 1.0.0
         */
        public function get_wp_count( $query = 'comments' ) {

            if ( $query == 'comments' ) {
                $count = wp_count_comments();
                return $count->approved;

            }
        }

        /**
         * Get BuddyPress count
         *
         * @since 1.0.0
         */
        public function get_buddypress_count( $query = 'groups' ) {

            if ( 'groups' == $query && function_exists( 'groups_get_total_group_count' ) ) {
                return groups_get_total_group_count();

            } else {
                return 0;
            }
        }
	}
}