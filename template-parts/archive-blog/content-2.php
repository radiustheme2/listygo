<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_padmin = RDTListygo::$options['post_meta_admin'];
$listygo_pdate = RDTListygo::$options['post_meta_date'];
$listygo_pcats = RDTListygo::$options['post_meta_cats'];
$listygo_cols = RDTListygo::$options['blog_grid'];
if ( has_post_thumbnail() ){
    $listygo_thumb_img = '';
} else {
    $listygo_thumb_img = 'no-image';
}
if ($listygo_cols > 1) {
    $listygo_image_size = 'listygo-size-3';
} else {
    $listygo_image_size = 'full';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-box-layout2 blog-grid '.$listygo_thumb_img ); ?>>
    <div class="blog-block">
        <?php if ( has_post_thumbnail() ){ ?>
            <figure class="blog-block__figure">
                <a class="blog-block__link--image" href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail($listygo_image_size); ?>
                </a>
            </figure>
        <?php } ?>
        <div class="blog-block__content">
            <?php if ($listygo_pcats) { ?>
            <div class="blog-block__tag">
                <?php the_category( ' ' ); ?>
            </div>
            <?php } ?>
            <h2 class="blog-block__heading text-white bold-underline">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <?php if ($listygo_padmin || $listygo_pdate) { ?>
            <div class="blog-block__meta">
                <ul>
                    <?php if ($listygo_padmin) { ?>
                    <li>
                        <?php echo wp_kses_post( Helper::user_icon() ); ?>
                        <span>
                            <span><?php esc_html_e( 'by', 'listygo' ); ?></span>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
                        </span>
                    </li>
                    <?php } if ( $listygo_pdate ) { ?>
                    <li>
                        <?php echo wp_kses_post( Helper::calendar_icon() ); ?>
                        <span><?php the_time( get_option( 'date_format' ) ); ?></span>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
    </div>
</article>