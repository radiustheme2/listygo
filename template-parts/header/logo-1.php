<?php 
namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

// Logo
$listygo_light_logo = empty( Helper::rt_the_logo_dark() ) ? get_bloginfo( 'name' ) : Helper::rt_the_logo_dark();
?>
<div class="header-logo logo-black">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="main-logo">
		<?php echo wp_kses_post( $listygo_light_logo ); ?>
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