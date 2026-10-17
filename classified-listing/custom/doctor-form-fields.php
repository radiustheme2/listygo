<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5.3
 *
 * @var $category_id int
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use radiustheme\listygo\Listing_Functions;

$listygo_doctor_cat_id = Listing_Functions::get_listing_doctor_category_id();
$listygo_has_doctor    = Listing_Functions::listygo_selected_category_fields( $category_id, $listygo_doctor_cat_id );

if ( $listygo_has_doctor && Listing_Functions::is_enable_doctor_listing() ):

	$listygo_generalSettings = Functions::get_option( 'rtcl_general_settings' );
	$listygo_text            = isset( $listygo_generalSettings['listygo_chamber_section_label'] ) && ! empty( $listygo_generalSettings['listygo_chamber_section_label'] ) ? $listygo_generalSettings['listygo_chamber_section_label'] : '';
	?>
    <div class="additional-input-wrap">
        <div class="form-group">
            <label></label>
        </div>
        <div class="rtcl-post-section-title">
            <h3><i class="rtcl-icon rtcl-icon-clock"></i><?php echo esc_html( $listygo_text ); ?>:</h3>
        </div>

        <div class="doctor-chamber-wrapper">
            <div class="dr-clinic-wrap">
				<?php
				$listygo_chamberList = get_post_meta( $post_id, 'listygo_doctor_chamber', true );
				if ( ! empty( $listygo_chamberList ) ) {
					$listygo_remove_chamber_nonce = wp_create_nonce( 'rtcl_remove_chamber_image' );
					$listygo_count = 0;

					foreach ( $listygo_chamberList as $chamber ) { // phpcs:ignore
						$listygo_cname        = $chamber['cname'] ?? '';
						$listygo_cloac        = $chamber['cloaction'] ?? '';
						$listygo_time         = $chamber['time'] ?? '';
						$listygo_phone        = $chamber['phone'] ?? '';
						$listygo_chamberImage = $chamber['chamber_img'] ?? '';
						?>
                        <div class="dr-clinic-item">
                            <span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                            <div class="rn-recipe-title">
                                <input type="text" name="listygo_doctor_chamber[<?php echo esc_attr( $listygo_count ); ?>][cname]" class="form-control" placeholder="<?php esc_attr_e( 'Chamber', 'listygo' ); ?>" value="<?php echo esc_attr( $listygo_cname ); ?>">
                                <textarea rows="3" cols="10" class="form-control" name="listygo_doctor_chamber[<?php echo esc_attr( $listygo_count ); ?>][cloaction]" placeholder="<?php esc_attr_e( 'Location', 'listygo' ); ?>"><?php echo esc_html( $listygo_cloac ); ?></textarea>
                            </div>
                            <div class="rn-ingredient-item">
                                <div class="rn-ingredient-fields">
                                    <input type="text" placeholder="<?php esc_attr_e( 'Time', 'listygo' ); ?>"
                                           class="form-control"
                                           name="listygo_doctor_chamber[<?php echo esc_attr( $listygo_count ); ?>][time]"
                                           value="<?php echo esc_attr( $listygo_time ); ?>">
                                    <input type="text" placeholder="<?php esc_attr_e( 'Phone', 'listygo' ); ?>"
                                           class="form-control"
                                           name="listygo_doctor_chamber[<?php echo esc_attr( $listygo_count ); ?>][phone]"
                                           value="<?php echo esc_attr( $listygo_phone ); ?>">
                                </div>
                            </div>
                            <div class="chamber-image-wrap">
								<?php if ( ! empty( $listygo_chamberImage ) ): ?>
                                    <div class="chamber-image">
                                        <input name="listygo_doctor_chamber[<?php echo esc_attr( $listygo_count ); ?>][attachment_id]"
                                               type="hidden" value="<?php echo esc_attr( $listygo_chamberImage ); ?>">
										<?php echo wp_get_attachment_image( $listygo_chamberImage, 'full' ); ?>
                                        <div class="remove-chamber-image">
                                            <a href="#"
                                               data-index="<?php echo absint( $listygo_count ); ?>"
                                               data-post_id="<?php echo esc_attr( $post_id ); ?>"
                                               data-attachment_id="<?php echo esc_attr( $listygo_chamberImage ); ?>"
                                               data-nonce="<?php echo esc_attr( $listygo_remove_chamber_nonce ); ?>">
												<?php esc_html_e( 'Remove Image', 'listygo' ); ?>
                                            </a>
                                        </div>
                                    </div>
								<?php endif; ?>
                                <div class="chamber-input-wrapper <?php echo esc_attr( $listygo_chamberImage ? 'd-none' : '' ); ?>">
                                    <input name="listygo_chamber_img[<?php echo esc_attr( $listygo_count ); ?>]" class="listygo-chamber-image" type="file"/>
                                </div>
                            </div>
                        </div>
						<?php
						$listygo_count++;
					}
				}
				?>
            </div>
            <div class="rn-recipe-actions">
                <a href="javascript:void()" class="btn-upload add-ingredient add-recipe btn-sm btn-primary"><?php esc_html_e( 'Add Chamber', 'listygo' ); ?></a>
            </div>
        </div>
    </div>
<?php endif; ?>