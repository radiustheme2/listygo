<?php
/**
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 * @var array[] $images
 * @var array[] $videos
 * @var string  $video_url
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;

$listygo_detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

$listygo_total_gallery_image  = count($images);
$listygo_total_gallery_videos = count($videos);
$listygo_total_gallery_item   = $listygo_total_gallery_image + $listygo_total_gallery_videos;
$listygo_isSliderEnable       = Functions::is_gallery_slider_enabled();

if ($listygo_total_gallery_item) :
	?>
	<div id="rtcl-slider-wrapper" class="rtcl-slider-wrapper" data-options="">
		<!-- Slider -->
		<div class="rtcl-slider<?php echo esc_attr($listygo_isSliderEnable ? '' : ' off') ?>">
			<div class="swiper-wrapper">
				<?php
				if ( !in_array('video_url', $listygo_detailOption) ){
					if ($listygo_total_gallery_videos) {
						foreach ($videos as $index => $video_url) { //phpcs:ignore ?>
							<div class="swiper-slide rtcl-slider-item rtcl-slider-video-item ratio-16x9">
								<iframe class="rtcl-lightbox-iframe"
										src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ); ?>"
										frameborder="0" webkitAllowFullScreen
										mozallowfullscreen allowFullScreen></iframe>
							</div>
							<?php
						}
					}
				}
				if ($listygo_total_gallery_image) {
					foreach ($images as $index => $image) : //phpcs:ignore
						$listygo_image_attributes = wp_get_attachment_image_src($image->ID, 'rtcl-gallery');
						$image_full = wp_get_attachment_image_src($image->ID, 'full'); //phpcs:ignore ?>
						<div class="swiper-slide rtcl-slider-item">
							<img src="<?php echo esc_html($listygo_image_attributes[0]); ?>"
								data-src="<?php echo esc_attr($listygo_image_attributes[0]) ?>"
								data-large_image="<?php echo esc_attr($listygo_image_attributes[0]) ?>"
								data-large_image_width="<?php echo esc_attr($listygo_image_attributes[1]) ?>"
								data-large_image_height="<?php echo esc_attr($listygo_image_attributes[2]) ?>"
								alt="<?php echo esc_attr( get_the_title( $image->ID ) ); ?>"
								data-caption="<?php echo esc_attr(wp_get_attachment_caption($image->ID)); ?>"
								class="rtcl-responsive-img"/>
						</div>
					<?php endforeach;
				} ?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
		<?php if ($listygo_isSliderEnable && $listygo_total_gallery_item > 1): ?>
			<!-- Slider nav -->
			<div class="rtcl-slider-nav">
				<div class="swiper-wrapper">
					<?php
					if ( !in_array('video_url', $listygo_detailOption) ){

						if ($listygo_total_gallery_videos) {
							foreach ($videos as $index => $video_url) { //phpcs:ignore ?>
								<div class="swiper-slide rtcl-slider-thumb-item rtcl-slider-video-thumb">
									<img src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_url ) ); ?>"
										class="rtcl-gallery-thumbnail" alt=""/>
								</div>
								<?php
							}
						}
					}
					if ( $listygo_total_gallery_image ) {
						foreach ( $images as $index => $image ) : //phpcs:ignore ?>
							<div class="swiper-slide rtcl-slider-thumb-item">
								<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery-thumbnail' ); ?>
							</div>
						<?php endforeach;
					} ?>
				</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
			</div>
		<?php endif; ?>
	</div>
<?php endif;
