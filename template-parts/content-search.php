<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( ! defined( 'ABSPATH' ) ) exit;

$listygo_blog_style = RDTListygo::$options['blog_style'];

get_template_part( 'template-parts/archive-blog/content',  $listygo_blog_style );

?>
