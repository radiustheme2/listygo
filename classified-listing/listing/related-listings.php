<?php
/**
 * @author        RadiusTheme
 * @package       listygo/templates
 * @version       1.0.0
 *
 * @var WP_Query $rtcl_related_query
 * @var array    $slider_options
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\listygo\RDTListygo;

if (!$rtcl_related_query->have_posts()) {
    return;
}
global $listing;


?>
<div class="related-listing-header">
    <div class="rtcl-related-title"><h4><?php echo esc_html( RDTListygo::$options['related_post_title'] ); ?></h4></div>
</div>
<div class="rtcl mb-3 rtcl-related-listing rtcl-listings">
    <div class="rtcl-related-listings">
        <div class="rtcl-related-slider-wrap">
            <div class="rtcl-related-slider rtcl-carousel-slider rtcl-grid-view" id="rtcl-related-slider"
                 data-options="<?php
                 //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                 echo htmlspecialchars(wp_json_encode($slider_options)); // WPCS: XSS ok. ?>">
                <div class="swiper-wrapper">
                    <?php
                    global $post;
                    while ($rtcl_related_query->have_posts()):
                        $rtcl_related_query->the_post();
                        $listygo_listing = rtcl()->factory->get_listing(get_the_ID());
                        $listygo_event_group_id = isset( RDTListygo::$options['custom_events_fields_types'] ) ? RDTListygo::$options['custom_events_fields_types'] : [];
                        $listygo_date = '';
                        if ( $listygo_event_group_id ) {
                            $listygo_field_ids   = Functions::get_cf_ids_by_cfg_id( $listygo_event_group_id );
                        }
                        if ( ! empty( $listygo_field_ids ) ) {
                            foreach ( $listygo_field_ids as $single_field ) { //phpcs:ignore
                                $listygo_field = new RtclCFGField( $single_field );
                                $listygo_value = $listygo_field->getFormattedCustomFieldValue( $listygo_listing->get_id() );
                                if ( ! $listygo_value ) {
                                    continue;
                                }
                                $listygo_date = $listygo_value ? $listygo_value : '';
                            }
                        }
                        $listygo_countdown = $listygo_date ? 'db-countdown dn-metalist' : 'dn-countdown';
                        ?>
                        <div class="swiper-slide rtcl-related-slider-item listing-item rtcl-listing-item">
                            <div class="related-item-inner grid-item">
                                <div class="product-box <?php echo esc_attr($listygo_countdown); ?>">
                                    <?php
                                        /**
                                         * Hook: rtcl_before_listing_loop_item.
                                         *
                                         * @hooked rtcl_template_loop_product_link_open - 10
                                         */
                                        do_action( 'rtcl_before_listing_loop_item' );

                                        /**
                                         * Hook: rtcl_listing_loop_item.
                                         *
                                         * @hooked listing_thumbnail - 10
                                         */
                                        do_action( 'rtcl_listing_loop_item_start' );

                                        /**
                                         * Hook: rtcl_listing_loop_item.
                                         *
                                         * @hooked loop_item_wrap_start - 10
                                         * @hooked loop_item_listing_title - 20
                                         * @hooked loop_item_labels - 30
                                         * @hooked loop_item_listable_fields - 40 
                                         * @hooked loop_item_meta - 50
                                         * @hooked loop_item_excerpt - 60
                                         * @hooked loop_item_wrap_end - 100
                                         */
                                        do_action('rtcl_listing_loop_item');

                                        /**
                                         * Hook: rtcl_after_listing_loop_item.
                                         *
                                         * @hooked listing_loop_map_data - 50
                                         */
                                        do_action( 'rtcl_after_listing_loop_item' );
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>