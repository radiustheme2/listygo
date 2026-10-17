<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_header_login = RDTListygo::$options['header_login'] ? true : false;
$listygo_header_listing = RDTListygo::$options['header_listing'] ? true : false;

if ( $listygo_header_login == true && $listygo_header_listing == true ) {
    $listygo_align_class = 'justify-content-center';
} else {
    $listygo_align_class = 'justify-content-end';
}

?>
<nav class="site-nav <?php echo esc_attr( $listygo_align_class ); ?>">
    <?php if ( has_nav_menu( 'primary' ) ) {
	    wp_nav_menu( [
		    'theme_location' => 'primary',
		    'container'      => 'ul',
		    'menu_class'     => 'main-menu',
	    ]);
    } else {
        if ( is_user_logged_in() ) {
            echo '<ul id="menu" class="menu fallbackcd-menu-item"><li class="menu-item"><a class="fallbackcd" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'listygo' ) . '</a></li></ul>';
        }
    } ?>
</nav>	