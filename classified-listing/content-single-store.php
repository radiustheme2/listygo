<?php
/**
 * Store single content
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.3.21
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;
use radiustheme\listygo\Listing_Functions;
use RtclStore\Helpers\Functions as StoreFunctions;

global $store;

if ( StoreFunctions::is_store_expired() ) {
	do_action( 'rtcl_single_store_expired_content' );
	return;
}

$listygo_banner_class = $store->get_banner_url() ? '' : ' rtin-noimage';
$listygo_member_since = esc_html__( 'Member since - ', 'listygo' ) . get_the_time( get_option( 'date_format' ) );

do_action( 'rtcl_before_single_store' );

?>

    <div class="row rtin-banner-wrap">
        <div class="col-sm-12">
            <div class="rtin-banner-wrap">
				<?php do_action( 'rtcl_before_single_store_content' ); ?>
                <div class="rtin-banner-img<?php echo esc_attr( $listygo_banner_class ); ?>">
					<?php if ( ! $listygo_banner_class ): ?>
						<?php $store->the_banner(); ?>
					<?php endif; ?>
                </div>
                <div class="rtin-banner-content">
					<?php if ( $store->get_logo_url() ): ?>
                        <div class="rtin-logo">
							<?php $store->the_logo(); ?>
                        </div>
					<?php endif; ?>
                    <div class="rtin-store-title-area">
                        <!-- Store Title -->
                        <h2 class="rtin-store-title"><?php $store->the_metas(); ?></h2>
						<?php if ( $store->get_the_slogan() ): ?>
                            <div class="rtin-store-slogan"><?php $store->the_slogan(); ?></div>
						<?php endif; ?>

                        <!-- Store Information -->
                        <ul class="rtin-title-meta">
							<?php if ( $listygo_store_address = $store->get_address() ){ ?>
                                <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i><?php echo esc_html( $listygo_store_address ); ?></li>
							<?php } ?>

							<?php if ( $listygo_stor_view_count = Listing_Functions::rt_get_post_view_count( $store->get_id() ) ) { ?>
								<?php
								/* translators: %d: Total number of views */
								$listygo_view_title = sprintf( __( 'Total Views: %d', 'listygo' ), $listygo_stor_view_count );
								?>
                                <li <?php if ( $listygo_stor_view_count > 999 ) echo 'data-toggle="tooltip" data-placement="top" title="' . esc_attr( $listygo_view_title ) . '"'; ?>>
                                    <i class="fas fa-eye"></i>
									<?php
									if ( $listygo_stor_view_count === 1 ) {
										/* translators: %d: Number of views */
										$listygo_label = sprintf( esc_html__( 'View: %d', 'listygo' ), $listygo_stor_view_count );
									} else {
										/* translators: %d: Number of views */
										$listygo_label = sprintf( esc_html__( 'Views: %d', 'listygo' ), $listygo_stor_view_count );
									}
									printf(
										'<span class="count-label">%s</span><span class="count-number">%s</span>',
										esc_html( $listygo_label ),
										esc_html( Helper::rt_number_shorten( $listygo_stor_view_count, 1 ) )
									);
									?>
                                </li>
							<?php } ?>

							<?php if ( $store->is_rating_enable() && $store->get_review_counts() ){
								$listygo_r_label = absint( $store->get_review_counts() ) < 2 ? esc_html__( 'Review', 'listygo' ) : esc_html__( 'Reviews', 'listygo' );
								?>
                                <li class="store-rating">
									<?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
                                    <span class="reviews-rating-count">
                                        <?php printf( "(%s %s)", esc_html( $listygo_r_label ), absint( $store->get_review_counts() ) ); ?>
                                    </span>
                                </li>
							<?php } if ( $store->get_category() ){ ?>
                                <li class="rtin-store-category">
                                    <i class="fa-solid fa-tags"></i>
									<?php
									echo wp_kses( $store->get_category(), [
										'a' => [
											'href'  => [],
											'title' => [],
										],
									] );
									?>
                                </li>
							<?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row store-information product-grid">
        <div class="col-lg-8 col-sm-12">
            <div class="widget single-agent-tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#overview" role="tab" aria-selected="true">
							<?php esc_html_e( 'Over View', 'listygo' ); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#listing" role="tab" aria-selected="false">
							<?php esc_html_e( 'Our Listing', 'listygo' ); ?>
                        </a>
                    </li>

					<?php if ( $store->is_rating_enable() && $store->get_review_counts() && false ): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#reviews" role="tab" aria-selected="false">
								<?php esc_html_e( 'Reviews', 'listygo' ); ?>
                            </a>
                        </li>
					<?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="overview" role="tabpanel">
                        <div class="about-stor">
                            <h3><?php esc_html_e( 'About Us', 'listygo' ); ?></h3>
							<?php if ( $store->get_the_description() ): ?>
								<?php $store->the_description(); ?>
							<?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="listing" role="tabpanel">
						<?php Functions::get_template( 'store/ad-listing', '', '', rtclStore()->get_plugin_template_path() ); ?>
                    </div>
					<?php if ( $store->is_rating_enable() && $store->get_review_counts() && false ): ?>
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
							<?php
							$listygo_r_label = absint( $store->get_review_counts() ) < 2 ? esc_html__( 'Review', 'listygo' ) : esc_html__( 'Reviews', 'listygo' );
							?>
							<?php //var_dump($store->get_average_rating()); ?>
							<?php echo wp_kses_post( Functions::get_rating_html( $store->get_average_rating(), $store->get_review_counts() ) ); ?>
                            <span class="reviews-rating-count">
                                <?php printf( "(%s %s)", esc_html( $listygo_r_label ), absint( $store->get_review_counts() ) ); ?>
                            </span>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="store-info">
				<?php Helper::get_custom_store_template( 'sidebar-store', true, get_defined_vars() ); ?>
            </div>
        </div>
    </div>

<?php
do_action( 'rtcl_after_single_store' );