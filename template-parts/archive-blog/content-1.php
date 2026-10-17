<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_post_id = get_the_ID();
$listygo_excerpt_length = RDTListygo::$options['excerpt_length'];
$listygo_padmin = RDTListygo::$options['post_meta_admin'];
$listygo_pdate = RDTListygo::$options['post_meta_date'];
$listygo_pcom = RDTListygo::$options['post_meta_comnts'];
$listygo_pcats = RDTListygo::$options['post_meta_cats'];

if ( has_post_thumbnail() ){
    $listygo_thumb_img = '';
} else {
    $listygo_thumb_img = 'no-image';
}
global $post;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout1 blog-list '.$listygo_thumb_img ); ?>>
    <?php if ( has_post_thumbnail() ){ ?>
    <div class="post-thumb">
        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
        <?php if ($listygo_pcats) { ?>
            <div class="blog-block__tag">
                <?php the_category( ' ' ); ?>
            </div>
        <?php } ?>
    </div>
    <?php } ?>
    <div class="post-content">
        <?php echo wp_kses_post( Helper::listygo_get_post_meta( $listygo_post_id, $listygo_padmin, $listygo_pdate, $listygo_pcom, $listygo_pcats = NULL ) ); ?>
        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="content">
            <p><?php echo wp_kses_post( Helper::listygo_excerpt( $listygo_excerpt_length ) ); ?></p>
        </div>
        <div class="btn-wrap btn-v2">
            <a href="<?php the_permalink(); ?>" class="item-btn">
                <span class="btn__icon">
                    <?php Helper::escape_svg( Helper::btn_right_icon() ); ?>
                </span>
                <span class="btn__text"><?php esc_html_e( 'Read More', 'listygo' ); ?></span>
                <span class="btn__icon">
                    <?php Helper::escape_svg( Helper::btn_right_icon() ); ?>
                </span>
            </a>
        </div>
    </div>
    <?php if ( is_sticky() ) {
        echo '<sup class="meta-featured-post"> <i class="fas fa-thumbtack"></i> ' . esc_html__( 'Sticky', 'listygo' ) . ' </sup>';
    } ?>
</article>