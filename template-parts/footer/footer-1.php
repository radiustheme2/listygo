<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_widgets_items1 = RDTListygo::$options['f1_widgets_area'];

if ( has_nav_menu( 'crmenu' ) ) {
    $listygo_cfcols = '6';
} else {
    $listygo_cfcols = '12 text-center';
}
?>

<!--=====================================-->
<!--=         Footer Area Start         =-->
<!--=====================================-->
<footer class="footer footer--layout1">
    <?php if ( is_active_sidebar( 'footer-widget-1-1' ) || is_active_sidebar( 'footer-widget-1-2' ) || is_active_sidebar( 'footer-widget-1-3' ) || is_active_sidebar( 'footer-widget-1-4' ) ) { ?>
    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between">
                <?php for ( $i = 1; $i <= $listygo_widgets_items1; $i++ ) { //phpcs:ignore ?>
                <div class="col-lg-<?php echo esc_attr(RDTListygo::$options['f1_area'.$i.'_column']); ?> col-md-6">
                    <?php dynamic_sidebar( 'footer-widget-1-'.esc_attr($i) ); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-<?php echo esc_attr( $listygo_cfcols ); ?>">
                    <p class="footer-copyright mb-0"><?php echo wp_kses_post( RDTListygo::$options['copyright_text'] ); ?></p>
                </div>
                <?php if ( has_nav_menu( 'crmenu' ) ) { ?>
                    <div class="col-lg-6">
                        <div class="footer-menu footer-menu--style2">
                            <?php
                                wp_nav_menu( [
                                    'theme_location' => 'crmenu',
                                    'depth'          => 1,
                                    'container'      => 'ul',
                                    'menu_class'     => 'footer-bottom-link',
                                ]);
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</footer>
<!--=====================================-->
<!--=          Footer Area End          =-->
<!--=====================================-->    