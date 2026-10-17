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

$listygo_group_ids = isset( RDTListygo::$options['custom_fields_list_types'] ) ? RDTListygo::$options['custom_fields_list_types'] : [];

if ( ! is_array( $listygo_group_ids ) ) {
	$listygo_group_ids = explode(',', $listygo_group_ids);
}

$listygo_group_ids = array_filter( $listygo_group_ids );

if( empty( $listygo_group_ids ) ){
	return;
}

Listing_Functions::listygo_listing_details_cfg( $listygo_group_ids );