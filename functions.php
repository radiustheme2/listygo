<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;
add_editor_style( 'style-editor.css' );

if ( !isset( $content_width ) ) {
	$content_width = 1200;
}

class Listygo_Main {
	public $theme   = 'listygo';
	public $action  = 'listygo_theme_init';

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action( 'admin_notices',     array( $this, 'plugin_update_notices' ) );
		$this->includes();
	}
	public function load_textdomain(){
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}
	
	public function includes(){
		do_action( $this->action ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/includes.php';
		require_once get_template_directory() . '/inc/customizer/typography/fonts-list.php';
	}

	public function plugin_update_notices() {
		$plugins = array();

		if ( defined( 'LISTYGO_CORE' ) ) {
			if ( version_compare( LISTYGO_CORE, '3.2.2', '<' ) ) {
				$plugins[] = 'Listygo Core';
			}
		}

		foreach ( $plugins as $plugin ) {

			$notice = '<div class="error"><p>' . sprintf(
				/* translators: 1: Plugin name, 2: Plugin installer URL */
					__(
						'Please update plugin %1$s to the latest version otherwise some functionalities will not work properly. You can update it from %2$s',
						'listygo'
					),
					'<b><i>' . esc_html( $plugin ) . '</i></b>',
					'<a href="' . esc_url( menu_page_url( 'listygo-install-plugins', false ) ) . '">' . esc_html__( 'here', 'listygo' ) . '</a>'
				) . '</p></div>';

			echo wp_kses( $notice, 'alltext_allow' );
		}
	}
}
new Listygo_Main;