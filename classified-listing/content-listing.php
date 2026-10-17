<?php
/**
 * @package ClassifiedListing/Templates
 * @version 1.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;

global $listing;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) { //phpcs:ignore
	$listygo_view = esc_attr( $_GET['view'] ); //phpcs:ignore
} else {
	$listygo_view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

?>
<div <?php Functions::listing_class(); ?> <?php Functions::listing_data_attr_options(); ?>>
	<?php
		if ( $listygo_view == 'grid' ) {
			Helper::get_custom_listing_template( 'grid' );
		} else {
			Helper::get_custom_listing_template( 'list' );
		}
	?>
</div>
