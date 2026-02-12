# Filament 4 & 5 compatibility

This package is tested and supported on **Filament 4.x** and **Filament 5.x**.

## What we support

- **Resources**: `UserResource`, `ParameterResource`, and base `LmpResource` use the same Resource API (`form()`, `table()`, `getPages()`) that exists in both v4 and v5.
- **Pages**: `LmpListRecords`, `LmpCreateRecord`, `LmpEditRecord`, `LmpViewRecord` extend Filament’s `ListRecords`, `CreateRecord`, `EditRecord`, `ViewRecord`. Method names and behavior used here (`getRedirectUrl()`, `mutateFormDataBeforeCreate()`, `getTitle()`, etc.) are unchanged between v4 and v5.
- **Forms**: Form components use `Filament\Forms\Form`, `Filament\Forms\Components\*`, and `Filament\Forms\Get` (or closure `$get`) in the same way in both versions.
- **Tables**: Table components and table API (`columns`, `filters`, `actions`, `bulkActions`, enums like `ActionsPosition`, `FiltersLayout`) are compatible with both versions.
- **Icons**: Package uses string icon names (e.g. `heroicon-o-users`). These work in both Filament 4 and 5.

## Filament 5 notes

- Filament 5 requires **Livewire 4** and **Laravel 11.28+**. Your app must satisfy those; the package does not change that.
- If you use **Tailwind**, Filament 5 typically expects **Tailwind CSS 4** in the app. This package does not ship Tailwind; compatibility is in your app’s build.
- **Spatie Media Library plugin**: We require `filament/spatie-laravel-media-library-plugin: ^4.0|^5.0`. Use the major version that matches your Filament (4.x or 5.x). If a v5 of the plugin is not yet released, use the v4 plugin with Filament 5 only if the plugin’s docs say it’s supported.

## Version constraint

In `composer.json` we use:

- `filament/filament`: `^4.0|^5.0`
- `filament/spatie-laravel-media-library-plugin`: `^4.0|^5.0`

So you can install the package in either a Filament 4 or Filament 5 project; Composer will resolve the right Filament and plugin versions.

## Reporting issues

If something breaks on a specific Filament version, please report the Filament and Laravel versions (e.g. Filament 5.0, Laravel 12) and the error or behavior you see.
