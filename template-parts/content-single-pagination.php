<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_previous = get_previous_post();
$listygo_next = get_next_post();
if ( $listygo_previous || $listygo_next ):
?>
<ul class="post-view">
    <?php if ( $listygo_previous ): ?>
    <li><a href="<?php echo esc_url( get_permalink( $listygo_previous ) ); ?>"><i class="flaticon-back"></i><?php esc_html_e( 'Previous Article', 'listygo' ); ?></a></li>
    <?php endif; if ( $listygo_next ): ?>
    <li><a href="<?php echo esc_url( get_permalink( $listygo_next ) ); ?>"><?php esc_html_e( 'Next Article', 'listygo' ); ?><i class="flaticon-right-arrow"></i></a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>