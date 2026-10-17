<?php
/**
 * Login Form Information
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 * @var Listing $listing
 * @var int     $title_limit
 * @var array   $hidden_fields
 * @var string  $selected_type
 * @var string  $title
 * @var string  $listing_pricing
 * @var string  $price_type
 * @var string  $price
 * @var string  $post_content
 * @var array   $tags
 * @var string  $editor
 * @var int     $category_id
 * @var int     $post_id
 * @var int     $description_limit
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use Rtcl\Resources\Options;
use radiustheme\listygo\Helper;

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="rtcl-post-details rtcl-post-section">
    <div class="rtcl-post-section-title">
        <h3><?php esc_html_e("Listing Title", "listygo"); ?></h3>
    </div>
    <div class="form-group">
        <label for="rtcl-title"><?php esc_html_e('Title', 'listygo'); ?><span class="require-star">*</span></label>
        <input type="text" <?php echo esc_attr( $title_limit ? 'data-max-length="3" maxlength="' . $title_limit . '"' : '' ); ?> class="form-control" value="<?php echo esc_attr($title); ?>" id="rtcl-title" name="title" required/>
		<?php
            if ( $title_limit ) {
                echo sprintf(
                    '<div class="rtcl-hints">%s</div>',
                    apply_filters(
                        'rtcl_listing_title_character_limit_hints',
                        sprintf(
                        /* translators: %s: Character limit number */
                            __( 'Character limit <span class="target-limit">%s</span>', 'listygo' ),
                            $title_limit
                        )
                    )
                );
            }
		?>
    </div>

    <div class="rtcl-post-listing-logo rtcl-post-section">
        <div class="rtcl-post-section-title">
            <h3>
                <i class="rtcl-icon rtcl-icon-picture"></i>
				<?php esc_html_e( 'Listing Logo', 'listygo' ); ?>
            </h3>
        </div>

		<?php
		$listygo_logoID            = get_post_meta( $post_id, 'listing_logo_img', true );
		$listygo_remove_logo_nonce = wp_create_nonce( 'rtcl_remove_listing_logo' );
		?>
		<?php if ( ! empty( $listygo_logoID ) ) : ?>
            <div class="logo-image">
                <input type="hidden" name="logo_attachment_id" value="<?php echo esc_attr( $listygo_logoID ); ?>">

				<?php echo wp_get_attachment_image( $listygo_logoID, 'full' ); ?>

                <div class="remove-logo-image">
                    <a href="#"
                       data-post_id="<?php echo esc_attr( $post_id ); ?>"
                       data-attachment_id="<?php echo esc_attr( $listygo_logoID ); ?>"
                       data-nonce="<?php echo esc_attr( $listygo_remove_logo_nonce ); ?>">
                        <i class="rtcl-icon rtcl-icon-trash"></i>
                    </a>
                </div>
            </div>
		<?php endif; ?>

        <div class="logo-input-wrapper <?php echo esc_attr( $listygo_logoID ? 'd-none' : '' ); ?>">
            <input type="file" name="listing_logo_img" class="listygo-logo-image"/>
        </div>
    </div>

	<?php if (!in_array('price', $hidden_fields)):
		$listygo_listingPricingTypes = Options::get_listing_pricing_types(); ?>
        <div id="rtcl-pricing-wrap">
			<?php if (!Functions::is_pricing_disabled()) { ?>
                <div class="rtcl-post-section-title">
                    <h3><?php esc_html_e("Listing Information:", "listygo"); ?></h3>
                </div>
                <div class="rtcl-from-group rtcl-checkbox-list rtcl-checkbox-inline rtcl-listing-pricing-types">
					<?php
					foreach ($listygo_listingPricingTypes as $type_id => $type) { // phpcs:ignore ?>
                        <div class="rtcl-checkbox rtcl-listing-pricing-type">
                            <input type="radio" name="_rtcl_listing_pricing" id="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ); ?>"
								<?php echo esc_attr( $listing_pricing === $type_id ? 'checked' : '' ); ?> value="<?php echo esc_attr( $type_id ); ?>">
                            <label for="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ); ?>">
								<?php echo esc_html( $type ); ?>
                            </label>
                        </div>
					<?php } ?>
                </div>
			<?php } ?>
			<?php do_action( 'rtcl_listing_form_price_items', $listing ); ?>
            <div id="rtcl-pricing-items" class="<?php echo esc_attr( 'rtcl-pricing-' . $listing_pricing ); ?>">
				<?php if (!Functions::is_price_type_disabled()): ?>
                    <div id="rtcl-form-price-wrap" class="form-group rtcl-pricing-item rtcl-from-group">
                        <label for="rtcl-price-type">
							<?php esc_html_e('Price Type', 'listygo'); ?>
                            <span class="require-star">*</span>
                        </label>
                        <select class="form-control" id="rtcl-price-type" name="price_type">
							<?php
							$listygo_price_types = Options::get_price_types();
							foreach ($listygo_price_types as $key => $type) {  // phpcs:ignore
								$listygo_slt = $price_type == $key ? " selected" : null;
								echo "<option value='{$key}'{$listygo_slt}>{$type}</option>";
							}
							?>
                        </select>
                    </div>
				<?php endif; ?>
                <div id="rtcl-price-items" class="rtcl-pricing-item<?php echo ! Functions::is_price_type_disabled() ? ( ' rtcl-price-type-' . esc_attr( $price_type ) ) : '' ?>">
                    <div class="form-group rtcl-price-item" id="rtcl-price-wrap">
                        <div class="price-wrap">
                            <label for="rtcl-price"><?php echo sprintf('<span class="price-label">%s [%s]</span>',
									esc_html__("Price", 'listygo'), Functions::get_currency_symbol()); ?><span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo esc_attr( $listing ? $listing->get_price() : '' ); ?>"
                                   name="price"
                                   id="rtcl-price"<?php echo esc_attr(!$price_type || $price_type == 'fixed' ? " required" : ''); ?>>
                        </div>
                        <div class="price-wrap rtcl-max-price rtcl-hide">
                            <label for="rtcl-max-price"><?php echo sprintf('<span class="price-label">%s [%s]</span>',
									__("Max Price", 'listygo'),
									Functions::get_currency_symbol()
								); ?><span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo esc_attr( $listing ? $listing->get_max_price() : '' ); ?>"
                                   name="_rtcl_max_price"
                                   id="rtcl-max-price"<?php echo esc_attr(!$price_type || $price_type == 'fixed' ? " required" : ''); ?>>
                        </div>
                    </div>
					<?php do_action('rtcl_listing_form_price_unit', $listing, $category_id); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div id="rtcl-custom-fields-list" data-post_id="<?php echo esc_attr($post_id); ?>">
		<?php do_action('wp_ajax_rtcl_custom_fields_listings', $post_id, $category_id );  // phpcs:ignore ?>
    </div>
	<?php if (!in_array('description', $hidden_fields)): ?>
        <div class="form-group">
            <label for="description"><?php esc_html_e('Description', 'listygo'); ?></label>
			<?php
			if ('textarea' == $editor) { ?>
                <textarea id="description" name="description" class="form-control" <?php echo esc_attr( $description_limit ? 'maxlength="' . $description_limit . '"' : '' ); ?> rows="8"><?php Functions::print_html($post_content); ?></textarea>
			<?php } else {
				wp_editor(
					$post_content,
					'description',
					array(
						'media_buttons' => false,
						'editor_height' => 200
					)
				);
			}
			if ( $description_limit ) {
				echo sprintf(
					'<div class="rtcl-hints">%s</div>',
					apply_filters(
						'rtcl_listing_description_character_limit_hints',
						sprintf(
						/* translators: %s: Character limit number */
							__( 'Character limit <span class="target-limit">%s</span>', 'listygo' ),
							$description_limit
						)
					)
				);
			}
			?>
        </div>
	<?php endif; ?>

	<?php if ( ! in_array( 'tags', $hidden_fields ) ): ?>
        <div class="form-group">
            <label for="description"><?php esc_html_e( 'Tags', 'listygo' ); ?></label>
            <div class="rtcl-tags-input-wrap">
                <div class="rtcl-tags-input">
					<?php
					$listygo_tags_data = array();
					if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
						foreach ( $tags as $tag ) {
							echo '<div><span class="rtcl-tag-term">' . esc_html( $tag->name ) . '</span><span class="remove">x</span></div>';
							$listygo_tags_data[] = esc_html( $tag->name );
						}
					}
					?>
                    <input type="text" autocomplete="off" id="new-tag-rtcl_tag" class="form-control"/>
                </div>
                <input type="hidden" id="rtcl_listing_tag" name="rtcl_listing_tag" value="<?php echo esc_attr( implode( ',', $listygo_tags_data ) ); ?>"/>
            </div>
        </div>
	<?php endif; ?>

    <!-- Restaurant & Doctor Fields -->
	<?php
	$listygo_args = [
		'category_id' => $category_id,
		'post_id' => $post_id
	];
	Helper::get_custom_listing_template( 'restaurant-form-fields', true, $listygo_args );
	Helper::get_custom_listing_template( 'doctor-form-fields', true, $listygo_args );
	?>

</div>