<?php
/**
 * Author Listing
 *
 * @author     RadiusTheme
 * @package    ClassifiedListing/Templates
 * @version    2.2.1.1
 */

use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$listygo_author   = get_user_by( 'slug', get_query_var( 'author_name' ) );
$listygo_user_id  = $listygo_author->ID;
$listygo_phone    = get_user_meta( $listygo_user_id, '_rtcl_phone', true );
$listygo_whatsApp = get_user_meta( $listygo_user_id, '_rtcl_whatsapp_number', true );
$listygo_website  = get_user_meta( $listygo_user_id, '_rtcl_website', true );
$listygo_pp_id    = absint( get_user_meta( $listygo_user_id, '_rtcl_pp_id', true ) );
?>
<div class="rtcl-user-single-wrapper rtcl">
    <div class="rtcl-user-info-wrap">
        <div class="rtcl-user-img">
			<?php
			$listygo_profile_image = '';
			if ( ! empty( $listygo_pp_id ) ) {
				$listygo_profile_image = wp_get_attachment_image( $listygo_pp_id, [400, 240] );
			} else {
				$listygo_profile_image = get_avatar( $listygo_user_id );
			}
			echo wp_kses_post( $listygo_profile_image );
			?>
        </div>
        <div class="rtcl-user-info">

            <h3 class="user-name">
				<?php echo esc_html($listygo_author->display_name); ?>
				<?php do_action('rtcl_after_author_meta', $listygo_user_id ); ?>
            </h3>
			<?php echo esc_html($listygo_author->description); ?>
            <div class="rtcl-user-meta">
				<?php if ( $listygo_phone && Functions::check_visibility( $listygo_user_id, 'phone' ) ): ?>
                    <div class="item-phone">
                        <i class="rtcl-icon rtcl-icon-phone"></i>
                        <a href="tel:<?php echo esc_attr( $listygo_phone ); ?>"><?php echo esc_html( $listygo_phone ); ?></a>
                    </div>
				<?php endif; ?>
				<?php if ( $listygo_whatsApp && Functions::check_visibility( $listygo_user_id, 'whatsapp' ) ): ?>
                    <div class="item-whatsapp">
                        <i class="rtcl-icon rtcl-icon-whatsapp"></i>
                        <a target="_blank" href="https://wa.me/<?php echo esc_attr( $listygo_whatsApp ); ?>">
							<?php echo esc_html( $listygo_whatsApp ); ?>
                        </a>
                    </div>
				<?php endif; ?>
	            <?php if ( Functions::check_visibility( $listygo_user_id, 'phone' ) ): ?>
                <div class="item-contact">
                    <i class="rtcl-icon rtcl-icon-envelope-open"></i>
                    <a href="mailto:<?php echo esc_attr( $listygo_author->user_email ); ?>"><?php echo esc_html( $listygo_author->user_email ); ?></a>
                </div>
	            <?php endif; ?>
				<?php if ( $listygo_website ): ?>
                    <div class="item-whatsapp">
                        <i class="rtcl-icon rtcl-icon-link"></i>
                        <a target="_blank"
                           href="<?php echo esc_url( $listygo_website ); ?>"><?php echo esc_url( $listygo_website ); ?></a>
                    </div>
				<?php endif; ?>
            </div>

			<?php
			$listygo_social_list = Functions::get_user_social_profile( $listygo_user_id );
			if ( ! empty( $listygo_social_list ) ) {
				?>
                <div class="rtcl-user-social">
					<?php
					foreach ( $listygo_social_list as $item => $value ) { //phpcs:ignore
						if ( 'twitter' === $item ) {
							$listygo_iconClass = 'fa-brands fa-x-twitter';
						} else {
							$listygo_iconClass = 'rtcl-icon-' . $item;
						}
						?>
                        <a target="_blank" href="<?php echo esc_url( $value ); ?>">
                            <i class="rtcl-icon <?php echo esc_attr( $listygo_iconClass ); ?>"></i>
                        </a>
						<?php
					}
					?>
                </div>
			<?php } ?>

            <?php do_action( 'rtcl_author_details_after_meta', $listygo_user_id ); ?>

        </div>
    </div>
	<?php Functions::get_template( 'listing/author-listing'); ?>
</div>