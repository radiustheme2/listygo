<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.2.8
 */

use radiustheme\listygo\RDTListygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_sticky_header = '';
$listygo_search = RDTListygo::$options['header_mobile_login'] ? '' : 'login-mobi-disable';
$listygo_button = RDTListygo::$options['header_mobile_listing'] ? '' : 'button-mobi-disable';
$listygo_toggle = RDTListygo::$options['header_mobile_toggle'] ? '' : 'toggle-mobi-disable';
$listygo_menuClass = $listygo_search.' '.$listygo_button.' '.$listygo_toggle;
if ( RDTListygo::$options['sticky_header'] == 1 ) {
    $listygo_sticky_header = 'header-sticky';
}
$listygo_menu_layout_settings = get_post_meta( get_the_id(), 'listygo_layout_settings', true );
$listygo_menu_layout = ( empty( $listygo_menu_layout_settings['listygo_header_menu'] ) || $listygo_menu_layout_settings['listygo_header_menu'] == 'default' ) ? RDTListygo::$options['menu_box_layout'] : $listygo_menu_layout_settings['listygo_header_menu'];

if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
    if ( is_post_type_archive( rtcl()->post_type ) || is_tax( 'rtcl_category' ) || is_tax( 'rtcl_tag' ) ) { 
        $listygo_menu_layout = RDTListygo::$options['listing_archive_box_layout'] ? RDTListygo::$options['listing_archive_box_layout'] : $listygo_menu_layout;
    }
}

?>

<!--=====================================-->
<!--=          Header Menu Start header-one        =-->
<!--=====================================-->
<header id="site-header" class="header-area header-area-1 <?php echo esc_attr( $listygo_menuClass ); ?>">
    <?php 
        if ( RDTListygo::$header_top == '1' || RDTListygo::$header_top === "on" ) {
            get_template_part( 'template-parts/header/header', 'top' );
        }
    ?>
    <div id="sticky-placeholder"></div>
    <div class="header-main <?php echo esc_attr( $listygo_sticky_header ); ?>">
        <div class="<?php echo esc_attr( $listygo_menu_layout ); ?>">
            <div class="header-navbar">
                <div class="site-logo">
                    <?php get_template_part( 'template-parts/header/logo', 1 ); ?>
                    <?php get_template_part( 'template-parts/header/logo', 2 ); ?>
                </div>
                <?php 
                    get_template_part( 'template-parts/header/menu', 1 );
                    get_template_part( 'template-parts/header/menu', 'elements' );
                ?>
                <?php get_template_part('template-parts/header/mobile', 'menu'); ?>
            </div>
        </div>
    </div>
</header>
