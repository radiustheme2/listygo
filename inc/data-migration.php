<?php
/**
 * Old Data Migration (Food Menu & Doctor Chamber data).
 *
 * Handles one-time migration of legacy food menu, doctor chamber,
 * and logo data to the new format. Shows migration UI only for
 * users upgrading from versions below 2.0.0 and hides after
 * migration is complete.
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Conditionally add migration settings field.
 *
 * Shows migration UI only if:
 * 1. Migration has not yet been completed, AND
 * 2. The user upgraded from a version below 2.0.0.
 *
 * @return void
 */
function listygo_maybe_add_migration_settings() {
	// If migration is already done, don't show anything.
	if ( get_option( 'rtcl_listygo_data_migrated' ) ) {
		return;
	}

	// Track the version when migration code first runs.
	// Set once — captures the user's pre-update version.
	$previous_version = get_option( 'listygo_pre_migration_version' );

	if ( false === $previous_version ) {
		$current_version = wp_get_theme( get_template() )->get( 'Version' );
		update_option( 'listygo_pre_migration_version', $current_version );
		$previous_version = $current_version;
	}

	// Only show if user upgraded FROM below 2.0.0.
	if ( version_compare( $previous_version, '2.0.0', '<' ) ) {
		add_filter( 'rtcl_general_settings_options', 'listygo_add_migration_settings_field', 20 );
	}
}
add_action( 'admin_init', 'listygo_maybe_add_migration_settings' );


/**
 * Migrate listing logo to new format.
 *
 * @param int $post_id The post ID.
 *
 * @return void
 */
function listygo_migrate_logo( $post_id = 0 ) {
	global $wpdb;

	$attachment_id = (int) get_post_meta( $post_id, 'listing_logo_img', true );

	if ( ! empty( $attachment_id ) && is_numeric( $attachment_id ) ) {
		// Create new meta value in array format.
		$new_logo = [ $attachment_id ];
		// Save to new key.
		update_post_meta( $post_id, 'listing_logo', $new_logo );
		// Add meta key for attachment post.
		update_post_meta( $attachment_id, '_rtcl_attachment_type', 'file' );

		// Update the attachment.
		$file = get_post_meta( $attachment_id, '_wp_attached_file', true );

		if ( ! empty( $file ) ) {
			$upload_dir = wp_upload_dir();
			$new_guid   = trailingslashit( $upload_dir['baseurl'] ) . $file;

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update(
				$wpdb->posts,
				[
					'guid'        => $new_guid,
					'post_parent' => $post_id,
				],
				[ 'ID' => $attachment_id ],
				[ '%s', '%d' ],
				[ '%d' ]
			);
		}
	}
}

/**
 * Migrate doctor chamber data to new format.
 *
 * @param int $post_id The post ID.
 *
 * @return void
 */
function listygo_migrate_doctor_chamber( $post_id = 0 ) {
	global $wpdb;

	$old = get_post_meta( $post_id, 'listygo_doctor_chamber', true );

	if ( ! empty( $old ) && is_array( $old ) ) {
		$new_rows = [];

		foreach ( $old as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			// Map fields with safe fallbacks.
			$chamber_name     = isset( $row['cname'] ) ? sanitize_text_field( $row['cname'] ) : '';
			$chamber_location = isset( $row['cloaction'] ) ? sanitize_text_field( $row['cloaction'] ) : '';
			$meet_time        = isset( $row['time'] ) ? sanitize_text_field( $row['time'] ) : '';
			$phone_number     = isset( $row['phone'] ) ? sanitize_text_field( $row['phone'] ) : '';

			// Image can be scalar or array; normalize to array of ints.
			$attachment_id = 0;
			if ( isset( $row['chamber_img'] ) && $row['chamber_img'] !== '' ) {
				$attachment_id = absint( $row['chamber_img'] );
			}

			// Skip completely empty rows.
			if ( $chamber_name === '' && $chamber_location === '' && $meet_time === '' && $phone_number === '' && empty( $attachment_id ) ) {
				continue;
			}

			$new_item = [
				'chamber_name'     => $chamber_name,
				'chamber_location' => $chamber_location,
				'meet_time'        => $meet_time,
				'phone_number'     => $phone_number,
			];

			if ( ! empty( $attachment_id ) && is_numeric( $attachment_id ) ) {
				// Update the attachment.
				$file = get_post_meta( $attachment_id, '_wp_attached_file', true );
				if ( ! empty( $file ) ) {
					update_post_meta( $attachment_id, '_rtcl_attachment_type', 'file' );
					$new_item['chamber_image'] = [ $attachment_id ];
					$upload_dir                = wp_upload_dir();
					$new_guid                  = trailingslashit( $upload_dir['baseurl'] ) . $file;

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$wpdb->update(
						$wpdb->posts,
						[
							'guid'        => $new_guid,
							'post_parent' => $post_id,
						],
						[ 'ID' => $attachment_id ],
						[ '%s', '%d' ],
						[ '%d' ]
					);
				}
			}

			$new_rows[] = $new_item;
		}

		// Save if we produced anything.
		if ( ! empty( $new_rows ) ) {
			update_post_meta( $post_id, 'rtcl_listygo_doctor_chamber', $new_rows );
		}
	}
}

/**
 * Migrate food menu data to new format.
 *
 * @param int $post_id The post ID.
 *
 * @return void
 */
function listygo_migrate_food_menu( $post_id = 0 ) {
	global $wpdb;

	$old_data = get_post_meta( $post_id, 'listygo_food_list', true );

	if ( ! empty( $old_data ) && is_array( $old_data ) ) {
		$new_data = [];

		foreach ( $old_data as $group ) {
			// Group Title Row.
			$new_data[] = [
				'enable_group_title' => 'yes',
				'menu_name'          => $group['gtitle'] ?? '',
			];

			// Menu Items.
			if ( ! empty( $group['food_list'] ) && is_array( $group['food_list'] ) ) {
				foreach ( $group['food_list'] as $item ) {
					$menu_item     = [
						'enable_group_title' => 'no',
						'menu_name'          => $item['title'] ?? '',
						'menu_desc'          => $item['description'] ?? '',
						'menu_price'         => $item['foodprice'] ?? '',
					];
					$attachment_id = isset( $item['attachment_id'] ) ? (int) $item['attachment_id'] : 0;

					if ( ! empty( $attachment_id ) && is_numeric( $attachment_id ) ) {
						// Update the attachment.
						$file = get_post_meta( $attachment_id, '_wp_attached_file', true );
						if ( ! empty( $file ) ) {
							update_post_meta( $attachment_id, '_rtcl_attachment_type', 'file' );
							$menu_item['menu_img'] = [ $attachment_id ];
							$upload_dir            = wp_upload_dir();
							$new_guid              = trailingslashit( $upload_dir['baseurl'] ) . $file;

							// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$wpdb->update(
								$wpdb->posts,
								[
									'guid'        => $new_guid,
									'post_parent' => $post_id,
								],
								[ 'ID' => $attachment_id ],
								[ '%s', '%d' ],
								[ '%d' ]
							);
						}
					}

					$new_data[] = $menu_item;
				}
			}
		}

		// Save new meta.
		update_post_meta( $post_id, 'rtcl_listygo_food_list', $new_data );
	}
}

/**
 * Run the main migration process for all listings.
 *
 * @return void
 */
function migrate_listygo_form_data() { // phpcs:ignore
	try {
		$post_ids = get_posts( [
			'post_type'      => 'rtcl_listing',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		] );

		foreach ( $post_ids as $post_id ) {
			listygo_migrate_logo( $post_id );
			listygo_migrate_food_menu( $post_id );
			listygo_migrate_doctor_chamber( $post_id );
		}

		update_option( 'rtcl_listygo_data_migrated', 1 );
	} catch ( Throwable $e ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( 'Listing data migration failed: ' . $e->getMessage() );
	}
}

/**
 * Inject migration Status + Trigger button into RTCL general settings options.
 *
 * @param array $options The existing options array.
 *
 * @return array Modified options array.
 */
function listygo_add_migration_settings_field( $options ) {
	// Add section header.
	$options['old_data_migration_section'] = [
		'title' => esc_html__( 'Old Data Migration (Food Menu & Doctor Chamber data)', 'listygo' ),
		'type'  => 'section',
	];

	// Status HTML.
	$migrated    = get_option( 'rtcl_listygo_data_migrated' );
	$last_errors = get_option( 'rtcl_listygo_data_migration_last_errors', [] );

	$status_html = '<div id="listygo-migration-status">';
	if ( $migrated ) {
		$when         = is_array( $migrated ) && ! empty( $migrated['at'] ) ? esc_html( $migrated['at'] ) : '';
		$status_html .= '<p><strong style="color:green;">' . esc_html__( 'Migration status: DONE', 'listygo' ) . '</strong>';
		if ( $when ) {
			$status_html .= '<br><small>' . $when . '</small>';
		}
		$status_html .= '</p>';
	} else {
		$status_html .= '<p><strong>' . esc_html__( 'Migration status: Not yet run', 'listygo' ) . '</strong></p>';
	}

	if ( ! empty( $last_errors['errors'] ) ) {
		$status_html .= '<p style="color:#d63638;">' . esc_html__( 'Last run had errors. Check logs.', 'listygo' ) . '</p>';
	}
	$status_html .= '</div>';

	$options['listygo_migration_status'] = [
		'title' => esc_html__( 'Status', 'listygo' ),
		'type'  => 'html',
		'html'  => $status_html,
	];

	// Button/form HTML.
	ob_start();
	?>
    <div id="listygo-migration-wrap" style="margin-top:8px;">
        <form method="post" class="listygo-migration-form" style="margin:0;">
			<?php wp_nonce_field( 'listygo_migrate_old_data', 'listygo_migrate_nonce' ); ?>
            <input type="hidden" name="listygo_migrate_action" value="1" />
            <p>
                <button type="submit" class="button button-primary" name="listygo_migrate" value="1"
					<?php disabled( (bool) $migrated ); ?>>
					<?php if ( $migrated ) : ?>
						<?php echo esc_html__( 'Already Migrated', 'listygo' ); ?>
					<?php else : ?>
						<?php echo esc_html__( 'Run Migration', 'listygo' ); ?>
					<?php endif; ?>
                </button>
                <span style="margin-left:10px; color:#666;"><?php esc_html_e( 'This runs the one-time old-data migration.', 'listygo' ); ?></span>
            </p>
        </form>
    </div>
	<?php
	$button_html = ob_get_clean();

	$options['listygo_migration_button'] = [
		'title' => esc_html__( 'Trigger', 'listygo' ),
		'type'  => 'html',
		'html'  => $button_html,
	];

	return $options;
}


/**
 * Handle migration form submission (admin POST).
 *
 * Checks capability and nonce. Prevents re-run if option already set.
 * Calls migrate_listygo_form_data().
 *
 * @return void
 */
function listygo_handle_migration_form_submission() {
	// Only process admin POST requests for our action.
	if ( ! is_admin() ) {
		return;
	}

	$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '';

	if ( $request_method !== 'POST' ) {
		return;
	}

	// Our custom migration action must be set.
	$migrate_action = isset( $_POST['listygo_migrate_action'] ) ? sanitize_text_field( wp_unslash( $_POST['listygo_migrate_action'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( $migrate_action !== '1' ) {
		return;
	}

	// Capability check.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Nonce check.
	$nonce = isset( $_POST['listygo_migrate_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['listygo_migrate_nonce'] ) ) : '';

	if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'listygo_migrate_old_data' ) ) {
		return;
	}

	// Prevent accidental re-run.
	if ( get_option( 'rtcl_listygo_data_migrated' ) ) {
		return;
	}

	// Do migration.
	if ( function_exists( 'migrate_listygo_form_data' ) ) {
		migrate_listygo_form_data();
	}

	update_option( 'rtcl_listygo_data_migrated', 1 );

	// Redirect back.
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
	exit;
}
add_action( 'admin_init', 'listygo_handle_migration_form_submission' );