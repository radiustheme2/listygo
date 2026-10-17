<?php
/**
 *
 * @package ClassifiedListing/Templates
 * @version 5.2.0
 * @var Form $form
 * @var string $fieldUuid
 * @var FBField $field
 * @var Listing $field
 */

use Rtcl\Models\Listing;
use Rtcl\Models\Form\Form;
use Rtcl\Helpers\Functions;
use radiustheme\listygo\Helper;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

defined( 'ABSPATH' ) || exit;
global $listing;
if ( ! is_a( $field, FBField::class ) || ! is_a( $listing, Listing::class ) ) {
	return;
}
$listygo_value = $field->getFormattedCustomFieldValue( $listing->get_id() );

if ( empty( $listygo_value ) ) {
	return;
}

$listygo_listing_args = [
	'form'       => $form,
	'listing_id' => $listing->get_id()
];

$listygo_repeaterItemFields = $field->getField();
$listygo_is_collapsable     = isset( $listygo_repeaterItemFields['collapsable'] ) && $listygo_repeaterItemFields['collapsable'] == 'yes' ? 'rtcl-is-collapsable' : 'rtcl-not-collapsable';
$listygo_layout             = isset( $listygo_repeaterItemFields['layout'] ) ? 'layout_' . $listygo_repeaterItemFields['layout'] : '';
$listygo_icon               = $field->getIconData();
$listygo_labelPlacement     = !empty( $field->getSlField()['label_placement'] ) ? $field->getSlField()['label_placement'] : '';

if ( 'rtcl_listygo_food_list' === $field->getName() ) {
	Helper::get_custom_listing_template( 'c-fields_food_menu', true, $listygo_listing_args );
} else if ( 'rtcl_listygo_doctor_chamber' === $field->getName() ) {
	Helper::get_custom_listing_template( 'c-fields_chamber_list', true, $listygo_listing_args );
} else {

?>
    <div class="rtcl-sl-element <?php echo esc_attr( $listygo_is_collapsable . ' ' . $listygo_layout ); ?>  label-<?php echo esc_attr( $listygo_labelPlacement ); ?>">
	<?php
	if ( ( ! empty( $listygo_icon['type'] ) && 'class' === $listygo_icon['type'] && ! empty( $listygo_icon['class'] ) ) || ! empty( $field->getLabel() ) ) {
		?>
        <div class="rtcl-slf-label-wrap rtcl-repeater-group-title">
			<?php
			if ( ! empty( $listygo_icon['type'] ) && 'class' === $listygo_icon['type'] && ! empty( $listygo_icon['class'] ) ) {
				?>
                <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $listygo_icon['class'] ); ?>"></i></div>
				<?php
			}
			if ( ! empty( $field->getLabel() ) ) {
				?>
                <div class='rtcl-slf-label'><?php echo esc_html( $field->getLabel() ); ?></div>
				<?php
			}
			?>
        </div>
	<?php } ?>
    <div class="rtcl-slf-value">
		<?php
		$listygo_repeaterFields = $field->getData( 'fields', [] );
		if ( ! empty( $listygo_repeaterFields ) && is_array( $listygo_value ) ) {
			?>
            <div class="rtcl-slf-repeater-items">
				<?php
				foreach ( $listygo_value as $listygo_rValueIndex => $listygo_rValues ) { //phpcs:ignore
					?>
                    <div class="rtcl-slf-repeater-item">
						<?php
						foreach ( $listygo_repeaterFields as $listygo_repeaterField ) {
							$listygo_rField = new FBField( $listygo_repeaterField );
							$listygo_rValue = 'file' === $listygo_rField->getElement() ? ( ! empty( $listygo_rValues[ $listygo_rField->getName() ] )
							                                               && is_array( $listygo_rValues[ $listygo_rField->getName() ] )
								? FBHelper::getFieldAttachmentFiles( $listing->get_id(), $listygo_rField->getField(), $listygo_rValues[ $listygo_rField->getName() ], true )
								: [] ) : ( $listygo_rValues[ $listygo_rField->getName() ] ?? '' );
							?>
                            <div class="rtcl-slf-repeater-field <?php echo esc_attr( $listygo_rField->getElement() ) ?>"
                                 data-name="<?php echo esc_attr( $field->getName() ); ?>"
                                 data-uuid="<?php echo esc_attr( $field->getUuid() ); ?>">
								<?php
								$listygo_rIcon = $listygo_rField->getIconData();
								if ( ( ! empty( $listygo_rIcon['type'] ) && 'class' === $listygo_rIcon['type'] && ! empty( $listygo_rIcon['class'] ) )
								     || ! empty( $listygo_rField->getLabel() )
								) {
									?>
                                    <div class="rtcl-slf-label-wrap">
										<?php
										if ( ! empty( $listygo_rIcon['type'] ) && 'class' === $listygo_rIcon['type'] && ! empty( $listygo_rIcon['class'] ) ) {
											?>
                                            <div class="rtcl-field-icon"><i
                                                        class="<?php echo esc_attr( $listygo_rIcon['class'] ); ?>"></i>
                                            </div>
											<?php
										}
										if ( ! empty( $listygo_rField->getLabel() ) ) { ?>
                                            <div class='rtcl-slf-label'><?php echo esc_html( $listygo_rField->getLabel() ); ?></div>
											<?php
										}
										?>
                                    </div>
								<?php } ?>
                                <div class="rtcl-slf-value">
									<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $listygo_rValue, $listygo_rField ) ); ?>
                                </div>
                            </div>
							<?php
						}
						?>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		}
		?>
    </div>
</div>
<?php }