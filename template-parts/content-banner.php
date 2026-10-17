<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;
$listygo_prefix = LISTYGO_THEME_PREFIX_VAR;

if ( is_404() ) {
	$listygo_title = RDTListygo::$options['error_page_title'];
	$listygo_title = $listygo_title . get_search_query();
}
elseif ( is_search() ) {
	$listygo_title = esc_html__( 'Search Results for : ', 'listygo' ) . get_search_query();
}
elseif ( is_home() ) {
	if (!empty(RDTListygo::$options['blog_breadcrumb_title'])) {
		$listygo_title = RDTListygo::$options['blog_breadcrumb_title'];
	} elseif ( get_option( 'page_for_posts' ) ) {
		$listygo_title = get_the_title( get_option( 'page_for_posts' ) );
	}
	else {
		$listygo_title = apply_filters( "{$listygo_prefix}_blog_title", esc_html__( 'All Posts', 'listygo' ) ); // phpcs:ignore
	}
}
elseif ( is_archive() ) {
	if ( is_post_type_archive( "rtcl_listing" ) ) {
		$listygo_title = RDTListygo::$options['listing_archive_title'];
	} else {
		$listygo_title = get_the_archive_title();
	}
} elseif (is_singular( 'rtcl_listing' )) {
	$listygo_title  = esc_html__( 'Listing Details', 'listygo' );
} elseif (is_single()) {
	$listygo_title  = get_the_title();
} else {
	$listygo_id                        = $post->ID;
	$listygo_custom_page_title = get_post_meta( $listygo_id, 'listygo_custom_page_title', true );
	if (!empty($listygo_custom_page_title)) {
		$listygo_title = get_post_meta( $listygo_id, 'listygo_custom_page_title', true );
	} else { 
		$listygo_title = get_the_title();
	}
}

?>

<section class="breadcrumbs-banner">
   <div class="container">
      <div class="breadcrumbs-area">
        <h1 class="heading-title"><?php echo wp_kses( $listygo_title, 'alltext_allow' ); ?></h1>
		<?php
			if ( RDTListygo::$has_breadcrumb == '1' || RDTListygo::$has_breadcrumb === "on" ) {
				get_template_part( 'template-parts/content', 'breadcrumb' ); 
			}
		?>
      </div>
   </div>
</section>

<!--=====================================-->
<!--=         Inner Banner Start    	=-->
<!--=====================================-->