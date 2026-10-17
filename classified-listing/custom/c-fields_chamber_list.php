<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates/listing
 * @version       3.0.0
 *
 * @var Form $form
 * @var array $fields
 * @var int $listing_id
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

if ( ! is_a( $form, Form::class ) ) {
	return;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$fields_all = $form->getFieldAsGroup( FBField::CUSTOM );

if ( empty( $fields_all['rtcl_listygo_doctor_chamber'] ) ) {
	return;
}
$fields = [ $fields_all['rtcl_listygo_doctor_chamber'] ];

$section_title = '';
if ( count( $fields ) ) :
	$fields = FBHelper::reOrderCustomField( $fields );
	ob_start();

	foreach ( $fields as $index => $field ) {
		$field = new FBField( $field );
		if ( ! $field->isSingleViewAble() ) {
			continue;
		}
		$value = $field->getFormattedCustomFieldValue( $listing_id );

		if ( empty( $value ) ) {
			continue;
		}

		if ( ! empty( $field->getLabel() ) ) {
			$section_title = $field->getLabel();
		}

		if ( ! empty( $value ) && is_array( $value ) ) {
			?>
            <div class="rtcl-faq-repeater-items">
				<?php
				$count = 1;
				foreach ( $value as $rValueIndex => $rValues ) {
					$values    = array_values( $rValues );
					$is_active = $count == 1 ? 'active' : '';
                    $chamber_name = $values[0] ?? 0;
                    $chamber_location = $values[1] ?? 0;
                    $chamber_time  = $values[2] ?? 0;
                    $chamber_phone = $values[3] ?? 0;
                    $chamber_image = $values[4] ?? 0;
					$phone_url = str_replace(' ', '', $chamber_phone);
					if ( ! empty( $chamber_location || $chamber_time || $chamber_phone || $chamber_image ) ):
					?>
                    <div class="faq-item <?php echo esc_attr( $is_active ) ?>">
                        <div class="faq-heading">
							<?php echo esc_html( $chamber_name ); ?>
                            <button class="collapse-btn">
                                <i class="iconPlus fa-solid fa-plus"></i>
                                <i class="iconMinus fa-solid fa-minus"></i>
                            </button>
                        </div>

                        <div class="faq-content">
                            <div class="faq-content-inner">
                                <?php if ( ! empty( $chamber_image[0] ) ): ?>
                                    <div class="clinic-design blocks-gallery-item">
                                        <?php echo wp_get_attachment_image( $chamber_image[0], 'full' ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                    if ( ! empty( $chamber_location || $chamber_time || $chamber_phone ) ): ?>
                                    <div class="info">
                                        <?php if ( ! empty( $chamber_location ) ){ ?>
                                            <h6><?php echo esc_html( $chamber_location ); ?></h6>
                                        <?php }
                                        if ( ! empty( $chamber_time ) ){
                                            ?>
                                            <div class="chamber-time">
                                                <?php echo esc_html( $chamber_time ); ?>
                                            </div>
                                        <?php } if ( ! empty( $chamber_phone ) ){ ?>
                                            <p class="meta-phone">
                                                <b><?php esc_html_e( 'Call for appointment:', 'listygo' ); ?></b>
                                                <a href="tel:<?php echo esc_attr( $phone_url ); ?>">
                                                    <?php echo esc_html( $chamber_phone ); ?>
                                                </a>
                                            </p>
                                        <?php } ?>
                                    </div>
                                <?php
                                    endif;
                                ?>
                            </div>
                        </div>
                    </div>
					<?php
                    endif;
					$count ++;
				}
				?>
            </div>
			<?php
		}
	}

	$fieldData = ob_get_clean();
	if ( $fieldData ) :
		?>
        <div class="rtcl-single-faqs-wrapper rtcl-content-section">
            <div class="rtcl-section-heading-simple">
				<?php printf(
					"<h3>%s</h3>",
					$section_title ? esc_html( $section_title ) : esc_html__( "Frequently Asked Question", "listygo" )
				); ?>
            </div>
            <div class="rtcl-faqs-content">
				<?php Functions::print_html( $fieldData, true ); ?>
            </div>
        </div>
	<?php
	endif;
	?>
<?php
endif;
