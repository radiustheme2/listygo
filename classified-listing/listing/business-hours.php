<?php
/**
 * Business Hours
 *
 * @author     RadiusTheme
 * @package    classified-listing/templates
 * @version    1.0.0
 *
 * @var array $business_hours   business hours
 * @var int   $current_week_day Day of the week (0 on Sunday)
 * @var array $options
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Controllers\BusinessHoursController as BHS;
use Rtcl\Helpers\Utility;
use Rtcl\Resources\Options;

global $wp_locale;

echo '<div class="rtclbh-block">';

echo '<div class="rtcl-listing-side-title"><h3>' . esc_html__( 'Open Hours', 'listygo' ) . '</h3></div>';
?>

    <table class="rtclbh">
		<?php if ( ! empty( $options['footer'] ) ) : ?>
            <tfoot>
            <tr>
                <th>&nbsp;</th>
                <th>
					<?php
					$listygo_open_text  = ! empty( $options['open_text'] ) ? $options['open_text'] : __( 'Open', 'listygo' );
					echo esc_html( $listygo_open_text );
					?>
                </th>
                <th class="rtclbh-separator">&nbsp;</th>
                <th>
					<?php
					$listygo_close_text = ! empty( $options['close_text'] ) ? $options['close_text'] : __( 'Close', 'listygo' );
					echo esc_html( $listygo_close_text );
					?>
                </th>
            </tr>
            </tfoot>
		<?php endif; ?>
        <tbody>
		<?php
		foreach ( Options::get_week_days() as $dayKey => $listygo_day ) { //phpcs:ignore

			// Display the day as either its initial or abbreviation.
			switch ( $options['day_name'] ?? '' ) {
				case 'initial':
					$listygo_day = $wp_locale->get_weekday_initial( $listygo_day );
					break;
				case 'abbrev':
					$listygo_day = $wp_locale->get_weekday_abbrev( $listygo_day );
					break;
			}

			$listygo_dayData = ! empty( $business_hours[ $dayKey ] ) ? $business_hours[ $dayKey ] : '';

			// Show the "Closed" message if there are no open and close hours recorded for the day.
			if ( ! BHS::openToday( $listygo_dayData ) ) {
				if ( ! empty( $options['show_closed_day'] ) ) {
					$closed_text = $current_week_day === $dayKey //phpcs:ignore
						? ( ! empty( $options['closed_today_text'] ) ? $options['closed_today_text'] : __( 'Closed Today', 'listygo' ) )
						: ( ! empty( $options['closed_24_text'] ) ? $options['closed_24_text'] : __( 'Closed', 'listygo' ) );

					printf(
						'<tr class="rtclbh-closed %1$s"><th>%2$s</th><td class="rtclbh-info" colspan="3">%3$s</td></tr>',
						esc_attr( sprintf( 'rtclbh-day-%d%s', absint( $dayKey ), $current_week_day === $dayKey ? ' rtclbh-active' : '' ) ),
						esc_html( $listygo_day ),
						esc_html( $closed_text )
					);
				}
				continue;
			}

			// Show 24 hours open
			if ( BHS::isOpenAllDayLong( $listygo_dayData ) ) {
				$open_24_text = $current_week_day === $dayKey //phpcs:ignore
					? ( ! empty( $options['open_today_text'] ) ? $options['open_today_text'] : __( 'Open Today (24 Hours)', 'listygo' ) )
					: ( ! empty( $options['open_24_text'] ) ? $options['open_24_text'] : __( 'Open (24 Hours)', 'listygo' ) );

				printf(
					'<tr class="rtclbh-opened %1$s"><th>%2$s</th><td class="rtclbh-info" colspan="3">%3$s</td></tr>',
					esc_attr( sprintf( 'rtclbh-day-%d%s', absint( $dayKey ), $current_week_day === $dayKey ? ' rtclbh-active' : '' ) ),
					esc_html( $listygo_day ),
					esc_html( $open_24_text )
				);
				continue;
			}

			$timePeriods = $listygo_dayData['times'] ?? []; //phpcs:ignore
			foreach ( $timePeriods as $periodIndex => $timePeriod ) { //phpcs:ignore

				if ( BHS::openPeriod( $timePeriod ) ) {
					printf(
						'<tr class="rtclbh-period %1$s" %2$s><th>%3$s</th><td class="rtclbh-open">%4$s</td><td class="rtclbh-separator">%5$s</td><td class="rtclbh-close">%6$s</td></tr>',
						esc_attr( sprintf(
							'rtclbh-day-%d%s%s',
							absint( $dayKey ),
							$current_week_day === $dayKey ? ' rtclbh-active' : '',
							( ! empty( $options['highlight_open_period'] ) && $current_week_day === $dayKey && BHS::isOpen( $timePeriod['start'], $timePeriod['end'] ) ) ? ' rtclbh-opened' : ''
						) ),
						'data-count="' . absint( $periodIndex ) . '"',
						$periodIndex === 0 ? esc_attr( $listygo_day ) : '&nbsp;',
						esc_html( Utility::formatTime( $timePeriod['start'], null, 'H:i' ) ),
						esc_html( $options['open_close_separator'] ?? '-' ),
						esc_html( Utility::formatTime( $timePeriod['end'], null, 'H:i' ) )
					);
				}
			}
		}
		?>
        </tbody>
    </table>

<?php
// Open/closed status
if ( ! empty( $options['show_open_status'] ) ) {
	if ( BHS::openStatus( $business_hours ) ) {
		$listygo_open_status_text = ! empty( $options['open_status_text'] ) ? $options['open_status_text'] : __( 'We are currently open.', 'listygo' );
		printf( '<div class="rtclbh-status rtclbh-status-open">%s</div>', esc_html( $listygo_open_status_text ) );
	} else {
		$listygo_close_status_text = ! empty( $options['close_status_text'] ) ? $options['close_status_text'] : __( 'Sorry, we are currently closed.', 'listygo' );
		printf( '<div class="rtclbh-status rtclbh-status-closed">%s</div>', esc_html( $listygo_close_status_text ) );
	}
}
echo '</div>';

