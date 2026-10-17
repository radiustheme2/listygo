<?php 
namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

// Logo
$listygo_sticky_logo = empty( Helper::rt_the_logo_dark() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_dark();

?>
<div class="header-logo logo-white sticky-logo">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-light">
		<?php if ( !empty( Helper::rt_the_logo_light() ) ){
			echo wp_kses_post( Helper::rt_the_logo_light() );
		} else {
			echo wp_kses_post( $listygo_sticky_logo );
		} ?>
	</a>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-dark">
		<?php if ( !empty( Helper::rt_the_logo_dark() ) ){
			echo wp_kses_post( Helper::rt_the_logo_dark() );
		} else {
			echo wp_kses_post( $listygo_sticky_logo );
		} ?>
	</a>
	<?php 
		if ( display_header_text() == true ) {
			$listygo_description = get_bloginfo( 'description', 'display' );
		if ( $listygo_description || is_customize_preview() ) : ?>
			<div class="site-description"><?php echo esc_html( $listygo_description ); ?></div>
		<?php endif; 
		}
	?>
</div>