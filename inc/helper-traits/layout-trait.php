<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;
use radiustheme\listygo\RDTListygo;

trait LayoutTrait {
  public static function has_sidebar() {
    return ( self::has_full_width() ) ? false : true;
  }

  /**
   * It will determine whether content will take full width or not 
   * this is determine by 2 parameters - redux theme option and active sidebar
   * @return boolean [description]
   */
  public static function has_full_width() {
    $theme_option_full_width = ( RDTListygo::$layout == 'full-width' ) ? true : false;
    if ( is_singular( 'listygo_service' ) && ! is_active_sidebar('service-widgets')) {
      $not_active_sidebar = ! is_active_sidebar('service-widgets') ;
    } else {  
      $not_active_sidebar = ! is_active_sidebar('sidebar') ;
    }
    $bool = $theme_option_full_width || $not_active_sidebar;
    return  $bool;
  }

  public static function the_layout_class() {
    $layout_class = self::has_sidebar() ? 'col-xl-8' : 'col-12';
    if ( RDTListygo::$layout == 'right-sidebar' ) {
      $layout_class = $layout_class.' order-1';
    } elseif ( RDTListygo::$layout == 'left-sidebar' ) {
      $layout_class = $layout_class.' order-2';
    }
	$filtered_class = apply_filters( 'listygo_layout_class', $layout_class );
    echo esc_attr( $filtered_class );
  }

  public static function the_sidebar_class() {
    if ( class_exists( 'WooCommerce' ) ) {
      if ( is_shop() || is_product() ) {
        if ( RDTListygo::$layout == 'right-sidebar' ) {
          echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-3 order-2' )); // phpcs:ignore
        } else {
          echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-3 order-1' ));// phpcs:ignore
        }
      } else {
        if ( RDTListygo::$layout == 'right-sidebar' ) {
          echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-4 order-2' ));// phpcs:ignore
        } else {
          echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-4 order-1' ));// phpcs:ignore
        }
      }
    } else {
      if ( RDTListygo::$layout == 'right-sidebar' ) {
        echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-4 order-2' )); // phpcs:ignore
      } else {
        echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-4 order-1' )); // phpcs:ignore
      }
    }
  }

  public static function listygo_sidebar() {
    if ( RDTListygo::$layout == 'right-sidebar' || RDTListygo::$layout == 'left-sidebar' && ! self::has_full_width() ) {
      get_sidebar();
    }
  }

  public static function has_footer(){
    if ( !RDTListygo::$options['footer_area'] ) {
      return false;
    }
    $footer_column = RDTListygo::$options['footer_column'];
    for ( $i = 1; $i <= $footer_column; $i++ ) {
      if ( is_active_sidebar( 'footer-'. $i ) ) {
        return true;
      }
    }
    return false;
  }
  
  public static function has_footer_widget(){
    return is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4') ;
  }

  public static function listing_archive_lay_cols(){
    return is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4') ;
  }

  // Listing Layout
  public static function listing_layout_class() {  
    $bodyLayout = 'container';
    if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
      if (is_post_type_archive( rtcl()->post_type ) || is_author() || is_tax('rtcl_category')) {
        $bodyLayout = RDTListygo::$options['listing_archive_box_layout'] ? RDTListygo::$options['listing_archive_box_layout'] : 'container';
      }
    }
    $ccols = $bodyLayout != 'container' ? '10' : '9';
    $listing_layout_class = is_active_sidebar('listing-archive-sidebar') && RDTListygo::$options['listing_layout'] != 'full-width' ? 'col-xl-'.$ccols : 'col-12';
    if ( RDTListygo::$options['listing_layout'] == 'right-sidebar' ) {
      $listing_layout_class = $listing_layout_class.' order-1';
    } elseif ( RDTListygo::$options['listing_layout'] == 'left-sidebar' ) {
      $listing_layout_class = $listing_layout_class.' order-2';
    }
    echo esc_attr( apply_filters( 'listing_layout_class', $listing_layout_class ) ); // phpcs:ignore
  }

  public static function listing_sidebar() {
    if ( RDTListygo::$options['listing_layout'] == 'right-sidebar' || RDTListygo::$options['listing_layout'] == 'left-sidebar' && RDTListygo::$options['listing_layout'] != 'full-width' && is_active_sidebar('listing-archive-sidebar') ) {
      get_sidebar( 'listing' );
    }
  }
  public static function listing_sidebar_class() {
    $bodyLayout = 'container';
    if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
      if (is_post_type_archive( rtcl()->post_type ) || is_author() || is_tax('rtcl_category')) {
        $bodyLayout = RDTListygo::$options['listing_archive_box_layout'] ? RDTListygo::$options['listing_archive_box_layout'] : 'container';
      }
    }
    $scols = $bodyLayout != 'container' ? '2' : '3';
    if ( RDTListygo::$options['listing_layout'] == 'right-sidebar' ) {
      echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-'.$scols.' order-2 listing-sidebar-right' )); // phpcs:ignore
    } else {
      echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-xl-'.$scols.' order-1 listing-sidebar-left' )); // phpcs:ignore
    }
  }

}
