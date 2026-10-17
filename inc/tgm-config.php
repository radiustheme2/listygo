<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.6
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

class TGM_Config {
	
	public $listygo = LISTYGO_THEME_PREFIX;
	public $path   = LISTYGO_THEME_PLUGINS_DIR;
	public function __construct() {
		add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
	}
	public function register_required_plugins(){
		$plugins = array(

			// Repository
			array(
                'name'     => 'Classified Listing',
                'slug'     => 'classified-listing',
                'required' => true,
            ),
			array(
                'name'     => 'Classified Listing Toolkits',
                'slug'     => 'classified-listing-toolkits',
                'required' => true,
            ),
			array(
				'name'     => esc_html__('Elementor Page Builder','listygo'),
				'slug'     => 'elementor',
				'required' => true,
			),
			array(
				'name'     => esc_html__('Fluent Forms','listygo'),
				'slug'     => 'fluentform',
				'required' => true,
			),
			array(
				'name'     => esc_html__('Review Schema','listygo'),
				'slug'     => 'review-schema',
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'Breadcrumb NavXT','listygo' ),
				'slug'     => 'breadcrumb-navxt',
				'required' => true,
			),
			array(
				'name'     => 'Radius Booking',
				'slug'     => 'radius-booking',
				'required' => false,
			),
			array(
				'name'      => 'Easy Demo Importer',
				'slug'      => 'easy-demo-importer',
				'required'  => false,
			),

			// Bundled
			array(
				'name'         => esc_html__('Listygo Core', 'listygo'),
				'slug'         => 'listygo-core',
				'source'       => 'listygo-core.3.2.2.zip',
				'required'     =>  true,
				'version'      => '3.2.2',
			),
			array(
				'name'         => esc_html__('RT Framework', 'listygo'),
				'slug'         => 'rt-framework',
				'source'       => 'rt-framework-2.9.1.zip',
				'required'     =>  true,
				'version'      => '2.9.1'
			),
			array(
				'name'         => 'Classified Listing Pro',
				'slug'         => 'classified-listing-pro',
				'source'       => 'classified-listing-pro.4.2.5.zip',
				'required'     =>  true,
				'version'      => '4.2.5'
			),
			array(
				'name'         => 'Classified Listing Store',
				'slug'         => 'classified-listing-store',
				'source'       => 'classified-listing-store.3.2.1.zip',
				'required'     =>  false,
				'version'      => '3.2.1'
			),
			array(
				'name'         => 'Review Schema Pro',
				'slug'         => 'review-schema-pro',
				'source'       => 'review-schema-pro.2.0.1.zip',
				'required'     =>  false,
				'version'      => '2.0.1'
			),
		);
		$config = array(
			'id'           => $this->listygo,            // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => $this->path,              // Default absolute path to bundled plugins.
			'menu'         => $this->listygo . '-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                    // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}
}
new TGM_Config;