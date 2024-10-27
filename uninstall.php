<?php

// If uninstall.php is not called by WordPress, die
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

// Option name
$option = 'tt_arkam_lite';

// Delete plugin option
delete_option( $option );

// For site options in Multisite
delete_site_option( $option );