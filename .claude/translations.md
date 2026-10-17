# Listygo Theme — Translation Reference

## Overview

- **Text domain**: `listygo`
- **Type**: WordPress Theme
- **Languages directory**: `languages/`
- **POT file**: `languages/listygo.pot`
- **Total translatable strings**: ~647
- **Supported languages**: 15

## Supported Languages

| Code    | Language   | PO File              | MO File              | L10n PHP File              |
|---------|------------|----------------------|----------------------|----------------------------|
| es_ES   | Spanish    | listygo-es_ES.po     | listygo-es_ES.mo     | listygo-es_ES.l10n.php     |
| fr_FR   | French     | listygo-fr_FR.po     | listygo-fr_FR.mo     | listygo-fr_FR.l10n.php     |
| de_DE   | German     | listygo-de_DE.po     | listygo-de_DE.mo     | listygo-de_DE.l10n.php     |
| pt_BR   | Portuguese | listygo-pt_BR.po     | listygo-pt_BR.mo     | listygo-pt_BR.l10n.php     |
| it_IT   | Italian    | listygo-it_IT.po     | listygo-it_IT.mo     | listygo-it_IT.l10n.php     |
| nl_NL   | Dutch      | listygo-nl_NL.po     | listygo-nl_NL.mo     | listygo-nl_NL.l10n.php     |
| ru_RU   | Russian    | listygo-ru_RU.po     | listygo-ru_RU.mo     | listygo-ru_RU.l10n.php     |
| ja      | Japanese   | listygo-ja.po        | listygo-ja.mo        | listygo-ja.l10n.php        |
| zh_CN   | Chinese    | listygo-zh_CN.po     | listygo-zh_CN.mo     | listygo-zh_CN.l10n.php     |
| ar      | Arabic     | listygo-ar.po        | listygo-ar.mo        | listygo-ar.l10n.php        |
| fa_IR   | Persian    | listygo-fa_IR.po     | listygo-fa_IR.mo     | listygo-fa_IR.l10n.php     |
| ur      | Urdu       | listygo-ur.po        | listygo-ur.mo        | listygo-ur.l10n.php        |
| he_IL   | Hebrew     | listygo-he_IL.po     | listygo-he_IL.mo     | listygo-he_IL.l10n.php     |
| hi_IN   | Hindi      | listygo-hi_IN.po     | listygo-hi_IN.mo     | listygo-hi_IN.l10n.php     |
| bn_BD   | Bengali    | listygo-bn_BD.po     | listygo-bn_BD.mo     | listygo-bn_BD.l10n.php     |

## Translation File Types

| Extension   | Purpose                                         |
|-------------|------------------------------------------------|
| `.pot`      | Template — master list of all translatable strings |
| `.po`       | Portable Object — human-readable translations    |
| `.mo`       | Machine Object — compiled binary for WordPress   |
| `.l10n.php` | PHP array — WordPress 6.5+ fast loading via OPcache |

## How Translations Work in WordPress

1. Theme code uses `__('string', 'listygo')`, `esc_html__()`, `_e()`, `esc_html_e()`, `_n()`, `_x()`, etc.
2. `.pot` file is generated from source code (contains all `msgid` entries with empty `msgstr`)
3. `.po` files are created per locale with translated `msgstr` values
4. `.mo` files are compiled from `.po` for WordPress to load at runtime
5. `.l10n.php` files provide faster loading in WordPress 6.5+

## Translation Functions Used in This Theme

| Function              | Purpose                                    |
|-----------------------|--------------------------------------------|
| `__()`                | Return translated string                    |
| `_e()`                | Echo translated string                      |
| `esc_html__()`        | Return escaped translated string            |
| `esc_html_e()`        | Echo escaped translated string              |
| `esc_attr__()`        | Return attribute-escaped translated string  |
| `esc_attr_e()`        | Echo attribute-escaped translated string    |
| `_n()`                | Plural form translation                     |
| `_x()`                | Translation with context                    |
| `_ex()`               | Echo translation with context               |
| `_nx()`               | Plural form with context                    |
| `sprintf()`           | Used with `__()` for parameterized strings  |

## Step-by-Step Update Instructions

### When new strings are added to the theme:

1. **Regenerate `.pot`** file (using wp-cli, Loco Translate, or makepot)
2. **Merge** new strings into existing `.po` files: `msgmerge --update`
3. **Translate** only the new empty `msgstr` entries
4. **Compile** `.mo` files: `msgfmt`
5. **Generate** `.l10n.php` files
6. **Validate** with `msgfmt --check` and `php -l`

### Quick commands:

```bash
# Merge new .pot strings into all .po files
for po in languages/*.po; do
  msgmerge --update --backup=none "$po" languages/listygo.pot
done

# Compile all .mo files
for po in languages/*.po; do
  msgfmt "$po" -o "${po%.po}.mo"
done

# Validate all .po files
for po in languages/*.po; do
  msgfmt --check "$po" 2>&1 && echo "OK: $po" || echo "FAIL: $po"
done

# Validate all .l10n.php files
for php in languages/*.l10n.php; do
  php -l "$php" 2>&1 && echo "OK: $php" || echo "FAIL: $php"
done
```

## Domain-Specific Terminology

| English Term     | Context                                              |
|-----------------|------------------------------------------------------|
| Listing          | A classified ad or directory post                    |
| Store            | A seller's or agent's shop/profile page              |
| Booking          | Reservation or appointment for a listing             |
| Agent            | Real estate agent or service provider                |
| Category         | Listing classification group                         |
| Location         | Geographic area for a listing                        |
| Custom Field     | Additional data field on a listing                   |
| Membership       | Subscription plan for sellers                        |
| Badge            | Visual indicator (e.g., "Featured", "New")           |
| Sidebar          | Widget area on the page layout                       |

## PO File Rules

- **Escape sequences**: Use `\"` for quotes inside `msgstr`, `\\n` for literal newline display, actual newline for line continuation
- **Placeholders**: Keep `%s`, `%d`, `%1$s`, `%2$s` exactly as in `msgid`
- **HTML**: Preserve all HTML tags (`<span>`, `<a>`, `<strong>`, etc.) and attributes
- **Punctuation**: Match trailing periods, colons, ellipsis from source string
- **Context**: `msgctxt` lines must NOT be translated — they are lookup keys
- **Plurals**: Use `msgstr[0]` for singular, `msgstr[1]` for plural (varies by language)

## RTL Languages

Arabic (`ar`), Persian (`fa_IR`), Urdu (`ur`), and Hebrew (`he_IL`) are RTL languages. WordPress handles text direction automatically — no special markup needed in translation strings.

## Plural Forms by Language

| Language(s)                                                          | Plural-Forms |
|----------------------------------------------------------------------|--------------|
| Spanish, French, Italian, Portuguese, German, Dutch, Persian, Urdu, Hindi, Bengali, Hebrew | `nplurals=2; plural=(n != 1);` |
| Russian                                                                | `nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 \|\| n%100>14) ? 1 : 2);` |
| Japanese, Chinese                                                      | `nplurals=1; plural=0;` |
| Arabic                                                                 | `nplurals=2; plural=(n != 1);` |