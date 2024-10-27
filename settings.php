<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Plugin settings/configuration array
 *
 * @since 1.0.0
 *
 * @package tt-arkam-lite
 *
 */
function tt_arkam_lite_settings_array() {

	$settings = array(

        'cache' => 300, // in minutes

        'backlink' => '0',

        'profiles' => array(

            'facebook' => array(
                'label' => esc_html__( 'Facebook', 'arkam-lite' ),
                'color' => '#3b5998',
                'icon' => 'icon-facebook',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'Page ID', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'App Access Token', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'access'
                        )
                    ),

                    array(
                        'label' => '',
                        'option' => array(
                            'type' => 'button',
                            'title' => esc_html__( 'Get Access Token', 'arkam-lite' ),
                            'class' => 'arkam-gen-btn',
                            'atts' => array(
                            	'data-channel' => 'facebook'
                            )
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'twitter' => array(
                'label' => esc_html__( 'Twitter', 'arkam-lite' ),
                'color' => '#1da1f2',
                'icon' => 'icon-twitter',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'Username', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Access Token (Bearer)', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'access'
                        )
                    ),

                    array(
                        'label' => '',
                        'option' => array(
                            'type' => 'button',
                            'title' => esc_html__( 'Get Access Token', 'arkam-lite' ),
                            'class' => 'arkam-gen-btn',
                            'atts' => array(
                            	'data-channel' => 'twitter'
                            )
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'pinterest' => array(
                'label' => esc_html__( 'Pinterest', 'arkam-lite' ),
                'color' => '#bd081c',
                'icon' => 'icon-pinterest',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'User Name/ID', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Access Token', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'access'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'reddit' => array(
                'label' => esc_html__( 'Reddit', 'arkam-lite' ),
                'color' => '#ff4500',
                'icon' => 'icon-reddit-alien',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'Subreddit Slug', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'vimeo' => array(
                'label' => esc_html__( 'Vimeo', 'arkam-lite' ),
                'color' => '#1ab7ea',
                'icon' => 'icon-vimeo',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'Channel ID/Name', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Access Token (Public Only)', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'access'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'dribbble' => array(
                'label' => esc_html__( 'Dribbble', 'arkam-lite' ),
                'color' => '#ea4c89',
                'icon' => 'icon-dribbble',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'User ID/Name', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    // No longer required
                    /*array(
                        'label' => esc_html__( 'Access Token', 'arkam' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'access'
                        )
                    ),*/

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),

                'alerts' => array(
                    array(
                        'type' => 'info',
                        'msg' => esc_html__( 'Dribbble API v1 is deprecated and V2 requires authentication before requesting anything. So we are now forced to use a hacky method (by requesting the full page and then parsing its HTML) to fetch followers count until Dribbble make it possible to fetch it using their new API.', 'arkam-lite' )
                    ),
                ),
            ),

            'mixcloud' => array(
                'label' => esc_html__( 'MixCloud', 'arkam-lite' ),
                'color' => '#52aad8',
                'icon' => 'icon-mixcloud',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'Username', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'steam' => array(
                'label' => esc_html__( 'Steam', 'arkam-lite' ),
                'color' => '#000000',
                'icon' => 'icon-steam',
                'fields' => array(
                    array(
                        'label' => esc_html__( 'Group Name', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'id'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Fallback', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'fallback'
                        )
                    ),
                ),
            ),

            'wp_comments' => array(
                'label' => esc_html__( 'Comments', 'arkam-lite' ),
                'color' => '#3be8b0',
                'icon' => 'icon-chat',
                'fields' => array(

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),
                ),
            ),

            'buddypress_groups' => array(
                'label' => esc_html__( 'BuddyPress (Groups)', 'arkam-lite' ),
                'color' => '#be3631',
                'icon' => 'icon-users',
                'fields' => array(

                    array(
                        'label' => esc_html__( 'URL', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'url'
                        )
                    ),

                    array(
                        'label' => esc_html__( 'Label', 'arkam-lite' ),
                        'option' => array(
                            'type' => 'text',
                            'name' => 'text'
                        )
                    ),
                ),
            ),
        ),
    );

	return apply_filters( 'arkam_lite_settings_array', $settings );
}