<?php
/**
 * @author  RadiusTheme
 * @since   1.0.1
 * @version 1.0.1
 */

class EDD_Theme_Updater_Admin {

	/**
	 * Variables required for the theme updater
	 *
	 * @since 1.0.0
	 * @type string
	 */
	protected $remote_api_url = null;
	protected $theme_slug = null;
	protected $version = null;
	protected $author = null;
	protected $download_id = null;
    protected $item_id = null;
	protected $renew_url = null;
	protected $strings = null;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	function __construct( $config = [], $strings = [] ) {

		$config = wp_parse_args( $config, [
			'remote_api_url' => 'http://easydigitaldownloads.com',
			'theme_slug'     => get_template(),
			'item_id'        => '',
			'license'        => '',
			'version'        => '',
			'author'         => '',
			'download_id'    => '',
			'renew_url'      => ''
		] );

		// Set config arguments
		$this->remote_api_url = $config['remote_api_url'];
		$this->item_id        = $config['item_id'];
		$this->theme_slug     = sanitize_key( $config['theme_slug'] );
		$this->version        = $config['version'];
		$this->author         = $config['author'];
		$this->download_id    = $config['download_id'];
		$this->renew_url      = $config['renew_url'];

		// Populate version fallback
		if ( '' == $config['version'] ) {
			$theme         = wp_get_theme( $this->theme_slug );
			$this->version = $theme->get( 'Version' );
		}

		// Strings passed in from the updater config
		$this->strings = $strings;

		add_action( 'admin_init', [ $this, 'updater' ] );
		add_action( 'admin_init', [ $this, 'register_option' ] );
		add_action( 'admin_init', [ $this, 'license_action' ] );
		add_action( 'update_option_' . $this->theme_slug . '_license_key', [ $this, 'activate_license' ], 10, 2 );
		add_action( 'add_option_' . $this->theme_slug . '_license_key', [ $this, 'activate_license' ], 10, 2 );
		add_filter( 'http_request_args', [ $this, 'disable_wporg_request' ], 5, 2 );
		add_action( 'admin_notices', [ $this, 'show_license_notice'] );
	}

	/**
	 * Creates the updater class.
	 *
	 * since 1.0.0
	 */
	function updater() {

		/* If there is no valid license key status, don't allow updates. */
		if ( get_option( $this->theme_slug . '_license_key_status', false ) != 'valid' ) {
			return;
		}

		if ( ! class_exists( 'EDD_Theme_Updater' ) ) {
			// Load our custom theme updater
			include( dirname( __FILE__ ) . '/theme-updater-class.php' );
		}

		new EDD_Theme_Updater(
			[
				'remote_api_url' => $this->remote_api_url,
				'version'        => $this->version,
				'license'        => trim( get_option( $this->theme_slug . '_license_key' ) ),
				'item_id'        => $this->item_id,
				'author'         => $this->author
			],
			$this->strings
		);
	}

	/**
	 * Adds a menu item for the theme license under the appearance menu.
	 *
	 * since 1.0.0
	 */
	function license_menu() {

		$strings = $this->strings;

		add_theme_page(
			$strings['theme-license'],
			$strings['theme-license'],
			'manage_options',
			$this->theme_slug . '-license',
			[ $this, 'license_page' ]
		);
	}

	/**
	 * Outputs the markup used on the theme license page.
	 *
	 * since 1.0.0
	 */
	function license_page() {

		$strings = $this->strings;

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$status  = get_option( $this->theme_slug . '_license_key_status', false );

		// Checks license status to display under license key
		if ( ! $license ) {
			$message = $strings['enter-key'];
		} else {
			// delete_transient( $this->theme_slug . '_license_message' );
			if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
				set_transient( $this->theme_slug . '_license_message', $this->check_license(), ( 60 * 60 * 24 ) );
			}
			$message = get_transient( $this->theme_slug . '_license_message' );
		}
		?>
        <div class="wrap">
        <h2><?php echo esc_html( $strings['theme-license'] ); ?></h2>
        <form method="post" action="options.php">

			<?php settings_fields( $this->theme_slug . '-license' ); ?>

            <table class="form-table">
                <tbody>

                <tr valign="top">
                    <th scope="row" valign="top">
						<?php echo esc_html( $strings['license-key'] ); ?>
                    </th>
                    <td>
                        <input id="<?php echo esc_attr( $this->theme_slug ); ?>_license_key"
                               name="<?php echo esc_attr( $this->theme_slug ); ?>_license_key" type="text"
                               class="regular-text" value="<?php echo esc_attr( $license ); ?>"/>
                        <p class="description">
							<?php echo esc_html( $message ); ?>
                        </p>
                    </td>
                </tr>

				<?php if ( $license ) { ?>
                    <tr valign="top">
                        <th scope="row" valign="top">
							<?php echo esc_html( $strings['license-action'] ); ?>
                        </th>
                        <td>
							<?php
							wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );
							if ( 'valid' == $status ) { ?>
                                <input type="submit" class="button-secondary"
                                       name="<?php echo esc_attr( $this->theme_slug ); ?>_license_deactivate"
                                       value="<?php echo esc_attr( $strings['deactivate-license'] ); ?>"/>
							<?php } else { ?>
                                <input type="submit" class="button-secondary"
                                       name="<?php echo esc_attr( $this->theme_slug ); ?>_license_activate"
                                       value="<?php echo esc_attr( $strings['activate-license'] ); ?>"/>
							<?php }
							?>
                        </td>
                    </tr>
				<?php } ?>

                </tbody>
            </table>
			<?php submit_button(); ?>
        </form>
		<?php
	}

	/**
	 * Outputs only the form markup for embedding in a tabbed interface.
	 *
	 * @since 1.0.0
	 */
	function license_page_content() {

		$strings = $this->strings;

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$status  = get_option( $this->theme_slug . '_license_key_status', false );

		// Checks license status to display under license key
		if ( ! $license ) {
			$message = $strings['enter-key'];
		} elseif ( 'valid' !== $status ) {
			delete_transient( $this->theme_slug . '_license_message' );
			$message = $strings['license-is-inactive'];
		} else {
			// Status is locally valid — get detailed info (expiry, site count) from API.
			if ( ! get_transient( $this->theme_slug . '_license_message' ) ) {
				$api_message = $this->check_license();
				// Only cache when the API confirms active; if it returns inactive/unknown
				// (e.g. a brief sync delay right after activation), trust the local status
				// and fall back to the generic active string without caching.
				$inactive_strings = [
					$strings['license-is-inactive'],
					$strings['site-is-inactive'],
					$strings['license-keys-do-not-match'],
					$strings['license-key-is-disabled'],
					$strings['license-status-unknown'],
					$strings['status-unknown'],
				];
				if ( ! in_array( $api_message, $inactive_strings, true ) ) {
					set_transient( $this->theme_slug . '_license_message', $api_message, ( 60 * 60 * 24 ) );
					$message = $api_message;
				} else {
					$message = $strings['license-key-is-active'];
				}
			} else {
				$message = get_transient( $this->theme_slug . '_license_message' );
			}
		}
		?>
		<form method="post" action="options.php">
			<?php settings_fields( $this->theme_slug . '-license' ); ?>

			<div class="rtlc-field-group">
				<label for="<?php echo esc_attr( $this->theme_slug ); ?>_license_key">
					<?php echo esc_html( $strings['license-key'] ); ?> <span class="rtlc-required">*</span>
				</label>
				<input id="<?php echo esc_attr( $this->theme_slug ); ?>_license_key"
					   name="<?php echo esc_attr( $this->theme_slug ); ?>_license_key" type="text"
					   class="rtlc-input" value="<?php echo esc_attr( $license ); ?>"
					   placeholder="<?php esc_attr_e( 'Enter your theme license key', 'listygo' ); ?>" />
				<p class="rtlc-field-desc"><?php echo esc_html( $message ); ?></p>
			</div>

			<div class="rtlc-field-group">
				<label><?php esc_html_e( 'License Status', 'listygo' ); ?></label>
				<?php if ( 'valid' == $status ) { ?>
					<span class="rtlc-status-btn rtlc-verified"><?php esc_html_e( 'Activated', 'listygo' ); ?></span>
				<?php } else { ?>
					<span class="rtlc-status-btn rtlc-unverified"><?php esc_html_e( 'Not Activated', 'listygo' ); ?></span>
				<?php } ?>
			</div>

			<?php if ( $license ) {
				wp_nonce_field( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' );
				if ( 'valid' == $status ) { ?>
					<button type="submit" name="<?php echo esc_attr( $this->theme_slug ); ?>_license_deactivate" class="rtlc-activate-btn rtlc-deactivate-btn">
						<span class="dashicons dashicons-unlock"></span>
						<?php echo esc_html( $strings['deactivate-license'] ); ?>
					</button>
				<?php } else { ?>
					<button type="submit" name="<?php echo esc_attr( $this->theme_slug ); ?>_license_activate" class="rtlc-activate-btn">
						<span class="dashicons dashicons-lock"></span>
						<?php echo esc_html( $strings['activate-license'] ); ?>
					</button>
				<?php }
			} else { ?>
				<button type="submit" class="rtlc-activate-btn">
					<span class="dashicons dashicons-lock"></span>
					<?php esc_html_e( 'Save Changes', 'listygo' ); ?>
				</button>
			<?php } ?>
		</form>
		<?php
	}

	/**
	 * Registers the option used to store the license key in the options table.
	 *
	 * since 1.0.0
	 */
	function register_option() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			[ $this, 'sanitize_license' ]
		);
	}

	/**
	 * Sanitizes the license key.
	 *
	 * since 1.0.0
	 *
	 * @param string $new License key that was submitted.
	 *
	 * @return string $new Sanitized license key.
	 */
	function sanitize_license( $new ) {

		$old = get_option( $this->theme_slug . '_license_key' );

		if ( $old && $old != $new ) {
			// New license has been entered, so must reactivate
			delete_option( $this->theme_slug . '_license_key_status' );
			delete_transient( $this->theme_slug . '_license_message' );
		}

		return $new;
	}

	/**
	 * Makes a call to the API.
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 *
	 * @return array $response decoded JSON response.
	 * @since 1.0.0
	 *
	 */
	function get_api_response( $api_params ) {

		// Call the custom API.
		$response = wp_remote_get(
			add_query_arg( $api_params, $this->remote_api_url ),
			[ 'timeout' => 15, 'sslverify' => false ]
		);

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );

		return $response;
	}

	/**
	 * Activates the license key.
	 *
	 * @since 1.0.0
	 */
	function activate_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$strings = $this->strings;

		// Data to send in our API request.
		$api_params = [
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => $this->item_id
		];

		$license_data = $this->get_api_response( $api_params );

		// $response->license will be either "valid" or "inactive"
		if ( $license_data && isset( $license_data->license ) ) {
			update_option( $this->theme_slug . '_license_key_status', $license_data->license );
			delete_transient( $this->theme_slug . '_license_message' );

			// Build and cache the message immediately from the activation response so the
			// expiry date is shown straight away without a second API call.
			if ( 'valid' === $license_data->license ) {
				$expires = false;
				if ( isset( $license_data->expires ) ) {
					if ( 'lifetime' === $license_data->expires ) {
						$expires = 'lifetime';
					} else {
						$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
					}
				}

				$site_count    = ! empty( $license_data->site_count ) ? $license_data->site_count : '';
				$license_limit = ! empty( $license_data->license_limit ) ? $license_data->license_limit : '';
				if ( 0 == $license_limit ) {
					$license_limit = $strings['unlimited'];
				}

				$message = $strings['license-key-is-active'] . ' ';
				if ( $expires ) {
					$message .= 'lifetime' === $expires
						? $strings['lifetime-license'] . ' '
						: sprintf( $strings['expires%s'], $expires ) . ' ';
				}
				if ( $site_count && $license_limit ) {
					$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
				}

				set_transient( $this->theme_slug . '_license_message', trim( $message ), ( 60 * 60 * 24 ) );
			}
		}

	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 1.0.0
	 */
	function deactivate_license() {

		// Retrieve the license from the database.
		$license = trim( get_option( $this->theme_slug . '_license_key' ) );

		// Always clear local state first so the UI reflects deactivation
		// immediately, even if the API call fails (network issues, timeout, etc.).
		delete_option( $this->theme_slug . '_license_key_status' );
		delete_transient( $this->theme_slug . '_license_message' );

		// Notify the API server to free up the license slot (best-effort).
		if ( $license ) {
			$api_params = [
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'    => $this->item_id
			];
			$this->get_api_response( $api_params );
		}
	}

	/**
	 * Constructs a renewal link
	 *
	 * @since 1.0.0
	 */
	function get_renewal_link() {

		// If a renewal link was passed in the config, use that
		if ( '' != $this->renew_url ) {
			return $this->renew_url;
		}

		// If download_id was passed in the config, a renewal link can be constructed
		$license_key = trim( get_option( $this->theme_slug . '_license_key', false ) );
		if ( '' != $this->download_id && $license_key ) {
			$url = esc_url( $this->remote_api_url );
			$url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;

			return $url;
		}

		// Otherwise return the remote_api_url
		return $this->remote_api_url;

	}


	/**
	 * Checks if a license action was submitted.
	 *
	 * @since 1.0.0
	 */
	function license_action() {

		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[ $this->theme_slug . '_license_deactivate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '_nonce', $this->theme_slug . '_nonce' ) ) {
				$this->deactivate_license();
			}
		}

	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @return string $message License status message.
	 * @since 1.0.0
	 *
	 */
	function check_license() {

		$license = trim( get_option( $this->theme_slug . '_license_key' ) );
		$strings = $this->strings;

		$api_params = [
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_id'    => $this->item_id
		];

		$license_data = $this->get_api_response( $api_params );

		// If response doesn't include license data, return
		if ( ! isset( $license_data->license ) ) {
			$message = $strings['license-unknown'];

			return $message;
		}

		// Get expire date
		$expires = false;
		if ( isset( $license_data->expires ) ) {
			if('lifetime' === $license_data->expires) {
				$expires = 'lifetime';
			} else {
				$expires    = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires ) );
			}
			$renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
		}

		// Get site counts
		$site_count    = ! empty( $license_data->site_count ) ? $license_data->site_count : ''; //@kowsar
		$license_limit = ! empty( $license_data->license_limit ) ? $license_data->license_limit : ''; //@kowsar

		// If unlimited
		if ( 0 == $license_limit ) {
			$license_limit = $strings['unlimited'];
		}

		if ( $license_data->license == 'valid' ) {
			$message = $strings['license-key-is-active'] . ' ';
			if ( $expires ) {
				$message .= 'lifetime' === $expires ? $strings['lifetime-license'] : sprintf( $strings['expires%s'], $expires ) . ' ';
			}
			if ( $site_count && $license_limit ) {
				$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
			}
		} else if ( $license_data->license == 'expired' ) {
			if ( $expires ) {
				$message = sprintf( $strings['license-key-expired-%s'], $expires );
			} else {
				$message = $strings['license-key-expired'];
			}
			if ( $renew_link ) {
				$message .= ' ' . $renew_link;
			}
		} else if ( $license_data->license == 'invalid' ) {
			$message = $strings['license-keys-do-not-match'];
		} else if ( $license_data->license == 'inactive' ) {
			$message = $strings['license-is-inactive'];
		} else if ( $license_data->license == 'disabled' ) {
			$message = $strings['license-key-is-disabled'];
		} else if ( $license_data->license == 'site_inactive' ) {
			// Site is inactive
			$message = $strings['site-is-inactive'];
		} else {
			$message = $strings['license-status-unknown'];
		}

		return $message;
	}

	/**
	 * Disable requests to wp.org repository for this theme.
	 *
	 * @since 1.0.0
	 */
	function disable_wporg_request( $r, $url ) {

		// If it's not a theme update request, bail.
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
			return $r;
		}

		// Decode the JSON response
		$themes = json_decode( $r['body']['themes'] );

		// Remove the active parent and child themes from the check
		$parent = get_option( 'template' );
		$child  = get_option( 'stylesheet' );
		unset( $themes->themes->$parent );
		unset( $themes->themes->$child );

		// Encode the updated JSON response
		$r['body']['themes'] = json_encode( $themes );

		return $r;
	}

	function get_license_status() {
	    $license = trim( get_option( $this->theme_slug . '_license_key' ) );

	    $api_params = [
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_id'    => $this->item_id
		];

	    $license_data = $this->get_api_response( $api_params );

	    return $license_data->license;
	}

	function show_license_notice() {
		// Suppress on the license page itself.
		if ( isset( $_GET['page'] ) && 'rtlc' === $_GET['page'] ) {
			return;
		}

		// Check RadiusTheme Site (EDD) license directly — always available inside this class.
		if ( 'valid' === get_option( $this->theme_slug . '_license_key_status', '' ) ) {
			return;
		}

		// Check ThemeForest license if the helper function is loaded.
		if ( function_exists( '\\RTLC\\rtlc_is_valid' ) && \RTLC\rtlc_is_valid()['success'] ) {
			return;
		}

		$link = '<a href="' . esc_url( admin_url( 'themes.php?page=rtlc' ) ) . '">' . esc_html__( 'activate your theme license', 'listygo' ) . '</a>';
		?>
		<div class="notice notice-error">
			<p><strong><?php printf( esc_html__( 'Please %s to get update, full functionalities and customer support.', 'listygo' ), $link ); ?></strong></p>
		</div>
		<?php
	}

}