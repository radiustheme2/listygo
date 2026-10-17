<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use radiustheme\listygo\RDTListygo;
use Rtcl\Helpers\Functions;
global $listing;

$listygo_sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );
$listygo_sidebar_class = [
	'col-lg-4',
	'order-2',
];
if ( $listygo_sidebar_position == "left" ) {
	$listygo_sidebar_class   = array_diff( $listygo_sidebar_class, [ 'order-2' ] );
	$listygo_sidebar_class[] = 'order-1';
} elseif ( $listygo_sidebar_position == "bottom" ) {
	$listygo_sidebar_class   = array_diff( $listygo_sidebar_class, [ 'col-lg-4' ] );
	$listygo_sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}
?>

<!-- Seller / User Information -->
<div id="sticky_sidebar" class="<?php echo esc_attr( implode( ' ', $listygo_sidebar_class ) ); ?>">
    <div class="listing-sidebar">
		<?php 
			do_action( 'listygo_before_sidebar_ad' );
			if (RDTListygo::$options['listing_list_information']) {
				$listing->the_user_info();
			} 
			if (RDTListygo::$options['listing_list_timing']) {
				do_action('rtcl_single_listing_business_hours');
			} 
		?>
		<?php if ( is_active_sidebar( 'listing-single-sidebar' ) ): ?>
            <aside class="sidebar-widget">
				<?php dynamic_sidebar( 'listing-single-sidebar' ); ?>
            </aside>
		<?php 
			endif; 
			do_action( 'listygo_after_sidebar_ad' );
		?>
    </div>
</div>
