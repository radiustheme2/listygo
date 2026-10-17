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
use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use radiustheme\listygo\Helper;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

defined( 'ABSPATH' ) || exit;
global $listing;
if ( !is_a( $field, FBField::class ) || !is_a( $listing, Listing::class ) ) {
	return;
}
$listygo_value = $field->getFormattedCustomFieldValue( $listing->get_id() );

if ( empty( $listygo_value ) ) {
	return;
}
$listygo_icon = $field->getIconData();
$listygo_labelPlacement = !empty( $field->getSlField()['label_placement'] ) ? $field->getSlField()['label_placement'] : '';

if ( 'event-date' === $field->getName() ) {
    echo '<ul class="single-builder-event-date">';
	Helper::get_custom_listing_template('cfg-event');
	echo '</ul>';
} else {
?>
    <div class="rtcl-sl-element label-<?php echo esc_attr( $listygo_labelPlacement ); ?>">
        <?php
        if ( ( !empty( $listygo_icon['type'] ) && 'class' === $listygo_icon['type'] && !empty( $listygo_icon['class'] ) ) || !empty( $field->getLabel() ) ) {
            ?>
            <div class="rtcl-slf-label-wrap">
                <?php
                if ( !empty( $listygo_icon['type'] ) && 'class' === $listygo_icon['type'] && !empty( $listygo_icon['class'] ) ) {
                    ?>
                    <div class="rtcl-field-icon"><i class="<?php echo esc_attr( $listygo_icon['class'] ); ?>"></i></div>
                    <?php
                }
                if ( !empty( $field->getLabel() ) ) {
                    ?>
                    <div class='rtcl-slf-label'><?php echo esc_html( $field->getLabel() ); ?></div>
                    <?php
                }
                ?>
            </div>
        <?php } ?>
        <div class="rtcl-slf-value">
            <?php Functions::print_html( FBHelper::getFormattedFieldHtml( $listygo_value, $field ) ); ?>
        </div>
    </div>
<?php }