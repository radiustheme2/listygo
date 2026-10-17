<?php
/**
 * @author  RadiusTheme
 * @since   1.0.1
 * @version 1.0.1
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

add_action( 'after_setup_theme', 'rdtheme_edd_theme_updater', 20 );

function rdtheme_edd_theme_updater(){
	$theme_data = wp_get_theme( get_template() );

	// Config settings
	$config = array(
		'remote_api_url' => 'https://www.radiustheme.com', // Site where EDD is hosted
		'item_id'        => 196785, // ID of item in site where EDD is hosted
		'theme_slug'     => '_rt_listygo', // Theme slug
		'version'        => $theme_data->get( 'Version' ), // The current version of this theme
		'author'         => $theme_data->get( 'Author' ), // The author of this theme
		'download_id'    => '', // Optional, used for generating a license renewal link
		'renew_url'      => '' // Optional, allows for a custom license renewal link
	);

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'listygo' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'listygo' ),
		'license-key'               => __( 'License Key', 'listygo' ),
		'license-action'            => __( 'License Action', 'listygo' ),
		'deactivate-license'        => __( 'Deactivate License', 'listygo' ),
		'activate-license'          => __( 'Activate License', 'listygo' ),
		'status-unknown'            => __( 'License status is unknown.', 'listygo' ),
		'renew'                     => __( 'Renew?', 'listygo' ),
		'unlimited'                 => __( 'unlimited', 'listygo' ),
		'license-key-is-active'     => __( 'License key is active.', 'listygo' ),
		'expires%s'                 => __( 'Expires %s.', 'listygo' ),
		'lifetime-license'          => __( 'License type: lifetime.', 'listygo' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'listygo' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'listygo' ),
		'license-key-expired'       => __( 'License key has expired.', 'listygo' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'listygo' ),
		'license-is-inactive'       => __( 'License is inactive.', 'listygo' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'listygo' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'listygo' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'listygo' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'listygo' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'listygo' )
	);

	// Loads the updater classes
	global $listygo_edd_updater;
	$listygo_edd_updater = new EDD_Theme_Updater_Admin( $config, $strings );
}