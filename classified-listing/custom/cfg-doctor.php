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

$listygo_doctor_group_id = isset( RDTListygo::$options['custom_doctor_fields_types'] ) ? RDTListygo::$options['custom_doctor_fields_types'] : [];

if ( $listygo_doctor_group_id ) {
	$listygo_field_ids   = Functions::get_cf_ids_by_cfg_id( $listygo_doctor_group_id );
}

?>

<?php
if (  Functions::isEnableFb() && Listing_Functions::form_builder_custom_group_field_check()){
	$listygo_listing_form = $listing->getForm();
	$listygo_designation = get_post_meta( $listing->get_id(), 'doctor-designation', true );
	$listygo_meetTime = get_post_meta( $listing->get_id(), 'doctor-meet-time', true );
	$listygo_designationIcon = $listygo_listing_form->getFieldByName('doctor-designation');
	$listygo_meetTimeIcon = $listygo_listing_form->getFieldByName('doctor-meet-time');
	if (!empty($listygo_designation)) {

		?>
        <li class="doctor-meta">
			<?php if ( isset( $listygo_designationIcon['icon']['class'] ) ) { ?>
                <span class="rtcl-cat-icon rtcl-icon rtcl-icon- demo-icon <?php echo esc_attr( $listygo_designationIcon['icon']['class'] ); ?>"></span>
			<?php } echo esc_html( $listygo_designation ); ?>
        </li>
	<?php } if (!empty($listygo_meetTime)) { ?>
        <li class="doctor-meta">
			<?php if ( isset( $listygo_meetTimeIcon['icon']['class'] ) ) { ?>
                <span class="rtcl-cat-icon rtcl-icon rtcl-icon- demo-icon <?php echo esc_attr( $listygo_meetTimeIcon['icon']['class'] ); ?>"></span>
			<?php } echo esc_html( $listygo_meetTime ); ?>
        </li>
	<?php } ?>
<?php } else {
	if ( ! empty( $listygo_field_ids ) ) {
		foreach ( $listygo_field_ids as $single_field ) { //phpcs:ignore
			$listygo_field = new RtclCFGField( $single_field );
			$listygo_value = $listygo_field->getFormattedCustomFieldValue( $listing->get_id() );
			$listygo_icon = $listygo_field->getIconClass();
			if ( ! $listygo_value || empty( $listygo_value ) ) {
				continue;
			}
			if ( $listygo_icon ){
				$listygo_icon = sprintf( '<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $listygo_icon );
			}
			?>
            <li class="doctor-meta <?php echo esc_attr( $listygo_value ); ?>">
				<?php
				printf(
					'%s %s',
					wp_kses_post( $listygo_icon ),
					esc_html( $listygo_value )
				);
				?>
            </li>
			<?php
		}
	}
}
?>