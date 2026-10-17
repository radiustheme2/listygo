<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 * @var $listygo_sidebar_position string
 * @var $listygo_content_class array
 */

use radiustheme\listygo\Listing_Functions;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

defined('ABSPATH') || exit;
$listygo_banner = Listing_Functions::listing_single_banner_option();
global $listing;
$listygo_feature_img = '';
$listygo_images = $listing->get_images();
$listygo_video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
if (empty($listygo_images)) {
    $listygo_feature_img = 'feature-img-not-set';
}
if (!empty($listygo_images || $listygo_video_urls)) {

?>

<div class="page-header">
  <?php 
    if ( $listygo_banner == 'slider' ) {
      Listing_Functions::listing_details_slider();
    } else {
      Listing_Functions::listing_details_banner();
    }
  ?>
</div>
<?php } ?>
<!-- listing Details --> 
<section class="listing-details listing-details--layout1 <?php echo esc_attr( $listygo_feature_img ); ?>">
  <?php Helper::get_custom_listing_template( 'listing-single-header-1' ); ?>
  <div class="listingDetails-main">
    <div class="container">
      <div class="row g-30">
        <div class="<?php echo esc_attr(implode(' ', $listygo_content_class)); ?>">
          <?php Helper::get_custom_listing_template( 'listing-single-main' ); ?>
        </div>
        <?php if ( in_array( $listygo_sidebar_position, array( 'left', 'right' ) ) ) : ?>
          <?php do_action('rtcl_single_listing_sidebar'); ?>
        <?php endif; ?>
      </div>
    </div>
    <?php if ( RDTListygo::$options['show_related_listing'] ) : ?>
    <div class="container">
      <div class="row">
        <div class="col-12 listing-title-wrap-enable">
          <?php $listing->the_related_listings(); ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>
<!-- listing Details End -->