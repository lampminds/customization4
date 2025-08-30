## Lampminds Customization for Laravel (Filament 3)

Small utility package with configuration and helpers to streamline customization in Laravel + Filament projects.

### Features
- **Config presets**: timezone, date/time display formats, number formatting
- **Helper functions**: date/time conversions (UTC ↔ local), formatting, Filament route helpers, simple metrics formatters
- **Publishable assets**: config and views
- **Migrations loader**: package migrations are autoloaded

## Requirements
- **PHP**: ^8.1
- **Laravel**: ^10.0 | ^11.0 | ^12.0
- **Filament**: ^3.0
- **Spatie**: `spatie/laravel-medialibrary` ^11.13, `spatie/laravel-permission` ^6.20

## Installation
```bash
composer require lampminds/customization
```

The service provider is auto-discovered via `composer.json`.

### Publish configuration (recommended)
```bash
php artisan vendor:publish --provider="Lampminds\\Customization\\CustomizationServiceProvider" --tag="lmpcustomization-config"
```
This will create `config/lmpcustomization.php` in your app.

### Publish views (optional)
```bash
php artisan vendor:publish --provider="Lampminds\\Customization\\CustomizationServiceProvider" --tag="lmpcustomization-views"
```
Views will be published to `resources/views/vendor/lmpcustomization`.

## Configuration
Key options in `config/lmpcustomization.php`:
- **timezone_shift**: IANA timezone used to convert to/from UTC
- **display_date_format**: default display format for dates
- **display_datetime_format**: default display format for datetimes
- **display_time_format**: default display format for times
- **database_date_format**, **database_datetime_format**: storage formats
- **decimal_point**, **thousands_separator**: number formatting

Example override in `.env`:
```env
LMP_DECIMAL_POINT=.
LMP_THOUSANDS_SEPARATOR=,
```

## Usage

### Date & time helpers
```php
use Illuminate\Support\Carbon;

// Convert app-local time to UTC
$utc = toUtc('2025-01-01 12:00');

// Convert UTC to app-local time
$local = fromUtc('2025-01-01T15:00:00Z');

// Localized display
echo localized_date(Carbon::now());     // e.g. "Jan 01, 2025"
echo localized_time(Carbon::now());     // e.g. "14:35"
```

### Number helpers
```php
echo formatNumber(12345.678, true);     // "$ 12,345.68" (based on config)
echo formatPercentage(1.075);           // "7 %" (based on config)
```

### Filament helpers
```php
if (isFilamentCreating()) {
    // Set autofocus or defaults for create forms
}

if (isFilamentEditing()) {
    // Logic for edit forms
}
```

### Misc helpers
```php
nickname($userId);           // "John-D" → derived from User name
human_filesize(1536000);     // "1.47M"
human_count(12500);          // "12.5K"
```

### Migrations
Package migrations are auto-loaded from the package. If you add new ones and need to re-run:
```bash
php artisan migrate
```

## Updating
After updating, re-publish assets if needed (use `--force` to overwrite):
```bash
php artisan vendor:publish --provider="Lampminds\\Customization\\CustomizationServiceProvider" --tag="lmpcustomization-config" --force
php artisan vendor:publish --provider="Lampminds\\Customization\\CustomizationServiceProvider" --tag="lmpcustomization-views" --force
```

## License
Released under the MIT License.


