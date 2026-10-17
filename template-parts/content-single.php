<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

$post_id = get_the_ID();
$padmin = RDTListygo::$options['post_admin'];
$pdate = RDTListygo::$options['post_date'];
$pcom = RDTListygo::$options['post_comnts'];
$pcats = RDTListygo::$options['post_cats'];

if ( RDTListygo::$options['post_tags'] && has_tag() || RDTListygo::$options['post_share'] == "1" ){
    $tags_shares = '';
} else {
    $tags_shares = 'tags-shares-none';
}

if ( RDTListygo::$options['post_tags'] && has_tag() ) {
    $scols = '6';
    $tc = '';
} else {
    $scols = '12';
    $tc = 'none-tag';
}

if ( $share = RDTListygo::$options['post_share'] == 1 ) {
    $tcols = '6';
} else {
    $tcols = '12';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-blog-wrap' ); ?>>
    <?php if ( has_post_thumbnail() ){ ?> 
        <div class="single-blog-thumb"><?php the_post_thumbnail(); ?></div>
    <?php } ?>
    <div class="single-content-wrap">
        <div class="single-blog-entry">
            <?php echo wp_kses_post( Helper::listygo_get_post_meta( $post_id, $padmin, $pdate, $pcom, $pcats ) ); ?>
        </div>
        <div class="single-blog-details">
            <?php the_content(); ?>
            <?php wp_link_pages( array(
                'before'      => '<div class="listygo-page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'listygo' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                ) );
            ?>
        </div>
        <?php if ( RDTListygo::$options['post_tags'] && has_tag() || RDTListygo::$options['post_share'] == "1" ){ ?>
        <div class="single-blog-footer <?php echo esc_attr( $tags_shares.' '.$tc ); ?>">
            <div class="row align-items-center">
                <?php if ( RDTListygo::$options['post_tags'] && has_tag() ): ?>
                <div class="col-md-<?php echo esc_attr( $tcols ); ?>">
                    <div class="blog-tags">
                        <?php echo get_the_term_list( $post->ID, 'post_tag','',' ' ); ?>
                    </div>
                </div>
                <?php endif; if ( RDTListygo::$options['post_share'] == "1"  ): ?>
                <div class="col-md-<?php echo esc_attr( $scols ); ?>">
                    <?php Helper::render(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>  
        <?php } ?>      
    </div>
    <?php
        if ( RDTListygo::$options['post_navigation'] ) {
            get_template_part( 'template-parts/content-single-pagination' );
        }
    ?>
    <?php if (get_the_author_meta('description')) : ?>      
        <div class="blog-author">
            <h3 class="inner-item-heading"><?php esc_html_e( 'About Author', 'listygo' ); ?></h3>
            <div class="media">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 90 ); ?>
                <div class="media-body">
                    <div class="author-head">
                        <h4 class="author-title">
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></a>
                        </h4>
                        <?php echo wp_kses_post( Helper::UserMeta() ); ?>
                    </div>
                    <p class="author-bio"><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ( comments_open() || get_comments_number() ){ ?>
    <div class="blog-comment-form">
        <?php comments_template(); ?>  
    </div>
    <?php } ?>
</article>