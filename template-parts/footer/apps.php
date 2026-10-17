<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

$listygo_title = RDTListygo::$options['apps_title'];
$listygo_desc = RDTListygo::$options['apps_desc'];
$listygo_play_store = RDTListygo::$options['play_store'];
$listygo_apps_store = RDTListygo::$options['apps_store'];

?>

<!--=====================================-->
<!--=           Apps Download           =-->
<!--=====================================-->
<section class="apps apps--layout2 primary-bg overflow-hidden">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6">
                <div class="apps-block">
                    <?php if(!empty( $listygo_title )){ ?>
                        <h2 class="apps-block__heading text-white"><?php echo esc_html( $listygo_title ); ?></h2>
                    <?php } if(!empty( $listygo_desc )){ ?>
                        <p class="apps-block__text text-white text-white"><?php echo esc_html( $listygo_desc ); ?></p>
                    <?php } if(!empty( $listygo_play_store || $listygo_play_store )){ ?>
                        <div class="appButtons">
                            <?php if(!empty( $listygo_play_store )){ ?>
                                <a href="<?php echo esc_url( $listygo_play_store ); ?>" class="appButtons__logo">
                                    <img src="<?php echo esc_url( Helper::get_img('theme/android.svg') ); ?>" alt="<?php esc_attr_e( 'Androaid Stor', 'listygo' ); ?>">
                                </a>
                            <?php } if(!empty( $listygo_apps_store )){ ?>
                                <a href="<?php echo esc_url( $listygo_apps_store ); ?>" class="appButtons__logo">
                                <img src="<?php echo esc_url( Helper::get_img('theme/apple.svg') ); ?>" alt="<?php esc_attr_e( 'Apple Stor', 'listygo' ); ?>">
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if(!empty( RDTListygo::$options['app_right_img'] )){ ?>
                <div class="col-lg-6">
                    <div class="apps-screen position-relative text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.6s">
                        <?php Helper::listygo_get_attach_img( RDTListygo::$options['app_right_img'], 'full' ); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!--=====================================-->
<!--=         Apps Download End         =-->
<!--=====================================-->    