<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use radiustheme\listygo\RDTListygo;
use radiustheme\listygo\Listing_Functions;

global $listing;

$listygo_group_ids = isset( RDTListygo::$options['custom_fields_group_types'] ) ? RDTListygo::$options['custom_fields_group_types'] : [];

if( empty( $listygo_group_ids ) ){
	return;
}

$listygo_group_ids = array_filter( $listygo_group_ids );

Listing_Functions::listygo_listing_details_cfg(  $listygo_group_ids );