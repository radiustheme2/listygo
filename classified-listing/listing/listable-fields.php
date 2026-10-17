<?php
/**
 *
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;
$listygo_show_custom_fields_label = RDTListygo::$options['show_custom_fields_label'];

$listygo_cfgc = 'label-disable';
if ($listygo_show_custom_fields_label) {
    $listygo_cfgc = 'label-enable';
}

if ( count( $fields ) ) :
	ob_start();
	foreach ( $fields as $field ) : //phpcs:ignore

		$listygo_field = new RtclCFGField( $field->ID );
		$listygo_value = $listygo_field->getFormattedCustomFieldValue( $listing_id );

		if ( $listygo_value ) :
			?>
            <li>
                <?php if ( $listygo_show_custom_fields_label ) { ?>
    				<?php if ( $listygo_field->getIconClass() ): ?>
                        <i class='rtcl-icon rtcl-icon-<?php echo esc_html( $listygo_field->getIconClass() ); ?>'></i>
    				<?php else: ?>
                        <span class='listable-label'><?php echo esc_html( $listygo_field->getLabel() ); ?></span>
    				<?php endif; ?>
                <?php } ?>

                <span class='listable-value'>
                    <span class="value">
                    <?php
                    $listygo_value = ( ! is_array( $listygo_value ) && strlen( $listygo_value ) == 1 ) ? $listygo_value : $listygo_value;
                        echo wp_kses_post( stripslashes_deep( $listygo_value ) );
                    ?>
                    </span>
                </span>
            </li>
		<?php endif;
	endforeach;

	$listygo_fields_html = ob_get_clean();
	if ( $listygo_fields_html ) {
		printf( '<ul class="product-features %s">%s</ul>', esc_attr( $listygo_cfgc ), wp_kses_post( $listygo_fields_html ) );
	}
endif;
