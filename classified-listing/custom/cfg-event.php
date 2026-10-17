<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

global $listing;

if (  Functions::isEnableFb() && Listing_Functions::form_builder_custom_group_field_check()) {
	$listygo_event_date = get_post_meta( $listing->get_id(), 'event-date', true );
	$listygo_countDown = 'has-countdown';
	if (!empty($listygo_event_date)) {
		?>
        <li class="entry-countdown <?php echo esc_attr( $listygo_countDown ); ?>">
            <div class="countdown-content">
                <div data-countdown="<?php echo esc_attr( $listygo_event_date ); ?>" class="event-countdown"></div>
            </div>
        </li>
		<?php
	}
} else {

	$listygo_event_group_id = isset( RDTListygo::$options['custom_events_fields_types'] ) ? RDTListygo::$options['custom_events_fields_types'] : [];

	if ( $listygo_event_group_id ) {
		$listygo_field_ids = Functions::get_cf_ids_by_cfg_id( $listygo_event_group_id );
	}

	$listygo_date = '';

	?>

	<?php
	if ( ! empty( $listygo_field_ids ) ) {
		foreach ( $listygo_field_ids as $single_field ) { //phpcs:ignore
			$listygo_field = new RtclCFGField( $single_field );
			$listygo_value = $listygo_field->getFormattedCustomFieldValue( $listing->get_id() );
			if ( ! $listygo_value || empty( $listygo_value ) ) {
				continue;
			}
			$listygo_countDown = 'has-countdown';
			$listygo_countdown_time  = strtotime( $listygo_value );

			$listygo_date = gmdate( 'Y/m/d H:i:s', $listygo_countdown_time );
			if (!empty($listygo_date)) {
				?>
                <li class="entry-countdown <?php echo esc_attr( $listygo_countDown ); ?>">
                    <div class="countdown-content">
                        <div data-countdown="<?php echo esc_attr( $listygo_date ); ?>" class="event-countdown"></div>
                    </div>
                </li>
				<?php
			}
		}
	}
}
?>