<?php
/**
 * @package Listygo/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Helper;

$listygo_style = RDTListygo::$options['listing_archive_style'];

Helper::get_custom_listing_template( 'list/list-1' );

?>