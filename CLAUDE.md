# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**Listygo** is a commercial (ThemeForest) WordPress listing-directory **theme** by RadiusTheme, built on top of the
**Classified Listing** plugin family (`Rtcl` free, `RtclPro`, Store, Booking). Its companion plugin **listygo-core**
(Elementor widgets, demo importer) lives in the sibling repo `../listygo-core`. There is no test suite and no PHP linter
config; verification is done in a local WordPress install.

## Build commands (gulp, Node)

```bash
npm install
npx gulp scss        # assets/sass/{style,admin}.scss -> assets/css/
npx gulp rtl         # assets/css/*.css -> assets/css-auto-rtl/ (rtlcss)
npx gulp minify-css  # assets/css/*.css -> assets/css/minified/
npx gulp minify-js   # assets/js/*.js   -> assets/js/minified/
npx gulp watch       # on .scss change: scss -> rtl -> minify-css -> minify-js
npx gulp zip         # package theme into __build/listygo.zip
```

Never edit files in `assets/css/`, `assets/css/minified/`, `assets/css-auto-rtl/` or `assets/js/minified/` by hand —
edit `assets/sass/**` or `assets/js/*.js` and rebuild. The frontend loads the **minified** CSS/JS (and the
`css-auto-rtl` copy when `is_rtl()`), so a source change is invisible until the matching gulp task has run.
Asset URL helpers are in `inc/helper-traits/resource-load-trait.php` (`get_css`, `get_nomin_css`, `get_js`, `get_rtl_css`, …).

## Architecture

- **Bootstrap:** `functions.php` (`Listygo_Main`) → `inc/constants.php` (`LISTYGO_THEME_*` constants) →
  `inc/includes.php`, which loads the helper traits, `inc/helper.php` and then every other `inc/` file via
  `Helper::requires()`. New `inc/` files must be registered there.
- **Namespace:** all theme PHP classes are under `radiustheme\listygo`. `Helper` (`inc/helper.php`) composes the traits in
  `inc/helper-traits/` and is the general utility class (`Helper::get_custom_listing_template()`, asset helpers, etc.).
- **Options:** Customizer settings are defined in `inc/customizer/settings/*.php`, defaults in
  `inc/customizer/customizer-default-data.php` (`rttheme_generate_defaults()`). `RDTListygo` (`inc/rdtheme.php`) merges
  theme mods with defaults into the static `RDTListygo::$options` array on `after_setup_theme` — read options from
  there, not `get_theme_mod()`. Per-page layout state (header/footer style, banner, sidebar…) is resolved into other
  `RDTListygo::$*` statics by `inc/layout-settings.php`.
- **Assets:** `inc/scripts.php` (`Scripts`) registers/enqueues everything; customizer-driven CSS comes from
  `inc/dynamic-style.php` and is added inline as `listygo-dynamic`.
- **Classified Listing integration:**
  - `classified-listing/` overrides Rtcl plugin templates (same relative paths as the plugin's `templates/`).
  - `classified-listing/custom/` holds theme-only listing templates (grid/list cards, single layouts, search forms,
    doctor-chamber / food-menu custom fields), loaded with `Helper::get_custom_listing_template()`.
  - `classified-listing/custom/functions.php` (`Listing_Functions`) hooks into Rtcl/RtclPro and is only loaded when
    **both** `Rtcl` and `RtclPro` classes exist. It uses the new Form Builder (`Rtcl\Services\FormBuilder\*`);
    `inc/data-migration.php` migrates old custom-field data to the form builder.
  - `inc/api.php` adds REST/app API fields (chambers, food menu, logos) for the listing app.
- **Bundled plugins:** `inc/plugin-bundle/*.zip` are installed via TGM (`inc/tgm-config.php`). `functions.php`
  shows an admin notice when `LISTYGO_CORE` is older than the required version — bump that check when the theme
  depends on a newer listygo-core.
- **Updater / licensing:** `inc/updater/` and `inc/helper-traits/lc-*.php` (skipped when `RT_DEBUG` is defined).
- `sample-data/` holds demo import data (content XML, customizer, widgets, Rtcl settings/forms).

## Conventions

- Text domain is `listygo`; translations live in `languages/` (`listygo.pot` + per-locale `.po/.mo/.l10n.php`).
  The `/translate` project command (`.claude/commands/translate.md`, reference in `.claude/translations.md`) updates them.
- Calls into Rtcl / RtclPro / listygo-core classes must be guarded with `class_exists()` / `method_exists()` /
  `function_exists()`, since those plugins may be on a different version or branch.
- Changelog lives in `readme.txt` under `ChangeLog:` using `Added:` / `Fixed:` / `Updated:` prefixes. The theme
  version is in the `style.css` header (`LISTYGO_THEME_VERSION` reads it; it becomes `time()` under `WP_DEBUG`).
