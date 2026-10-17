<?php
/**
 * Result Count
 *
 * @var int $total
 * @var int $per_page
 * @var int $current
 * @var array $options
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="rtcl-result-count">
    <h2>
		<?php
		if ( 1 === $total ) {
			esc_html_e( 'Showing the single result', 'listygo' );
		} elseif ( $total <= $per_page || -1 === $per_page ) {
			printf(
			/* translators: %d: Total number of results */
				esc_html( _n( 'Showing all %d result', 'Showing all %d results', $total, 'listygo' ) ),
				intval( $total )
			);
		} else {
			$listygo_first = ( $per_page * $current ) - $per_page + 1;
			$listygo_last  = min( $total, $per_page * $current );
			printf(
			/* translators: 1: first result 2: last result 3: total results */
				esc_html( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'listygo' ) ),
				intval( $listygo_first ),
				intval( $listygo_last ),
				intval( $total )
			);
		}
		?>
    </h2>
</div>
