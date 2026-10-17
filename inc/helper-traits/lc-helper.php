<?php
/**
 * Check Radius Theme License
 *
 * @since 1.0.0
 */

namespace RTLC;

if ( defined( 'RT_DEBUG' ) && RT_DEBUG ) {
    return;
}

/**
 * Radius Theme License
 */
class Helper {
    /**
     * Holds the values to be used in the fields callbacks
     *
     * @var array
     */
    private $options;

    /**
     * License URL
     *
     * @var string
     */
    private $license_url = 'https://envato.radiustheme.com/license-check';

    /**
     * Theme Name
     *
     * @var string
     */
    private $theme_name = '';

    /**
     * Theme Slug
     *
     * @var string
     */
    private $theme_slug = '';

    /**
     * License URL (v2 REST API endpoint)
     *
     * @var string
     */
    private $license_url_v2 = 'https://envato.radiustheme.com/wp-json/radiustheme/v2/check-license';

    /**
     * API key for the v2 endpoint.
     * Empty string means v2 is disabled and v1 is used instead.
     *
     * @var string
     */
    private $license_api_key = 'b206784d-5b8f-46df-85b2-e0d2003aa240';

    /**
     * Class Constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'theme_menu' ] );
        add_action( 'admin_init', [ $this, 'theme_option' ] );
        add_action( 'wp_ajax_rtlc_verification', [ $this, 'rtlc_verification' ] );
        add_action( 'admin_notices', [ $this, 'envato_migration_notice' ] );

        $theme_info = wp_get_theme();
        $theme_info = ( $theme_info->parent() ) ? $theme_info->parent() : $theme_info;
        $theme_name = $theme_info->get( 'Name' );

        // Theme name.
        $this->theme_name = $theme_name;

        // Theme slug.
        $this->theme_slug = strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '-', $theme_name ) ) );
    }

    /**
     * Add options page
     */
    public function theme_menu() {
        add_theme_page( esc_html__( 'Theme License', 'listygo' ), esc_html__( 'Theme License', 'listygo' ), 'manage_options', 'rtlc', [ $this, 'create_admin_page' ], null, 99 );
    }

    /**
     * Options page callback
     *
     * @return void
     */
    public function create_admin_page() {
        $this->options = get_option( 'rt_licenses' );

        $theme_info    = wp_get_theme();
        $theme_info    = ( $theme_info->parent() ) ? $theme_info->parent() : $theme_info;
        $theme_name    = $theme_info->get( 'Name' );
        $theme_version = $theme_info->get( 'Version' );

        $is_valid    = rtlc_is_valid();
        $activated   = ! empty( $is_valid['success'] ); // Both systems — used for header pill only.
        $domain_name = rtlc_get_domain_name();

        // ThemeForest-only validity — used exclusively inside the ThemeForest tab.
        $tf_result          = rtlc_is_valid_envato();
        $tf_valid           = ! empty( $tf_result['success'] );
        $tf_domain_mismatch = ! $tf_valid && isset( $tf_result['domain_match'] ) && ! $tf_result['domain_match'];
        $support_url        = 'https://www.radiustheme.com/contact/';

        // Get purchase code value.
        $purchase_code = '';
        if ( isset( $this->options[ $this->theme_slug . '_license_key' ] ) ) {
            $purchase_code = $this->options[ $this->theme_slug . '_license_key' ];
        } elseif ( isset( $this->options[ $this->theme_slug . '_license' ] ) && isset( $this->options[ $this->theme_slug . '_license' ]['key'] ) ) {
            $purchase_code = $this->options[ $this->theme_slug . '_license' ]['key'];
        }
        ?>
        <div class="wrap">
            <!-- Page Header -->
            <div class="rtlc-page-header">
                <h1><?php esc_html_e( 'Theme License', 'listygo' ); ?></h1>
                <div class="rtlc-activation-status <?php echo $activated ? 'rtlc-status-active' : ''; ?>">
                    <span class="rtlc-status-dot"></span>
                    <?php echo $activated ? esc_html__( 'Activated', 'listygo' ) : esc_html__( 'Not activated', 'listygo' ); ?>
                </div>
            </div>

            <?php settings_errors(); ?>

            <!-- Subtitle Banner -->
            <?php if ( ! $activated ) { ?>
                <div class="rtlc-subtitle">
                    <span class="dashicons dashicons-info-outline"></span>
                    <span>
					<?php
                    printf(
                    /* translators: %s: theme name */
                            __( 'Activate %s to unlock one-click demo import, automatic updates, and premium support. Pick where you bought the theme — you only need to activate one.', 'listygo' ),
                            '<strong>' . esc_html( $theme_name ) . '</strong>',
                    );
                    ?>
				</span>
                </div>
            <?php } ?>

            <!-- Two Column Layout -->
            <div class="rtlc-page-layout">
                <!-- Main Content -->
                <div class="rtlc-main">
                    <div class="rtlc-license-wrap">
                        <!-- Theme Info -->
                        <div class="rtlc-theme-info">
                            <div class="rtlc-theme-icon">
                                <?php echo esc_html( mb_substr( $theme_name, 0, 1 ) ); ?>
                            </div>
                            <div>
                                <div class="rtlc-theme-name">
                                    <?php echo esc_html( $theme_name ); ?>
                                    <span class="rtlc-version">v<?php echo esc_html( $theme_version ); ?></span>
                                </div>
                                <div class="rtlc-theme-desc"><?php esc_html_e( 'Classified Ads WordPress Theme', 'listygo' ); ?> &middot; <?php esc_html_e( 'by RadiusTheme', 'listygo' ); ?></div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="rtlc-divider-label">
                            <?php
                            printf(
                            /* translators: %s: theme name in uppercase */
                                    esc_html__( 'WHERE DID YOU BUY %s?', 'listygo' ),
                                    esc_html( strtoupper( $theme_name ) ),
                            );
                            ?>
                        </div>

                        <!-- Tab Toggles -->
                        <div class="rtlc-tab-toggle">
                            <a href="#" class="rtlc-tab rtlc-tab-active" data-tab="themeforest">
                                <div class="rtlc-tab-header">
									<span class="rtlc-tab-logo">
                                        <svg class="rtlc-tab-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="18" height="18" rx="4" fill="#87E64B"/>
                                            <path d="M9.58753 15.3928C9.90334 15.3928 10.1594 15.1367 10.1594 14.8209C10.1594 14.5051 9.90334 14.249 9.58753 14.249C9.27167 14.249 9.01562 14.5051 9.01562 14.8209C9.01562 15.1367 9.27167 15.3928 9.58753 15.3928Z" fill="#1B4201"/>
                                            <path d="M12.8735 10.9267L9.65146 11.2718C9.59252 11.2783 9.56206 11.2029 9.60901 11.1664L12.7621 8.71156C12.9669 8.54424 13.0973 8.28351 13.0413 8.00431C12.9854 7.57627 12.6318 7.29706 12.1853 7.353L8.7594 7.85447C8.69897 7.86346 8.6665 7.78604 8.71495 7.74908L12.1108 5.15634C12.7811 4.6354 12.8366 3.61149 12.2227 3.01612C11.6643 2.45772 10.7708 2.4762 10.2124 3.0346L4.74019 8.59968C4.5354 8.82294 4.4425 9.12062 4.49844 9.43728C4.59135 9.93975 5.09381 10.2749 5.59677 10.182L8.54663 9.58013C8.61056 9.56715 8.64552 9.65256 8.59008 9.68752L5.31707 11.7823C4.90751 12.043 4.72171 12.508 4.85157 12.9735C4.98193 13.5879 5.59627 13.9415 6.19164 13.7926L11.0839 12.5874C11.1389 12.5739 11.1793 12.6379 11.1439 12.6818L10.3797 13.6248C10.1749 13.8855 10.51 14.2392 10.7892 14.0344L13.3021 11.9686C13.7486 11.5965 13.4509 10.8703 12.874 10.9262L12.8735 10.9267Z"
                                                  fill="#1B4201"/>
                                        </svg>
									</span>
                                    <span class="rtlc-tab-tick">&#10003;</span>
                                </div>
                                <div class="rtlc-tab-body">
                                    <span class="rtlc-tab-name"><?php esc_html_e( 'ThemeForest', 'listygo' ); ?></span>
                                    <span class="rtlc-tab-sub"><?php esc_html_e( 'Envato Marketplace', 'listygo' ); ?></span>
                                </div>
                            </a>
                            <a href="#" class="rtlc-tab" data-tab="radiustheme">
                                <div class="rtlc-tab-header">
									<span class="rtlc-tab-logo">
										<svg class="rtlc-tab-icon" width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.0596 11.6621C13.0673 10.4712 14.2582 9.46351 15.6323 8.73064C18.289 7.2649 21.2204 6.44042 24.3351 6.1656C26.6253 5.89077 29.282 5.70755 31.389 6.71525C32.4883 7.2649 33.3128 8.08938 33.954 9.09707C34.2289 9.55512 35.5114 12.2118 33.954 11.2957C27.633 7.81455 21.1288 7.63133 14.4414 10.3796C13.6169 10.746 12.884 11.2041 12.0596 11.6621Z" fill="#2059F9"/><path
                                                    d="M29.5563 4.78992C29.4647 4.78992 29.2815 4.78992 29.1899 4.78992C27.5409 4.69831 25.892 4.78992 24.3346 4.78992C21.8612 5.06474 19.3877 5.61439 17.0975 6.62209C15.4486 7.26335 13.9828 8.08783 12.5171 9.00392C13.9828 6.98852 15.815 5.24796 18.0136 4.14866C20.7619 2.59131 27.999 0.117875 29.7395 4.33187C29.7395 4.33187 29.7395 4.51509 29.7395 4.6067C29.7395 4.6067 29.7395 4.78992 29.5563 4.78992Z" fill="#2059F9"/><path d="M11.6932 7.62642C12.1512 6.25229 12.8841 4.96977 13.7086 3.87047C14.7163 2.22151 16.2736 0.938989 18.0142 0.206119C18.9303 -0.0687065 19.938 -0.0687065 20.854 0.206119C21.4037 0.389337 23.0526 0.938989 21.6785 1.30542C18.8386 2.03829 16.182 3.41242 13.8918 5.3362C13.0673 6.06907 12.3344 6.80194 11.6016 7.62642H11.6932Z"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            fill="#2059F9"/><path
                                                    d="M24.5181 12.026C25.709 12.9421 26.7167 14.2246 27.4495 15.5071C28.9153 18.1638 29.8314 21.0953 30.1062 24.1183C30.381 26.4086 30.6558 29.0652 29.6482 31.1722C29.0985 32.2715 28.3656 33.096 27.3579 33.7372C26.8999 34.0121 24.2432 35.3862 25.1593 33.7372C28.6405 27.4162 28.6405 20.912 25.8922 14.2246C25.5258 13.4001 25.0677 12.6673 24.6097 11.8428L24.5181 12.026Z" fill="#FCB615"/><path
                                                    d="M31.9383 29.615C31.9383 29.615 31.755 29.615 31.6634 29.615C31.6634 29.615 31.4802 29.615 31.4802 29.4318C31.4802 29.3402 31.4802 29.157 31.4802 29.0654C31.4802 27.4164 31.4802 25.7674 31.297 24.2101C31.1138 21.7367 30.4725 19.2632 29.4648 16.973C28.8236 15.4157 27.9991 13.8583 27.083 12.3926C29.19 13.7667 30.839 15.6905 32.0299 17.8891C33.5872 20.5457 36.1523 27.7828 31.9383 29.615Z" fill="#FCB615"/><path d="M28.5486 11.5684C29.9228 12.0264 31.2053 12.7593 32.3046 13.5838C33.9535 14.5914 35.2361 16.1488 35.9689 17.8894C36.2438 18.8054 36.2438 19.8131 35.9689 20.7292C35.7857 21.2789 35.2361 22.9278 34.8696 21.5537C34.1368 18.7138 32.671 16.0572 30.7472 13.8586C30.106 13.0341 29.2815 12.3012 28.457 11.5684H28.5486Z" fill="#FCB615"/><path
                                                    d="M24.244 24.3936C23.3279 25.5845 22.137 26.5922 20.7628 27.325C18.1062 28.7908 15.1747 29.7069 12.1516 30.0733C9.86141 30.3481 7.20476 30.6229 5.09776 29.6152C3.99845 29.1572 3.08237 28.3327 2.53272 27.325C2.25789 26.867 0.883759 24.2103 2.53272 25.1264C8.94532 28.6076 15.4495 28.5159 21.9538 25.7677C22.7782 25.4012 23.5111 24.9432 24.3356 24.4852L24.244 24.3936Z" fill="#8BC53F"/><path
                                                    d="M23.8762 27.0469C22.5021 29.0623 20.5783 30.8028 18.3797 31.9937C15.6315 33.5511 8.39437 36.1161 6.65381 31.9937C6.65381 31.9937 6.65381 31.8105 6.65381 31.7189C6.65381 31.6273 6.65381 31.5357 6.83703 31.5357C6.92863 31.5357 7.11185 31.5357 7.20346 31.5357C8.85242 31.5357 10.5014 31.5357 12.0587 31.4441C14.5322 31.1693 17.0056 30.528 19.2958 29.5203C20.8532 28.879 22.4105 27.963 23.7846 27.0469H23.8762Z" fill="#8BC53F"/><path d="M24.7012 28.4238C24.2432 29.798 23.6019 31.0805 22.6858 32.2714C21.6781 33.9203 20.2124 35.2029 18.3802 35.9357C17.4641 36.2106 16.4564 36.2106 15.5403 35.9357C14.9907 35.7525 13.3417 35.2029 14.7159 34.8364C17.5557 34.1036 20.2124 32.6378 22.411 30.714C23.2355 30.0728 23.9683 29.2483 24.7012 28.4238Z"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     fill="#8BC53F"/><path
                                                    d="M11.8764 24.2093C10.6855 23.2932 9.67779 22.1023 8.94492 20.7282C7.47918 18.0715 6.47149 15.14 6.19666 12.117C5.92184 9.82674 5.64701 7.17009 6.5631 5.06309C7.02114 3.96379 7.84562 3.0477 8.85331 2.49805C9.31136 2.22322 11.8764 0.849091 11.0519 2.49805C7.6624 8.91065 7.6624 15.4149 10.5939 22.0107C10.9603 22.7436 11.4184 23.568 11.8764 24.3009V24.2093Z" fill="#EF5226"/><path
                                                    d="M9.12698 23.9373C7.01998 22.5631 5.37102 20.731 4.18011 18.5324C2.62276 15.8757 -0.0338913 8.63863 4.18011 6.71484C4.18011 6.71484 4.36332 6.71484 4.45493 6.71484C4.45493 6.71484 4.63815 6.71484 4.63815 6.89806C4.63815 6.98967 4.63815 7.17289 4.63815 7.2645C4.63815 8.91345 4.63815 10.5624 4.82137 12.1198C5.09619 14.5932 5.73745 17.0666 6.74515 19.3568C7.38641 20.9142 8.21089 22.4715 9.21858 23.9373H9.12698Z" fill="#EF5226"/><path
                                                    d="M7.84521 24.7593C6.47108 24.3013 5.18855 23.66 3.99764 22.8355C2.34869 21.8278 0.974557 20.3621 0.241687 18.5299C-0.0331386 17.6138 -0.124747 16.6061 0.241687 15.5985C0.424905 15.0488 0.974557 13.3998 1.34099 14.774C2.16547 17.6138 3.5396 20.2705 5.55499 22.4691C6.28786 23.2936 7.11234 24.0265 7.93682 24.7593H7.84521Z" fill="#EF5226"/></svg>
									</span>
                                    <span class="rtlc-tab-tick">&#10003;</span>
                                </div>
                                <div class="rtlc-tab-body">
                                    <span class="rtlc-tab-name"><?php esc_html_e( 'RadiusTheme', 'listygo' ); ?></span>
                                    <span class="rtlc-tab-sub"><?php esc_html_e( 'Bought direct from us', 'listygo' ); ?></span>
                                </div>
                            </a>
                        </div>

                        <!-- ThemeForest Tab Content -->
                        <div id="rtlc-tab-themeforest" class="rtlc-tab-content">
                            <div class="rtlc-field-group">
                                <label for="rt_purchase_code"><?php esc_html_e( 'Purchase Code', 'listygo' ); ?> <span class="rtlc-required">*</span></label>
                                <input type="text" id="rt_purchase_code" name="rt_license[<?php echo esc_attr( $this->theme_slug ); ?>_license_key]" class="rtlc-input" value="<?php echo esc_attr( $purchase_code ); ?>" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" <?php echo $tf_valid ? 'readonly' : ''; ?> />
                                <p class="rtlc-field-desc"><?php esc_html_e( 'Enter your theme purchase code.', 'listygo' ); ?></p>
                            </div>

                            <div class="rtlc-field-group">
                                <label><?php esc_html_e( 'License Status', 'listygo' ); ?></label>
                                <?php if ( $tf_valid ) { ?>
                                    <span class="rtlc-status-btn rtlc-verified"><?php esc_html_e( 'Activated', 'listygo' ); ?></span>
                                <?php } elseif ( $tf_domain_mismatch ) { ?>
                                    <span class="rtlc-status-btn rtlc-unverified"><?php esc_html_e( 'Domain Mismatch', 'listygo' ); ?></span>
                                <?php } else { ?>
                                    <span class="rtlc-status-btn rtlc-unverified"><?php esc_html_e( 'Not Activated', 'listygo' ); ?></span>
                                <?php } ?>
                            </div>

                            <?php
                            $supported_until = isset( $this->options[ $this->theme_slug . '_license' ]['supported_until'] )
                                    ? $this->options[ $this->theme_slug . '_license' ]['supported_until']
                                    : '';
                            if ( $tf_valid && $supported_until ) {
                                $is_expired = strtotime( $supported_until ) < time();
                                $date_class = $is_expired ? ' rtlc-support-date--expired' : '';
                                $date_icon  = $is_expired ? 'dashicons-warning' : 'dashicons-calendar-alt';
                                $date_label = $is_expired
                                        ? esc_html__( 'Support expired: %s', 'listygo' )
                                        : esc_html__( 'Support expires: %s', 'listygo' );
                                ?>
                                <div class="rtlc-support-date<?php echo esc_attr( $date_class ); ?>">
                                    <span class="dashicons <?php echo esc_attr( $date_icon ); ?>"></span>
                                    <span>
										<?php
                                        printf(
                                        /* translators: %s: formatted support expiry date */
                                                $date_label,
                                                '<strong>' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $supported_until ) ) ) . '</strong>',
                                        );
                                        ?>
									</span>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="rtlc-notice-box rtlc-notice-warning">
                                <span class="dashicons dashicons-warning"></span>
                                <div>
                                    <?php
                                    printf(
                                    /* translators: 1: opening strong tag, 2: closing strong tag, 3: domain name, 4: support link */
                                            __( 'Please keep in mind, you can activate one license in %1$sone domain%2$s. Having trouble? %3$s.', 'listygo' ),
                                            '<strong>',
                                            '</strong>',
                                            '<a href="' . esc_url( $support_url ) . '" target="_blank">' . esc_html__( 'Contact Support Center', 'listygo' ) . '</a>',
                                    );
                                    ?>
                                </div>
                            </div>

                            <?php if ( $tf_valid ) { ?>
                                <button type="button" class="rtlc-activate-btn rtlc-btn-disabled" disabled>
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <?php esc_html_e( 'License Activated', 'listygo' ); ?>
                                </button>
                            <?php } else { ?>
                                <button type="button" id="rtlc_license_check" class="rtlc-activate-btn">
                                    <span class="dashicons dashicons-lock"></span>
                                    <?php esc_html_e( 'Activate License', 'listygo' ); ?>
                                </button>
                            <?php } ?>

                            <!-- Envato to EDD migration banner -->
                            <div class="rtlc-migration-banner">
                                <div class="rtlc-migration-banner__content">
                                    <h4 class="rtlc-migration-banner__title">
                                        <span class="rtlc-migration-banner__icon"><span class="dashicons dashicons-megaphone"></span></span>
                                        <?php esc_html_e( 'Get Direct Support from the Team Behind Your Theme', 'listygo' ); ?>
                                    </h4>
                                    <p class="rtlc-migration-banner__desc">
                                        <?php
                                        printf(
                                        /* translators: 1: bold text for non-exclusive model date, 2: "Learn why" link */
                                                __( 'Following Envato transition to a %1$s, we now offer our themes directly on our site. Technical & priority support is no longer available via ThemeForest — we handle it directly at radiustheme.com. %2$s', 'listygo' ),
                                                '<strong>' . esc_html__( 'non-exclusive model on July 1, 2026', 'listygo' ) . '</strong>',
                                                '<a href="https://www.radiustheme.com/buy-direct-or-migrate-support/" target="_blank" class="rtlc-migration-banner__link">' . esc_html__( 'Learn more', 'listygo' ) . ' &#8599;</a>',
                                        );
                                        ?>
                                    </p>
                                    <h5 class="rtlc-migration-banner__subtitle"><?php esc_html_e( 'What you get:', 'listygo' ); ?></h5>
                                    <ul class="rtlc-migration-banner__list">
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( '3 months free support extension', 'listygo' ); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Regular updates & improvements', 'listygo' ); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Priority technical support', 'listygo' ); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'License management dashboard', 'listygo' ); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Update new version instantly from your site', 'listygo' ); ?></li>
                                        <li><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Rate-locked support renewals', 'listygo' ); ?></li>
                                    </ul>
                                    <a href="https://www.radiustheme.com/envato-license-redemption" target="_blank" class="rtlc-migration-banner__btn">
                                        <?php esc_html_e( 'Get Direct Support', 'listygo' ); ?> &#8599;
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- RadiusTheme Tab Content -->
                        <div id="rtlc-tab-radiustheme" class="rtlc-tab-content" style="display:none;">
                            <?php
                            global $listygo_edd_updater;
                            if ( $listygo_edd_updater && method_exists( $listygo_edd_updater, 'license_page_content' ) ) {
                                $listygo_edd_updater->license_page_content();
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="rtlc-sidebar">
                    <!-- Where's my key — ThemeForest -->
                    <div id="rtlc-sidebar-themeforest" class="rtlc-sidebar-card">
                        <h3><span class="dashicons dashicons-search"></span> <?php esc_html_e( "Where's my purchase code?", 'listygo' ); ?></h3>
                        <ol class="rtlc-steps">
                            <li><?php esc_html_e( 'Log in to your ThemeForest account.', 'listygo' ); ?></li>
                            <li><?php esc_html_e( 'Go to your Downloads page.', 'listygo' ); ?></li>
                            <li><?php esc_html_e( 'Click "Download" then "License certificate & purchase code".', 'listygo' ); ?></li>
                        </ol>
                        <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank" class="rtlc-sidebar-link"><?php esc_html_e( 'Learn more on Envato', 'listygo' ); ?> &#8599;</a>
                    </div>

                    <!-- Where's my key — RadiusTheme -->
                    <div id="rtlc-sidebar-radiustheme" class="rtlc-sidebar-card" style="display:none;">
                        <h3><span class="dashicons dashicons-search"></span> <?php esc_html_e( "Where's my license key?", 'listygo' ); ?></h3>
                        <ol class="rtlc-steps">
                            <li><?php esc_html_e( 'Log in to your account at RadiusTheme.com.', 'listygo' ); ?></li>
                            <li><?php esc_html_e( 'Go to Downloads, then License keys.', 'listygo' ); ?></li>
                            <li><?php printf( esc_html__( 'Copy the key for %s.', 'listygo' ), esc_html( $theme_name ) ); ?></li>
                        </ol>
                        <a href="https://www.radiustheme.com/my-account/" target="_blank" class="rtlc-sidebar-link"><?php esc_html_e( 'Open RadiusTheme account', 'listygo' ); ?> &#8599;</a>
                    </div>

                    <!-- Need a hand -->
                    <div class="rtlc-sidebar-card rtlc-help-card">
                        <h3><?php esc_html_e( 'Need a hand?', 'listygo' ); ?></h3>
                        <p><?php esc_html_e( 'Activation issues are usually a mistyped code or a license already in use on another domain.', 'listygo' ); ?></p>
                        <a href="<?php echo esc_url( $support_url ); ?>" target="_blank" class="rtlc-support-btn">
                            <span class="dashicons dashicons-format-chat"></span>
                            <?php esc_html_e( 'Contact Support Center', 'listygo' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Show a global admin notice prompting ThemeForest users to redeem their license.
     *
     * Only displays when the ThemeForest (Envato) license is active
     * and the RadiusTheme (EDD) license is not.
     *
     * @return void
     */
    public function envato_migration_notice() {
        // Skip if EDD license is already active.
        if ( ! empty( rtlc_is_valid_edd()['success'] ) ) {
            return;
        }

        // Only show when ThemeForest license is active.
        if ( empty( rtlc_is_valid_envato()['success'] ) ) {
            return;
        }

        // Allow dismissing the notice.
        $dismiss_key = 'rtlc_migration_notice_dismissed';
        if ( get_option( $dismiss_key ) ) {
            return;
        }

        // Handle dismiss action.
        if ( isset( $_GET['rtlc_dismiss_migration'] ) && '1' === $_GET['rtlc_dismiss_migration'] ) {
            if ( wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'rtlc_dismiss_migration' ) ) {
                update_option( $dismiss_key, 1 );

                return;
            }
        }

        $redeem_url  = 'https://www.radiustheme.com/envato-license-redemption';
        $dismiss_url = wp_nonce_url( add_query_arg( 'rtlc_dismiss_migration', '1' ), 'rtlc_dismiss_migration' );
        ?>
        <div class="notice rtlc-global-migration-notice is-dismissible" style="border-left-color: #3232ff; padding: 16px 20px; display: flex; align-items: flex-start; gap: 14px;">
            <span class="dashicons dashicons-megaphone" style="color: #3232ff; font-size: 22px; width: 22px; height: 22px; margin-top: 2px; flex-shrink: 0;"></span>
            <div style="flex: 1;">
                <p style="margin: 0 0 6px; font-size: 14px; font-weight: 600; color: #1e1b4b;">
                    <?php echo esc_html( $this->theme_name ) . ' — ' . esc_html__( 'Get Direct Support from the Team Behind Your Theme', 'listygo' ); ?>
                </p>
                <p style="margin: 0 0 12px; font-size: 13px; line-height: 1.6; color: #4b5563;">
                    <?php
                    printf(
                    /* translators: %s: "Learn more" link */
                            __( 'Connect RadiusTheme license to get priority support, one-click updates, and 3 extra months of support — free, straight from the developers. %s', 'listygo' ),
                            '<a href="https://www.radiustheme.com/buy-direct-or-migrate-support/" target="_blank" style="color: #3232ff; text-decoration: underline;">' . esc_html__( 'Learn more', 'listygo' ) . ' &#8599;</a>',
                    );
                    ?>
                </p>
                <a href="<?php echo esc_url( $redeem_url ); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 4px; font-size: 13px; font-weight: 600; color: #fff; background: #3232ff; text-decoration: none; padding: 7px 18px; border-radius: 5px;">
                    <?php esc_html_e( 'Get Direct Support', 'listygo' ); ?> &#8599;
                </a>
                <a href="<?php echo esc_url( $dismiss_url ); ?>" style="margin-left: 12px; font-size: 13px; color: #6b7280; text-decoration: none;">
                    <?php esc_html_e( 'Dismiss', 'listygo' ); ?>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     *
     * @return void
     */
    public function theme_option() {
        register_setting(
                'rt_option_group',
                'rt_license',
                [ $this, 'sanitize_text' ],
        );

        add_settings_section(
                'rt_license_section',
                false,
                false,
                'listygo-setting',
        );

        add_settings_field(
                'rt_purchase_code',
                esc_html__( 'Purchase Code', 'listygo' ),
                [ $this, 'purchase_code_callback' ],
                'listygo-setting',
                'rt_license_section',
        );

        add_settings_field(
                'rt_license_status',
                esc_html__( 'License Status', 'listygo' ),
                [ $this, 'license_status_callback' ],
                'listygo-setting',
                'rt_license_section',
        );

        add_settings_field(
                'rt_license_note',
                esc_html__( 'Note:', 'listygo' ),
                [ $this, 'license_note_callback' ],
                'listygo-setting',
                'rt_license_section',
        );

        add_settings_field(
                'rtlc_license_check',
                false,
                [ $this, 'license_check_callback' ],
                'listygo-setting',
                'rt_license_section',
        );
    }


    /**
     * Sanitize each setting field as needed
     *
     * @param  array  $input  Contains all settings fields as array keys.
     *
     * @return array
     */
    public function sanitize_text( $input ) {
        $new_input = [];

        if ( isset( $input['rt_purchase_code'] ) ) {
            $new_input['rt_purchase_code'] = sanitize_text_field( $input['rt_purchase_code'] );
        }

        return $new_input;
    }

    /**
     * Get the settings option array and print one of its values
     *
     * @return void
     */
    public function purchase_code_callback() {
        $value = '';

        // this first line is for checking old codebase.
        if ( isset( $this->options[ $this->theme_slug . '_license_key' ] ) ) {
            $value = esc_attr( $this->options[ $this->theme_slug . '_license_key' ] );
        } elseif ( isset( $this->options[ $this->theme_slug . '_license' ] ) && isset( $this->options[ $this->theme_slug . '_license' ]['key'] ) ) {
            $value = esc_attr( $this->options[ $this->theme_slug . '_license' ]['key'] );
        }

        printf(
                '<input type="text" class="regular-text" id="rt_purchase_code" name="rt_license[%1$s_license_key]" value="%2$s" />',
                esc_attr( $this->theme_slug ),
                esc_attr( $value ),
        );
    }

    /**
     * Check license status
     *
     * @return void
     */
    public function license_status_callback() {
        $verify = false;

        $status_text = esc_html__( 'Not Activated', 'listygo' );

        if ( rtlc_is_valid()['success'] ) {
            $verify      = true;
            $status_text = __( 'Activated', 'listygo' );
        } elseif ( isset( rtlc_is_valid()['domain_match'] ) && ! rtlc_is_valid()['domain_match'] ) {
            $status_text = __( 'Domain Mismatch', 'listygo' );
        }

        $class = ( $verify ) ? 'verified' : 'unverified';
        echo '<span class="rtlc-status-btn rtlc-' . esc_attr( $class ) . '">' . esc_html( $status_text ) . '</span>';
    }

    /**
     * License note callback
     *
     * @return void
     */
    public function license_note_callback() {
        $support = 'https://www.radiustheme.com/contact/';
        $status  = sprintf(
        /* translators: Support Center */
                __( 'Please keep in mind, you can activate one license in one domain, if you face any problem in activation, please contact our <a href="%s" target="_blank">Support Center</a>', 'listygo' ),
                esc_url( $support ),
        );

        echo '<span class="rtcl-note">' . wp_kses(
                        $status,
                        [
                                'a' => [
                                        'href'   => [],
                                        'target' => [],
                                ],
                        ],
                ) . '</span> <br><pre>';
    }

    /**
     * Active license button
     *
     * @return void
     */
    public function license_check_callback() {
        printf(
                '<input type="button" id="rtlc_license_check" class="button button-primary rtcl-active-btn" value="%s" /> <span class="rtlc-loader"><i class="dashicons dashicons-update spin"></i></span>',
                esc_html__( 'Activate License', 'listygo' ),
        );
    }

    /**
     * Ajax action function to verify license.
     *
     * Tries the v2 REST API first when RTLC_LICENSE_API_KEY is defined.
     * Falls back to the legacy v1 page-template endpoint automatically.
     * Always echoes a JS-compatible value: true, false, or 555.
     *
     * @return void
     */
    public function rtlc_verification() {
        $purchase_code = ( ! empty( $_REQUEST['purchase_code'] ) ) ? wp_unslash( sanitize_text_field( $_REQUEST['purchase_code'] ) ) : '';

        if ( $purchase_code ) {
            $domain_name = rtlc_get_domain_name();

            // ── v2 path ──────────────────────────────────────────────────────
            if ( $this->license_api_key ) {
                $result = $this->verify_v2( $purchase_code, $domain_name );

                if ( null !== $result ) {
                    // v2 returned a definitive answer — send it and stop.
                    echo wp_json_encode( $result );
                    die();
                }
                // null means v2 was unreachable; fall through to v1.
            }

            // ── v1 fallback (existing behaviour, unchanged) ───────────────────
            $rt_license_server = $this->license_url;

            if ( ! $rt_license_server ) {
                die();
            }

            $api_url     = "{$rt_license_server}/?theme_name={$this->theme_name}&purchase_code=" . $purchase_code . '&domain_name=' . $domain_name;
            $envato_data = wp_remote_get( $api_url );

            if ( is_wp_error( $envato_data ) ) {
                die();
            }

            $envato_data = wp_remote_retrieve_body( $envato_data );

            if ( $envato_data ) {
                if ( $envato_data == '"true"' ) {
                    $arr_inputs = get_option( 'rt_licenses' );

                    $arr_inputs[ $this->theme_slug . '_license' ] = [
                            'key'    => sanitize_text_field( $purchase_code ),
                            'domain' => esc_html( $domain_name ),
                    ];

                    update_option( 'rt_licenses', $arr_inputs );
                }

                echo json_decode( $envato_data );
            }
        }

        die();
    }

    /**
     * Call the v2 REST API and, on success, persist the license locally.
     *
     * Returns the JS-compatible value (true, false, 555) on a valid HTTP 200
     * response, or null when the request itself failed so the caller can
     * transparently fall back to v1.
     *
     * @param  string  $purchase_code
     * @param  string  $domain_name
     *
     * @return true|false|int|null
     */
    private function verify_v2( $purchase_code, $domain_name ) {
        $api_url = add_query_arg(
                [
                        'theme_name'    => $this->theme_name,
                        'purchase_code' => $purchase_code,
                        'domain_name'   => $domain_name,
                ],
                $this->license_url_v2,
        );

        $response = wp_remote_get( $api_url, [
                'headers' => [
                        'X-RT-API-Key' => $this->license_api_key,
                ],
        ] );

        // Network error or non-200 → signal fallback to v1.
        if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
            return null;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        // Unexpected payload → signal fallback to v1.
        if ( ! is_array( $body ) || ! array_key_exists( 'response', $body ) ) {
            return null;
        }

        if ( true === $body['response'] ) {
            $arr_inputs = get_option( 'rt_licenses' );

            $arr_inputs[ $this->theme_slug . '_license' ] = [
                    'key'             => sanitize_text_field( $purchase_code ),
                    'domain'          => esc_html( $domain_name ),
                    'supported_until' => isset( $body['data']['supported_until'] ) ? sanitize_text_field( $body['data']['supported_until'] ) : '',
                    'sold_at'         => isset( $body['data']['sold_at'] ) ? sanitize_text_field( $body['data']['sold_at'] ) : '',
            ];

            update_option( 'rt_licenses', $arr_inputs );

            return true;
        }

        // false or 555 — pass straight through to JS.
        return $body['response'];
    }
}

if ( is_admin() ) {
    new Helper();
}
