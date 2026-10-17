# Translate Theme Strings

You are a WordPress theme translation agent for the `listygo` theme (text domain: `listygo`).

## Task

Depending on the situation, this command handles two scenarios:

- **New strings added to `.pot`** → Merge new strings into existing `.po` files, translate only the new untranslated entries, recompile `.mo` files.
- **Fresh / all languages** → Generate or fully update `.po` files for all (or specified) language(s).

If no argument is given, update ALL supported languages.

## Supported Languages

| Code    | Language   | PO File                    |
|---------|------------|----------------------------|
| es_ES   | Spanish    | listygo-es_ES.po           |
| fr_FR   | French     | listygo-fr_FR.po           |
| de_DE   | German     | listygo-de_DE.po           |
| pt_BR   | Portuguese | listygo-pt_BR.po           |
| it_IT   | Italian    | listygo-it_IT.po           |
| nl_NL   | Dutch      | listygo-nl_NL.po           |
| ru_RU   | Russian    | listygo-ru_RU.po           |
| ja      | Japanese   | listygo-ja.po              |
| zh_CN   | Chinese    | listygo-zh_CN.po           |
| ar      | Arabic     | listygo-ar.po              |
| fa_IR   | Persian    | listygo-fa_IR.po           |
| ur      | Urdu       | listygo-ur.po              |
| he_IL   | Hebrew     | listygo-he_IL.po           |
| hi_IN   | Hindi      | listygo-hi_IN.po           |
| bn_BD   | Bengali    | listygo-bn_BD.po           |

---

## Step-by-Step Instructions

### Step 0 — Initialize `.po` files if they don't exist

For each target locale that doesn't have a `.po` file yet, create one from the `.pot`:

```bash
for locale in es_ES fr_FR de_DE pt_BR it_IT nl_NL ru_RU ja zh_CN ar fa_IR ur he_IL hi_IN bn_BD; do
  po="languages/listygo-${locale}.po"
  if [ ! -f "$po" ]; then
    msginit --no-translator --input=languages/listygo.pot --output-file="$po" --locale="$locale"
    echo "Created: $po"
  fi
done
```

### Step 1 — Detect new/untranslated strings

Run `msgmerge` for each target locale to merge new `.pot` strings into the existing `.po` file:

```bash
msgmerge --update --backup=none languages/listygo-LOCALE.po languages/listygo.pot
```

- `--update` overwrites the `.po` file in place
- `--backup=none` skips creating a `.po~` backup file
- This preserves ALL existing translations and adds new `msgid` entries with empty `msgstr ""`

To merge all locales at once:
```bash
for po in languages/*.po; do
  msgmerge --update --backup=none "$po" languages/listygo.pot
  echo "Merged: $po"
done
```

### Step 2 — Find untranslated strings

After merging, identify which entries are new (empty `msgstr`):

```bash
msgattrib --untranslated languages/listygo-LOCALE.po
```

Or for all files:
```bash
for po in languages/*.po; do
  count=$(msgattrib --untranslated "$po" | grep -c '^msgid' 2>/dev/null || echo 0)
  echo "$po: $count untranslated"
done
```

### Step 3 — Translate only the new strings

Read each `.po` file, identify all entries where `msgstr ""` (after the header), and translate ONLY those entries. Do NOT re-translate already-translated strings.

**Important**: Since this theme has ~647 strings, process translations in batches per language. Work through one language at a time completely before moving to the next.

Rules:
- Preserve ALL existing `msgstr` values exactly as-is
- Only fill in the empty `msgstr ""` entries
- Keep `%s`, `%d`, `%1$s`, `%2$s` placeholders intact
- Keep escaped quotes `\"` in `msgstr` as `\"`
- Handle plural forms correctly using `msgid_plural` / `msgstr[0]` / `msgstr[1]` when present
- Preserve trailing spaces in strings like `"Name: "`, `"Email: "`, etc.
- Keep HTML tags intact (e.g., `<span>`, `<a href>`, `<strong>`)

### Step 4 — Compile `.mo` files

After updating each `.po` file, compile:

```bash
msgfmt languages/listygo-LOCALE.po -o languages/listygo-LOCALE.mo
```

All locales at once:
```bash
for po in languages/*.po; do
  mo="${po%.po}.mo"
  msgfmt "$po" -o "$mo" && echo "OK: $mo" || echo "FAIL: $po"
done
```

### Step 5 — Generate `.l10n.php` files

WordPress 6.5+ prefers `.l10n.php` files over `.mo` for faster loading via OPcache. After compiling each `.mo`, generate the corresponding `.l10n.php` file.

For each `.po` file, parse all translated `msgid` / `msgstr` pairs (skip the header entry where `msgid ""`) and write a PHP file with this exact format:

```php
<?php
return array(
    'project-id-version' => 'listygo',
    'messages' => array(
        'Original string' => 'Translated string',
        'Another string' => 'Another translation',
    ),
);
```

**Rules for `.l10n.php` generation:**

- File name: same as `.po` but with `.l10n.php` extension (e.g. `listygo-es_ES.l10n.php`)
- Skip entries where `msgstr` is empty (untranslated strings)
- Skip the header entry (where `msgid ""`)
- For entries with `msgctxt`, use the context delimiter `"\4"` (EOT character): key = `context . "\4" . msgid`
- For plural forms (`msgid_plural`), join translations with null byte `"\0"`: value = `msgstr[0] . "\0" . msgstr[1]`
- Escape single quotes in both keys and values with `\'`
- Use `array()` syntax (not `[]`) for PHP 5.6 compatibility
- The file must start with `<?php` and return the array

Example output for Spanish (`listygo-es_ES.l10n.php`):
```php
<?php
return array(
    'project-id-version' => 'listygo',
    'messages' => array(
        'Home' => 'Inicio',
        'Search' => 'Buscar',
        'Categories' => 'Categorías',
        'Listing' => 'Anuncio',
    ),
);
```

### Step 6 — Validate

Run `msgfmt --check` on each updated file to confirm validity:

```bash
for po in languages/*.po; do
  msgfmt --check "$po" 2>&1 && echo "OK: $po" || echo "FAIL: $po"
done
```

Also verify each `.l10n.php` file is valid PHP:
```bash
for php in languages/*.l10n.php; do
  php -l "$php" 2>&1 && echo "OK: $php" || echo "FAIL: $php"
done
```

---

## Full Update Workflow (when .pot has new strings)

```bash
# 1. Initialize any missing .po files
for locale in es_ES fr_FR de_DE pt_BR it_IT nl_NL ru_RU ja zh_CN ar fa_IR ur he_IL hi_IN bn_BD; do
  po="languages/listygo-${locale}.po"
  if [ ! -f "$po" ]; then
    msginit --no-translator --input=languages/listygo.pot --output-file="$po" --locale="$locale"
  fi
done

# 2. Merge new strings into all .po files
for po in languages/*.po; do
  msgmerge --update --backup=none "$po" languages/listygo.pot
done

# 3. Check how many untranslated strings exist per file
for po in languages/*.po; do
  count=$(msgattrib --untranslated "$po" | grep -c '^msgid' 2>/dev/null || echo 0)
  [ "$count" -gt 0 ] && echo "$po: $count new strings need translation"
done

# 4. (Claude translates the new empty msgstr entries in each .po file)

# 5. Recompile all .mo files
for po in languages/*.po; do
  msgfmt "$po" -o "${po%.po}.mo" && echo "OK: ${po%.po}.mo"
done

# 6. Generate .l10n.php files (Claude parses each .po and writes the PHP array)

# 7. Validate
for po in languages/*.po; do
  msgfmt --check "$po" 2>&1 | grep -v "^$" && echo "FAIL: $po" || echo "OK: $po"
done
for php in languages/*.l10n.php; do
  php -l "$php" 2>&1 | grep -v "^$" && echo "FAIL: $php" || echo "OK: $php"
done
```

---

## Translation Guidelines

- Use formal/polite register appropriate for software UI
- Keep translations concise — they appear in buttons, labels, headings, and short messages
- For RTL languages (Arabic, Persian, Urdu, Hebrew): translations render RTL automatically in WordPress, no special markup needed
- Preserve exact punctuation style (trailing periods, colons, ellipsis `...`)
- Theme-specific terms: "Listing" = a classified ad/post; "Store" = a seller's shop page; "Booking" = reservation/appointment; "Agent" = real estate agent or service provider
- Keep CSS class names and HTML attributes unchanged in translated strings
- Preserve `sprintf` format specifiers (`%s`, `%d`, `%1$s`, `%2$s`) in exact order

## Plural Forms Reference

| Language(s)                        | Plural-Forms |
|------------------------------------|--------------|
| Spanish, French, Italian, Portuguese, German, Dutch, Persian, Urdu, Hindi, Bengali, Hebrew | `nplurals=2; plural=(n != 1);` |
| Russian                             | `nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n%10>=2 && n%10<=4 && (n%100<12 \|\| n%100>14) ? 1 : 2);` |
| Japanese, Chinese                   | `nplurals=1; plural=0;` |
| Arabic                              | `nplurals=2; plural=(n != 1);` |

---

## User Argument

$ARGUMENTS