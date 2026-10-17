<?php
/**
 * Single layout element: rtrb_booking_enabled
 *
 * This field is a form-builder toggle that controls whether booking is
 * enabled for a listing. It has no visible output on the single listing
 * view — the actual booking button is rendered by the `rtrb-booking`
 * element. Returning early here prevents the "does not exist" notice
 * caused by the classified-listing-store plugin appending a double .php
 * extension when searching for this template.
 */

defined( 'ABSPATH' ) || exit;
