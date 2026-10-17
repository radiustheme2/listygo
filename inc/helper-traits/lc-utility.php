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
 * Check Radius Theme License
 */
class Utility {
    /**
     * Class Constructor.
     */
    public function __construct() {
        add_action( 'admin_head', [ $this, 'style' ] );
        add_action( 'admin_footer', [ $this, 'script' ] );
        add_action( 'in_admin_header', [ $this, 'hide_notices_on_license_page' ], 999 );
    }

    /**
     * Remove all admin notices on the license page.
     *
     * @return void
     */
    public function hide_notices_on_license_page() {
        if ( ! isset( $_GET['page'] ) || 'rtlc' !== $_GET['page'] ) {
            return;
        }

        remove_all_actions( 'admin_notices' );
        remove_all_actions( 'all_admin_notices' );
    }

    /**
     * License page styles.
     *
     * @return void
     */
    public function style() {
        if ( ! isset( $_GET['page'] ) || 'rtlc' !== $_GET['page'] ) {
            return;
        }
        ?>
        <style>
            /* ── Theme Color Variables ── */
            :root {
                --rtlc-primary: #3232ff;
                --rtlc-primary-hover: #0202c9;
                --rtlc-primary-light: #f5f8ff;
                --rtlc-primary-lighter: #eff1ff;
                --rtlc-primary-border: #f9a8b3;
                --rtlc-primary-border-hover: #fff0f2;
                --rtlc-dark: #2d2b55;
                --rtlc-dark-text: #c4c1e0;
                --rtlc-danger: #d63638;
                --rtlc-danger-hover: #b91c1c;
                --rtlc-danger-dark: #dc2626;
                --rtlc-success: #00a32a;
                --rtlc-success-bg: #ecfdf5;
                --rtlc-success-border: #a7f3d0;
                --rtlc-success-text: #065f46;
                --rtlc-info-bg: #eff6ff;
                --rtlc-info-border: #bfdbfe;
                --rtlc-info-text: #1e40af;
                --rtlc-info-icon: #3b82f6;
                --rtlc-warning-bg: #fffbeb;
                --rtlc-warning-border: #fde68a;
                --rtlc-warning-text: #92400e;
                --rtlc-warning-icon: #f59e0b;
            }

            /* ── Page Header ── */
            .rtlc-page-header {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
                gap: 24px;
                margin-bottom: 16px;
                max-width: 1240px;
            }

            .rtlc-page-header h1 {
                margin: 0;
            }

            .rtlc-page-header .notice {
                order: 3;
                width: 100%;
            }

            .rtlc-subtitle {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                max-width: 1200px;
                margin: 0 0 24px;
                padding: 14px 18px;
                font-size: 13px;
                line-height: 1.6;
                color: var(--rtlc-info-text);
                background: var(--rtlc-info-bg);
                border: 1px solid var(--rtlc-info-border);
                border-radius: 8px;
            }

            .rtlc-subtitle .dashicons {
                color: var(--rtlc-info-icon);
                flex-shrink: 0;
                margin-top: 1px;
            }

            .rtlc-activation-status {
                display: flex;
                align-items: center;
                gap: 6px;
                white-space: nowrap;
                padding: 6px 14px;
                font-size: 13px;
                font-weight: 500;
                color: #6b7280;
                background: #f3f4f6;
                border: 1px solid #e5e7eb;
                border-radius: 50px;
            }

            .rtlc-status-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: var(--rtlc-danger);
                display: inline-block;
            }

            .rtlc-status-active {
                color: var(--rtlc-success-text);
                background: var(--rtlc-success-bg);
                border-color: var(--rtlc-success-border);
            }

            .rtlc-status-active .rtlc-status-dot {
                background: var(--rtlc-success);
            }

            /* ── Two Column Layout ── */
            .rtlc-page-layout {
                display: flex;
                gap: 24px;
                align-items: flex-start;
                max-width: 1240px;
            }

            .rtlc-main {
                flex: 1;
                min-width: 0;
            }

            /* ── Card Container ── */
            .rtlc-license-wrap {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            }

            /* ── Theme Info ── */
            .rtlc-theme-info {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 20px 24px;
                border-bottom: 1px solid #f3f4f6;
            }

            .rtlc-theme-icon {
                width: 44px;
                height: 44px;
                border-radius: 10px;
                background: var(--rtlc-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                font-size: 22px;
                font-weight: 700;
                color: #fff;
                line-height: 1;
            }

            .rtlc-theme-name {
                font-size: 15px;
                font-weight: 600;
                color: #1d2327;
                display: flex;
                align-items: center;
                gap: 8px;
                text-transform: capitalizes;
            }

            .rtlc-version {
                font-size: 11px;
                font-weight: 500;
                color: var(--rtlc-primary);
                background: var(--rtlc-primary-lighter);
                padding: 2px 8px;
                border-radius: 50px;
            }

            .rtlc-theme-desc {
                font-size: 13px;
                color: #6b7280;
                margin-top: 2px;
            }

            /* ── Divider Label ── */
            .rtlc-divider-label {
                font-size: 11px;
                font-weight: 600;
                letter-spacing: 0.05em;
                color: #4b5563;
                padding: 16px 24px 0;
            }

            /* ── Tab Toggles ── */
            .rtlc-tab-toggle {
                display: flex;
                gap: 12px;
                padding: 16px 24px 0;
                max-width: 560px;
            }

            .rtlc-tab {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 12px;
                padding: 16px;
                text-decoration: none;
                background: #fff;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.18s ease;
                position: relative;
                color: #1d2327;
            }

            .rtlc-tab .dashicons {
                font-size: 16px;
                width: 16px;
                height: 16px;
                line-height: 16px;
            }

            /* ── Tab header row ── */
            .rtlc-tab-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
            }

            .rtlc-tab-logo {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
                border-radius: 10px;
                overflow: hidden;
                flex-shrink: 0;
            }

            .rtlc-tab-icon {
                width: 44px;
                height: 44px;
                flex-shrink: 0;
            }

            .rtlc-tab-tick {
                display: none;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                font-size: 11px;
                font-weight: 700;
                align-items: center;
                justify-content: center;
                line-height: 1;
                flex-shrink: 0;
            }

            /* ── Tab body ── */
            .rtlc-tab-body {
                display: flex;
                flex-direction: column;
                gap: 3px;
            }

            .rtlc-tab-name {
                font-size: 15px;
                font-weight: 700;
                color: #1d2327;
                line-height: 1.2;
            }

            .rtlc-tab-sub {
                font-size: 12px;
                font-weight: 400;
                color: #6b7280;
                line-height: 1.3;
            }

            /* ── RadiusTheme logo container (multicolor wheel needs a bg) ── */
            .rtlc-tab[data-tab="radiustheme"] .rtlc-tab-logo {
                background: #f0f4ff;
            }

            .rtlc-tab[data-tab="radiustheme"] .rtlc-tab-icon {
                width: 30px;
                height: 30px;
            }

            /* ── Inactive hover ── */
            .rtlc-tab:hover,
            .rtlc-tab:focus {
                border-color: #d1d5db;
                background: #fafafa;
                text-decoration: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
                border-radius: 12px;
            }

            .rtlc-tab:focus {
                box-shadow: none;
                outline: none;
            }

            /* ── ThemeForest — active ── */
            .rtlc-tab[data-tab="themeforest"].rtlc-tab-active,
            .rtlc-tab[data-tab="themeforest"].rtlc-tab-active:hover,
            .rtlc-tab[data-tab="themeforest"].rtlc-tab-active:focus {
                border-color: #87e64b;
                background: #f4ffe8;
                box-shadow: 0 4px 14px rgba(135, 230, 75, 0.3);
            }

            .rtlc-tab[data-tab="themeforest"].rtlc-tab-active .rtlc-tab-tick {
                display: flex;
                background: #87e64b;
                color: #2d6200;
            }

            .rtlc-tab[data-tab="themeforest"].rtlc-tab-active .rtlc-tab-sub {
                color: #3a7200;
            }

            /* ── ThemeForest — inactive hover ── */
            .rtlc-tab[data-tab="themeforest"]:not(.rtlc-tab-active):hover {
                border-color: #b5f072;
            }

            /* ── RadiusTheme — active ── */
            .rtlc-tab[data-tab="radiustheme"].rtlc-tab-active,
            .rtlc-tab[data-tab="radiustheme"].rtlc-tab-active:hover,
            .rtlc-tab[data-tab="radiustheme"].rtlc-tab-active:focus {
                border-color: #2659f2;
                background: #eef3ff;
                box-shadow: 0 4px 14px rgba(38, 89, 242, 0.22);
            }

            .rtlc-tab.rtlc-tab-active[data-tab="radiustheme"] .rtlc-tab-logo {
                background: #fefefe;
            }

            .rtlc-tab[data-tab="radiustheme"].rtlc-tab-active .rtlc-tab-tick {
                display: flex;
                background: #2659f2;
                color: #fff;
            }

            .rtlc-tab[data-tab="radiustheme"].rtlc-tab-active .rtlc-tab-sub {
                color: #2659f2;
            }

            /* ── RadiusTheme — inactive hover ── */
            .rtlc-tab[data-tab="radiustheme"]:not(.rtlc-tab-active):hover {
                border-color: #93b4fd;
            }

            /* ── Tab Content ── */
            .rtlc-tab-content {
                padding: 24px 24px 28px;
            }

            /* ── Form Fields ── */
            .rtlc-field-group {
                margin-bottom: 20px;
            }

            .rtlc-field-group label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 6px;
            }

            .rtlc-required {
                color: var(--rtlc-danger);
            }

            .rtlc-tab-content .rtlc-input {
                width: 100%;
                max-width: 100%;
                padding: 10px 14px;
                font-size: 14px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                background: #fff;
                color: #1d2327;
                box-sizing: border-box;
                transition: border-color 0.15s;
            }

            .rtlc-tab-content .rtlc-input:focus {
                border-color: var(--rtlc-primary);
                box-shadow: none;
                outline: none;
            }

            .rtlc-tab-content .rtlc-input[readonly] {
                background: #f3f4f6;
                color: #6b7280;
                cursor: not-allowed;
            }

            .rtlc-field-desc {
                font-size: 12px;
                color: #9ca3af;
                margin: 6px 0 0;
            }

            /* ── Support Date ── */
            .rtlc-support-date {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 8px 12px;
                margin-bottom: 20px;
                font-size: 13px;
                color: var(--rtlc-success-text);
                background: var(--rtlc-success-bg);
                border: 1px solid var(--rtlc-success-border);
                border-radius: 6px;
            }

            .rtlc-support-date .dashicons {
                color: var(--rtlc-success);
                font-size: 16px;
                width: 16px;
                height: 16px;
                flex-shrink: 0;
            }

            .rtlc-support-date--expired {
                color: var(--rtlc-warning-text);
                background: var(--rtlc-warning-bg);
                border-color: var(--rtlc-warning-border);
            }

            .rtlc-support-date--expired .dashicons {
                color: var(--rtlc-warning-icon);
            }

            .rtlc-extend-support-link {
                margin-left: auto;
                font-size: 12px;
                font-weight: 600;
                color: var(--rtlc-primary);
                text-decoration: none;
                white-space: nowrap;
            }

            .rtlc-extend-support-link:hover {
                text-decoration: underline;
                color: var(--rtlc-primary-hover);
            }

            .rtlc-support-date--expired .rtlc-extend-support-link {
                color: var(--rtlc-warning-text);
            }

            .rtlc-support-date--expired .rtlc-extend-support-link:hover {
                color: var(--rtlc-warning-text);
            }

            /* ── Migration Banner ── */
            .rtlc-migration-banner {
                margin-top: 24px;
                padding: 20px;
                background: linear-gradient(135deg, #eff3ff 0%, #f5f0ff 100%);
                border: 1px solid #c7d2fe;
                border-radius: 8px;
            }

            .rtlc-migration-banner__icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                background: var(--rtlc-primary);
                border-radius: 8px;
                flex-shrink: 0;
            }

            .rtlc-migration-banner__icon .dashicons {
                color: #fff;
                font-size: 18px;
                width: 18px;
                height: 18px;
            }

            .rtlc-migration-banner__title {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 14px;
                font-weight: 700;
                color: #1e1b4b;
                margin: 0 0 8px;
            }

            .rtlc-migration-banner__desc {
                font-size: 13px;
                line-height: 1.6;
                color: #4b5563;
                margin: 0 0 12px;
            }

            .rtlc-migration-banner__link {
                color: var(--rtlc-primary);
                text-decoration: underline;
                font-weight: 500;
            }

            .rtlc-migration-banner__link:hover {
                color: var(--rtlc-primary-hover);
            }

            .rtlc-migration-banner__subtitle {
                font-size: 13px;
                font-weight: 600;
                color: #1e1b4b;
                margin: 0 0 10px;
            }

            .rtlc-migration-banner__list {
                list-style: none;
                margin: 0 0 14px;
                padding: 0;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px 20px;
            }

            .rtlc-migration-banner__closing {
                font-size: 13px;
                line-height: 1.5;
                color: #4b5563;
                margin: 0 0 16px;
            }

            .rtlc-migration-banner__list li {
                display: flex;
                align-items: flex-start;
                gap: 6px;
                font-size: 13px;
                line-height: 1.5;
                color: #1e1b4b;
            }

            .rtlc-migration-banner__list .dashicons {
                color: #7761fc;
                font-size: 16px;
                width: 16px;
                height: 16px;
                flex-shrink: 0;
                margin-top: 1px;
            }

            .rtlc-migration-banner__btn {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                font-size: 13px;
                font-weight: 600;
                color: var(--rtlc-primary);
                text-decoration: none;
                padding: 6px 16px;
                background: #fff;
                border: 1px solid var(--rtlc-primary);
                border-radius: 6px;
                transition: all 0.15s;
            }

            .rtlc-migration-banner__btn:hover {
                background: var(--rtlc-primary);
                color: #fff;
                border-color: var(--rtlc-primary);
                text-decoration: none;
            }

            /* ── Notice Box ── */
            .rtlc-notice-box {
                display: flex;
                gap: 10px;
                align-items: flex-start;
                padding: 12px 16px;
                background: var(--rtlc-info-bg);
                border: 1px solid var(--rtlc-info-border);
                border-radius: 6px;
                margin-bottom: 20px;
                font-size: 13px;
                color: var(--rtlc-info-text);
                line-height: 1.5;
            }

            .rtlc-notice-box .dashicons {
                color: var(--rtlc-info-icon);
                margin-top: 1px;
                flex-shrink: 0;
            }

            .rtlc-notice-box a {
                color: var(--rtlc-info-text);
                text-decoration: underline;
            }

            .rtlc-notice-success {
                background: var(--rtlc-success-bg);
                border-color: var(--rtlc-success-border);
                color: var(--rtlc-success-text);
            }

            .rtlc-notice-success .dashicons {
                color: var(--rtlc-success);
            }

            .rtlc-notice-warning {
                background: var(--rtlc-warning-bg);
                border-color: var(--rtlc-warning-border);
                color: var(--rtlc-warning-text);
            }

            .rtlc-notice-warning .dashicons {
                color: var(--rtlc-warning-icon);
            }

            .rtlc-notice-warning a {
                color: var(--rtlc-warning-text);
            }

            /* ── Status Badge ── */
            .rtlc-status-btn {
                display: inline-block;
                color: #fff;
                padding: 4px 14px;
                border-radius: 50px;
                font-size: 13px;
                font-weight: 500;
                line-height: 1.5;
            }

            .rtlc-unverified {
                background: var(--rtlc-danger);
            }

            .rtlc-verified {
                background: var(--rtlc-success);
            }

            /* ── Activate / Deactivate Button ── */
            .rtlc-activate-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                padding: 12px 20px;
                font-size: 14px;
                font-weight: 600;
                color: #fff;
                background: var(--rtlc-primary);
                border: none;
                border-radius: 6px;
                cursor: pointer;
                transition: background 0.15s;
                text-decoration: none;
                box-sizing: border-box;
            }

            .rtlc-activate-btn:hover {
                background: var(--rtlc-primary-hover);
            }

            .rtlc-activate-btn:focus {
                outline: none;
                box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--rtlc-primary);
            }

            .rtlc-activate-btn .dashicons {
                font-size: 18px;
                width: 18px;
                height: 18px;
            }

            .rtlc-deactivate-btn {
                background: var(--rtlc-danger-dark);
            }

            .rtlc-deactivate-btn:hover {
                background: var(--rtlc-danger-hover);
            }

            .rtlc-deactivate-btn:focus {
                box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--rtlc-danger-dark);
            }

            /* ── Disabled Button State ── */
            .rtlc-btn-disabled {
                background: var(--rtlc-success);
                cursor: not-allowed;
                opacity: 0.85;
            }

            .rtlc-btn-disabled:hover {
                background: var(--rtlc-success);
            }

            .rtlc-btn-disabled:focus {
                box-shadow: none;
            }

            /* ── Button Loading State ── */
            .rtlc-activate-btn.rtlc-loading {
                opacity: 0.75;
                pointer-events: none;
            }

            .rtlc-activate-btn .dashicons.spin {
                animation: dashicons-spin 1s infinite linear;
            }

            @keyframes dashicons-spin {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }

            /* ── Sidebar ── */
            .rtlc-sidebar {
                width: 300px;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .rtlc-sidebar-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            }

            .rtlc-sidebar-card h3 {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                font-weight: 600;
                color: #1d2327;
                margin: 0 0 14px;
            }

            .rtlc-sidebar-card h3 .dashicons {
                color: var(--rtlc-primary);
                font-size: 18px;
                width: 18px;
                height: 18px;
            }

            /* Numbered Steps */
            .rtlc-steps {
                margin: 0 0 14px;
                padding: 0;
                list-style: none;
                counter-reset: rtlc-step;
            }

            .rtlc-steps li {
                counter-increment: rtlc-step;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                font-size: 13px;
                color: #4b5563;
                line-height: 1.5;
                margin-bottom: 10px;
            }

            .rtlc-steps li::before {
                content: counter(rtlc-step);
                display: flex;
                align-items: center;
                justify-content: center;
                min-width: 22px;
                height: 22px;
                font-size: 12px;
                font-weight: 600;
                color: var(--rtlc-primary);
                background: var(--rtlc-primary-lighter);
                border-radius: 50%;
                flex-shrink: 0;
            }

            .rtlc-sidebar-link {
                font-size: 13px;
                font-weight: 500;
                color: var(--rtlc-primary);
                text-decoration: none;
            }

            .rtlc-sidebar-link:hover {
                text-decoration: underline;
                color: var(--rtlc-primary);
            }

            /* Benefits List */
            .rtlc-benefits-list {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .rtlc-benefits-list li {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                font-size: 13px;
                color: #4b5563;
                line-height: 1.5;
                margin-bottom: 12px;
            }

            .rtlc-benefits-list li:last-child {
                margin-bottom: 0;
            }

            .rtlc-benefits-list .dashicons {
                color: var(--rtlc-success);
                font-size: 18px;
                width: 18px;
                height: 18px;
                margin-top: 1px;
                flex-shrink: 0;
            }

            /* Help Card */
            .rtlc-help-card {
                background: var(--rtlc-dark);
                border-color: var(--rtlc-dark);
            }

            .rtlc-help-card h3 {
                color: #fff;
            }

            .rtlc-help-card h3 .dashicons {
                color: var(--rtlc-primary-border);
            }

            .rtlc-help-card p {
                color: var(--rtlc-dark-text);
                font-size: 13px;
                line-height: 1.5;
                margin: 0 0 14px;
            }

            .rtlc-support-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 8px 16px;
                font-size: 13px;
                font-weight: 500;
                color: #fff;
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 6px;
                text-decoration: none;
                transition: background 0.15s, border-color 0.15s;
            }

            .rtlc-support-btn:focus,
            .rtlc-support-btn:hover {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.5);
                color: #fff;
                box-shadow: none;
            }

            .rtlc-support-btn .dashicons {
                font-size: 16px;
                width: 16px;
                height: 16px;
            }

            /* ── Responsive ── */
            @media screen and (max-width: 960px) {
                .rtlc-page-layout {
                    flex-direction: column;
                }

                .rtlc-sidebar {
                    width: 100%;
                }

                .rtlc-page-header {
                    flex-direction: column;
                }
            }

            @media screen and (max-width: 782px) {
                /* Migration banner */
                .rtlc-migration-banner {
                    padding: 16px;
                }

                /* Bullet list — single column */
                .rtlc-migration-banner__list {
                    grid-template-columns: 1fr;
                }

                /* Support date row — wrap link below */
                .rtlc-support-date {
                    flex-wrap: wrap;
                }

                .rtlc-extend-support-link {
                    margin-left: 0;
                    width: 100%;
                    margin-top: 4px;
                }

                /* Global notice */
                .rtlc-global-migration-notice {
                    flex-direction: column !important;
                    gap: 10px !important;
                    padding: 14px 16px !important;
                }

                .rtlc-global-migration-notice > a {
                    display: block !important;
                    text-align: center !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                }

                .rtlc-global-migration-notice > div > a:last-child {
                    display: block !important;
                    margin-left: 0 !important;
                    margin-top: 8px !important;
                    text-align: center !important;
                }
            }

            @media screen and (max-width: 480px) {
                /* ThemeForest tab banner — stack icon above title text */
                .rtlc-migration-banner__title {
                    flex-wrap: wrap;
                    font-size: 13px;
                }

                .rtlc-migration-banner__desc,
                .rtlc-migration-banner__list li {
                    font-size: 12px;
                }

                .rtlc-migration-banner__btn {
                    width: 100%;
                    justify-content: center;
                    padding: 8px 16px;
                }

                /* Global notice — smaller text */
                .rtlc-global-migration-notice p {
                    font-size: 12px !important;
                }
            }

        </style>
        <?php
    }

    /**
     * Ajax Action.
     *
     * @return void
     */
    public function script() {
        if ( wp_script_is( 'jquery', 'done' ) && ( isset( $_GET['page'] ) && 'rtlc' === $_GET['page'] ) ) {
            ?>
            <script type="text/javascript">
                (function ($) {
                    // Tab switching
                    var params = new URLSearchParams(window.location.search);
                    var initialTab = params.get('tab') || 'themeforest';

                    function activateTab(tab) {
                        $('.rtlc-tab').removeClass('rtlc-tab-active');
                        $('.rtlc-tab[data-tab="' + tab + '"]').addClass('rtlc-tab-active');
                        $('.rtlc-tab-content').hide();
                        $('#rtlc-tab-' + tab).show();
                        // Toggle sidebar cards
                        $('#rtlc-sidebar-themeforest, #rtlc-sidebar-radiustheme').hide();
                        $('#rtlc-sidebar-' + tab).show();
                    }

                    // Activate initial tab (supports deep-linking via ?tab= param)
                    activateTab(initialTab);

                    // Ensure RadiusTheme form redirects back to the correct tab after save
                    $('#rtlc-tab-radiustheme form input[name="_wp_http_referer"]').each(function () {
                        var referer = $(this).val();
                        if (referer.indexOf('tab=') === -1) {
                            $(this).val(referer + '&tab=radiustheme');
                        }
                    });

                    // Tab click handler — keep URL in sync so reloads land on the right tab
                    $('.rtlc-tab').on('click', function (e) {
                        e.preventDefault();
                        var tab = $(this).data('tab');
                        activateTab(tab);
                        var url = new URL(window.location.href);
                        url.searchParams.set('tab', tab);
                        window.history.replaceState(null, '', url.toString());
                    });

                    // ThemeForest AJAX license activation
                    var $btn = $("#rtlc_license_check");
                    var btnOriginal = $btn.html();

                    $btn.on("click", function () {
                        let purchase_code = $('#rt_purchase_code').val();

                        if (purchase_code) {
                            $.ajax({
                                type: "post",
                                dataType: "json",
                                url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                                data: {
                                    action: "rtlc_verification",
                                    purchase_code,
                                },
                                beforeSend: function () {
                                    $btn.addClass('rtlc-loading').html('<span class="dashicons dashicons-update spin"></span> <?php esc_html_e( 'Verifying...', 'listygo' ); ?>');
                                },
                                success: function (resp) {
                                    if (resp === 555) {
                                        alert('<?php esc_html_e( 'Purchase code already activated for one domain!!!', 'listygo' ); ?>');
                                        $btn.removeClass('rtlc-loading').html(btnOriginal);
                                    } else {
                                        if (resp) {
                                            var url = new URL(window.location.href);
                                            url.searchParams.set('tab', 'themeforest');
                                            window.location.href = url.toString();
                                            return;
                                        } else {
                                            alert('<?php esc_html_e( 'Sorry!!! Purchase code does not match', 'listygo' ); ?>');
                                        }
                                    }
                                    $btn.removeClass('rtlc-loading').html(btnOriginal);
                                },
                                error: function () {
                                    $btn.removeClass('rtlc-loading').html(btnOriginal);
                                },
                            });
                        } else {
                            alert('<?php esc_html_e( 'Purchase code is required!!!', 'listygo' ); ?>');
                        }
                    });
                })(jQuery);
            </script>
            <?php
        }

        if ( wp_script_is( 'jquery', 'done' ) && ! rtlc_is_valid()['success'] ) {
            if ( isset( $_GET['page'] ) && 'fw-backups-demo-content' === $_GET['page'] ) {
                ?>
                <script type="text/javascript">
                    jQuery("#fw-ext-backups-demo-list .theme-actions a").on("click", function (e) {
                        e.preventDefault();
                        alert('<?php esc_html_e( 'Please activate your theme license to install demo data.', 'listygo' ); ?>');
                        return false;
                    });
                </script>
                <?php
            }

            if ( isset( $_GET['page'] ) && 'listygo-install-plugins' === $_GET['page'] ) {
                ?>
                <script type="text/javascript">
                    jQuery(".row-actions a").on("click", function (e) {
                        if (jQuery(this).closest('td').next().has('span').length > 0) { //find pre packaged
                            e.preventDefault();
                            alert('<?php esc_html_e( 'Please activate your theme license to use this plugin.', 'listygo' ); ?>');
                            return false;
                        }
                    });

                    jQuery(".check-column input").on("change", function (e) {
                        if (!jQuery(e.target).is(':checked')) return;

                        if (jQuery(this).parent().hasClass('column-cb')) { //all checked or not
                            jQuery('table.wp-list-table > tbody  > tr').each(function (index, tr) {
                                if (jQuery(tr).find('.column-source').has('span').length > 0) {
                                    jQuery(tr).find('.check-column input').prop('checked', false);
                                }
                            });
                        } else {
                            if (jQuery(this).closest('th').next().next().has('span').length > 0) { //find pre packaged
                                jQuery(this).prop('checked', false);
                                alert('<?php esc_html_e( 'Please activate your theme license to use this plugin.', 'listygo' ); ?>');
                            }
                        }
                    });
                </script>
                <?php
            }
        }
    }
}

if ( is_admin() ) {
    new Utility();
}

if ( ! function_exists( 'rtlc_get_theme_slug' ) ) {
    /**
     * Get the theme slug used for license option keys.
     *
     * @return string
     */
    function rtlc_get_theme_slug() {
        $theme_info = wp_get_theme();
        $theme_info = ( $theme_info->parent() ) ? $theme_info->parent() : $theme_info;

        return strtolower( trim( preg_replace( '/[^A-Za-z0-9-]+/', '-', $theme_info->get( 'Name' ) ) ) );
    }
}

if ( ! function_exists( 'rtlc_is_valid_edd' ) ) {
    /**
     * Check RadiusTheme Site (EDD) license validity.
     *
     * @return array { success: bool, domain_match: bool }
     */
    function rtlc_is_valid_edd() {
        $slug       = rtlc_get_theme_slug();
        $edd_status = get_option( '_rt_' . $slug . '_license_key_status', '' );

        if ( 'valid' === $edd_status ) {
            return [
                'success'      => true,
                'domain_match' => true,
            ];
        }

        return [
            'success' => false,
        ];
    }
}

if ( ! function_exists( 'rtlc_is_valid_envato' ) ) {
    /**
     * Check ThemeForest (Envato) license validity.
     *
     * @return array { success: bool, domain_match: bool }
     */
    function rtlc_is_valid_envato() {
        $slug       = rtlc_get_theme_slug();
        $get_option = get_option( 'rt_licenses' );

        if ( isset( $get_option[ $slug . '_license_key' ] ) ) {
            return [
                'success'      => true,
                'domain_match' => true,
            ];
        } elseif ( isset( $get_option[ $slug . '_license' ] ) && isset( $get_option[ $slug . '_license' ]['key'] ) ) {
            $domain_name = rtlc_get_domain_name();
            $domain      = $get_option[ $slug . '_license' ]['domain'];

            if ( $domain_name === rtlc_get_domain_name( $domain ) ) {
                return [
                    'success'      => true,
                    'domain_match' => true,
                ];
            } else {
                return [
                    'success'      => false,
                    'domain_match' => false,
                ];
            }
        }

        return [
            'success' => false,
        ];
    }
}

if ( ! function_exists( 'rtlc_is_valid' ) ) {
    /**
     * License Validity Check.
     *
     * Returns true when either the ThemeForest (Envato) or RadiusTheme Site
     * (EDD) license is active, so all downstream restrictions are lifted for
     * both activation methods.
     *
     * @return false[]|true[]
     */
    function rtlc_is_valid() {
        // Check RadiusTheme Site (EDD) license first.
        $edd = rtlc_is_valid_edd();
        if ( ! empty( $edd['success'] ) ) {
            return $edd;
        }

        // Check ThemeForest (Envato) license.
        return rtlc_is_valid_envato();
    }
}

if ( ! function_exists( 'rtlc_get_domain_name' ) ) {
    /**
     * Get Domain Name.
     *
     * @return string
     */
    function rtlc_get_domain_name( $url = null ) {
        $protocols = [ 'http://', 'https://', 'http://www.', 'https://www.', 'www.' ];

        return str_replace( $protocols, '', esc_url( ! empty( $url ) ? $url : site_url() ) );
    }
}
