# Lampminds Customization for Laravel (Filament 4 & 5)

A comprehensive utility package with Filament resources, configuration helpers, and customization components to streamline Laravel + Filament projects.

## ✨ Features

### 🎯 **Filament Resources**
- **UserResource**: Enhanced user management with roles and permissions
- **ParameterResource**: Flexible parameter management with multiple data types
- **Automatic Registration**: Resources automatically appear in your Filament panel
- **Full Customization**: Override models, navigation, labels, and more

### 🛠️ **Custom Components**
- **Form Components**: 20+ specialized form fields (LmpFormTitle, LmpFormEmail, LmpFormCurrency, etc.)
- **Table Components**: 15+ table columns (LmpTableTitle, LmpTableToggle, LmpTableTimeStamp, etc.)
- **Audit Components**: Built-in created/updated by tracking
- **Location Components**: Phone numbers, addresses, currencies, timezones

### ⚙️ **Configuration & Helpers**
- **Config presets**: timezone, date/time display formats, number formatting
- **Helper functions**: date/time conversions (UTC ↔ local), formatting, Filament route helpers
- **Publishable assets**: config, views, and migrations
- **Environment configuration**: Configure everything via `.env` files

## 📋 Requirements

- **PHP**: ^8.2
- **Laravel**: ^11.0 | ^12.0
- **Filament**: ^4.0 | ^5.0
- **Spatie**: `spatie/laravel-medialibrary` ^11.13, `spatie/laravel-permission` ^6.20

### Filament 4 & 5 compatibility

This package supports **Filament 4.x and 5.x**. The same codebase is used for both; resources, pages, form and table APIs used here are compatible with both versions. Filament 5 runs on Livewire 4 and may require Tailwind CSS 4 in your app—see the [Filament 5 upgrade guide](https://filamentphp.com/docs/5.x/upgrade-guide). The Spatie Media Library plugin is required as `^4.0|^5.0`; use the version that matches your Filament major version.

## 🚀 Quick Start

### 1. Install the Package
```bash
composer require lampminds/customization
```

### 2. Run Migrations
```bash
php artisan vendor:publish --tag="lmpcustomization-migrations"
php artisan migrate
```

### 3. Publish Configuration (Recommended)
```bash
php artisan vendor:publish --tag="lmpcustomization-config"
```

### 4. Register Resources in Your Filament Panel

Add the resources to your `AdminPanelProvider`:

```php
// In app/Providers/Filament/AdminPanelProvider.php
use Lampminds\Customization\Resources\ParameterResource;
use Lampminds\Customization\Resources\UserResource;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... your existing configuration
        ->resources([
            ParameterResource::class,
            UserResource::class,
        ]);
}
```

**That's it!** Your Filament resources will now appear in the admin panel.

## ⚙️ Configuration

### Basic Configuration

Edit `config/lmpcustomization.php` to customize the package:

```php
return [
    // Enable/disable specific resources
    'enable_user_resource' => true,
    'enable_parameter_resource' => true,
    
    // Configure which panel to register with
    'panel_id' => 'admin', // or null for all panels
    
    // Navigation customization
    'user_navigation_group' => 'User Management',
    'parameter_navigation_group' => 'Settings',
    'user_navigation_sort' => 1,
    'parameter_navigation_sort' => 2,
];
```

### Environment Variables

Configure via `.env` files:

```env
# Enable/disable resources
LMP_ENABLE_USER_RESOURCE=true
LMP_ENABLE_PARAMETER_RESOURCE=true

# Panel configuration
LMP_PANEL_ID=admin

# Navigation customization
LMP_USER_NAVIGATION_GROUP="User Management"
LMP_PARAMETER_NAVIGATION_GROUP="Settings"
```

## 🎨 Advanced Customization

### Using Your Own Models

Replace package models with your own:

```php
// In config/lmpcustomization.php
'user_model' => \Lampminds\Customization\Models\User::class,
'parameter_model' => \Lampminds\Customization\Models\Parameter::class,
```

Or via environment variables:
```env
LMP_USER_MODEL="App\\Models\\User"
LMP_PARAMETER_MODEL="App\\Models\\Parameter"
```

### Using Your Own Resources

Extend package resources and use your own:

```php
// Create app/Filament/Resources/CustomUserResource.php
<?php

namespace App\Filament\Resources;

use Lampminds\Customization\Resources\UserResource as BaseUserResource;

class CustomUserResource extends BaseUserResource
{
    public static function getNavigationGroup(): ?string
    {
        return 'My Custom Group';
    }
    
    // Override any methods as needed
}
```

Then configure:
```php
// In config/lmpcustomization.php
'user_resource' => \App\Filament\Resources\CustomUserResource::class,
```

### Manual Registration (Alternative)

Disable auto-registration and register manually:

```php
// In config/lmpcustomization.php
'enable_user_resource' => false,
'enable_parameter_resource' => false,
```

Then in your `AdminPanelProvider`:
```php
use Lampminds\Customization\Resources\ParameterResource;
use Lampminds\Customization\Resources\UserResource;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configuration
        ->resources([
            ParameterResource::class,
            UserResource::class,
        ]);
}
```

## 📦 Available Resources

### UserResource
- **Features**: User management with roles and permissions
- **Navigation**: User Management group
- **Capabilities**: Create, edit, view, delete users
- **Special**: Role assignment, permission management, audit tracking

### ParameterResource
- **Features**: Flexible parameter management
- **Navigation**: Settings group
- **Data Types**: String, integer, boolean, date, datetime, time, text
- **Modes**: Editable, readonly, internal
- **Special**: File uploads, rich text editor, category filtering

## 🧩 Available Components

### Form Components
- `LmpFormTitle` - Enhanced title input
- `LmpFormEmail` - Email validation
- `LmpFormToggle` - Boolean toggle
- `LmpFormCurrency` - Currency input with formatting
- `LmpFormDate` - Date picker
- `LmpFormDateTimePicker` - DateTime picker
- `LmpFormFullName` - Full name input
- `LmpFormGenericText` - Generic text input
- `LmpFormIsbn` - ISBN validation
- `LmpFormLink` - URL input
- `LmpFormLocation` - Location selector
- `LmpFormNaPhone` - North American phone
- `LmpFormArPhone` - Arabic phone
- `LmpFormQuestion` - Question input
- `LmpFormRichEditor` - Rich text editor
- `LmpFormSlug` - URL slug generator
- `LmpFormSnake` - Snake case input
- `LmpFormTextArea` - Multi-line text
- `LmpFormTimeStamp` - Timestamp input
- `LmpFormCreatedByStamp` - Created by tracking
- `LmpFormUpdatedByStamp` - Updated by tracking

### Table Components
- `LmpTableTitle` - Enhanced title display
- `LmpTableToggle` - Boolean toggle display
- `LmpTableTimeStamp` - Timestamp formatting
- `LmpTableCreatedByStamp` - Created by display
- `LmpTableUpdatedByStamp` - Updated by display
- `LmpTableCurrency` - Currency formatting
- `LmpTableDate` - Date formatting
- `LmpTableIsbn` - ISBN display
- `LmpTableLocation` - Location display
- `LmpTableNaPhone` - North American phone
- `LmpTableArPhone` - Arabic phone
- `LmpTableNumber` - Number formatting
- `LmpTablePercentage` - Percentage display
- `LmpTableRelationCounter` - Relationship counter

## 🔧 Helper Functions

### Date & Time Helpers
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

### Number Helpers
```php
echo formatNumber(12345.678, true);     // "$ 12,345.68" (based on config)
echo formatPercentage(1.075);           // "7 %" (based on config)
```

### Filament Helpers
```php
if (isFilamentCreating()) {
    // Set autofocus or defaults for create forms
}

if (isFilamentEditing()) {
    // Logic for edit forms
}
```

### Miscellaneous Helpers
```php
nickname($userId);           // "John-D" → derived from User name
human_filesize(1536000);     // "1.47M"
human_count(12500);          // "12.5K"
```

## 🗄️ Database

### Migrations
Package migrations are auto-loaded. The package includes:

- **Users table enhancements**: Additional fields for user management
- **Parameters table**: Flexible parameter storage
- **Geographic data**: Countries, states, cities, timezones, currencies
- **Audit fields**: Created/updated by tracking

### Models
- **User**: Enhanced user model with roles and permissions
- **Parameter**: Flexible parameter model with multiple data types
- **Geographic models**: Country, State, City, Timezone, Currency
- **BaseModel**: Foundation model with audit capabilities

## 🔍 Troubleshooting

### Resources Not Showing Up
1. Check package installation: `composer show lampminds/customization`
2. Verify service provider registration in `composer.json`
3. Check configuration in `config/lmpcustomization.php`
4. Clear config cache: `php artisan config:clear`

### Model Conflicts
Use the model customization feature to bind your own models:

```php
'user_model' => \Lampminds\Customization\Models\User::class,
'parameter_model' => \Lampminds\Customization\Models\Parameter::class,
```

### Customization Not Working
Ensure you've published the config and cleared cache:
```bash
php artisan vendor:publish --tag="lmpcustomization-config"
php artisan config:clear
```

## 📚 Documentation

- **Installation Guide**: See `INSTALLATION.md` for detailed setup instructions
- **Configuration**: All options documented in `config/lmpcustomization.php`
- **Components**: Each component has inline documentation

## 🔄 Updating

After updating, re-publish assets if needed (use `--force` to overwrite):
```bash
php artisan vendor:publish --tag="lmpcustomization-config" --force
php artisan vendor:publish --tag="lmpcustomization-views" --force
```

## 📄 License

Released under the MIT License.

---

**Need help?** Check the troubleshooting section or create an issue on GitHub.
