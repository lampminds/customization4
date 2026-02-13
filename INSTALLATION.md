# LMP Customization Package Installation (Filament 4 & 5)

## I don't see the package features (Users, Parameters in the sidebar)

You will **not** see any package features until you do **all** of the following:

1. **Install the package**: `composer require lampminds/customization`
2. **Publish assets** (config and/or migrations — see "Publishing package assets" below)
3. **Run migrations**: `php artisan migrate`
4. **Register the resources** in your Filament panel (see step 4 below). **This is required** — the package does not auto-register resources.

If you skip step 4, the Users and Parameters menu items will not appear.

---

## Quick Start

### 1. Install the package

```bash
composer require lampminds/customization
```

### 2. Publish package assets and run migrations

**Option A — Publish everything in one go (recommended):**

```bash
php artisan vendor:publish --tag="lmpcustomization"
php artisan migrate
```

**Option B — Publish only what you need:**

```bash
# Config (for enable/disable resources, navigation, etc.)
php artisan vendor:publish --tag="lmpcustomization-config"

# Migrations (required for Parameters and extended Users table)
php artisan vendor:publish --tag="lmpcustomization-migrations"
php artisan migrate

# Views (optional, if the package ships views)
php artisan vendor:publish --tag="lmpcustomization-views"
```

### 3. Publish tags reference

| Tag | What it publishes |
|-----|-------------------|
| `lmpcustomization` | Config + migrations + views (one command for all) |
| `lmpcustomization-config` | `config/lmpcustomization.php` only |
| `lmpcustomization-migrations` | Migration files to `database/migrations` |
| `lmpcustomization-views` | Views to `resources/views/vendor/lmpcustomization` |

**Note:** Models and Filament resources are **not** published — they live inside the package. You use them by registering the resources in your panel (step 4). To customize, extend the package resources or bind your own models in config.

### 4. Register resources in your Filament panel (required)

Open your panel provider (e.g. `app/Providers/Filament/AdminPanelProvider.php`) and add the package resources so they appear in the sidebar.

**One-line registration (recommended):**

```php
use Lampminds\Customization\Customization;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... your existing configuration (id, path, login, etc.)
        ->resources(Customization::resources())   // Adds Parameters + Users (respects config)
        // ... rest of your configuration
}
```

**Or register each resource explicitly:**

```php
use Lampminds\Customization\Resources\ParameterResource;
use Lampminds\Customization\Resources\UserResource;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... your existing configuration
        ->resources([
            ParameterResource::class,
            UserResource::class,
        ])
        // ...
}
```

If you use `->discoverResources(...)` for your app resources, merge the package resources:

```php
->resources(array_merge(
    \Lampminds\Customization\Customization::resources(),
    // ... or list your app resources here
))
```

That's it. After step 4, **Parameters** and **User Management** (Users) should appear in your Filament admin sidebar.

## Configuration

### Basic Configuration

Edit `config/lmpcustomization.php` to customize the package behavior:

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

You can also configure via environment variables:

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

## Advanced Customization

### Using Your Own Models

If you want to use your own User or Parameter models (they must implement the same interface/attributes as the package models):

```php
// In config/lmpcustomization.php
'user_model' => \App\Models\User::class,
'parameter_model' => \App\Models\Parameter::class,
```

Or via environment variables:
```env
LMP_USER_MODEL="App\\Models\\User"
LMP_PARAMETER_MODEL="App\\Models\\Parameter"
```

### Using Your Own Resources

You can extend the package resources and use your own:

```php
// Create app/Filament/Resources/CustomUserResource.php
<?php

namespace App\Filament\Resources;

use Lampminds\Customization\Resources\UserResource as BaseUserResource;

class CustomUserResource extends BaseUserResource
{
    // Override methods as needed
    public static function getNavigationGroup(): ?string
    {
        return 'My Custom Group';
    }
}
```

Then configure:
```php
// In config/lmpcustomization.php
'user_resource' => \App\Filament\Resources\CustomUserResource::class,
```

### Disabling a resource via config

To hide one of the package resources from the list, set in `config/lmpcustomization.php`:

```php
'enable_user_resource' => false,   // hide User resource
'enable_parameter_resource' => false,  // hide Parameter resource
```

Then use `Customization::resources()` in your panel so the list respects this config. If you register the resources manually (listing the classes), they will show regardless of config.

## Available Resources

- **ParameterResource**: Manage application parameters with different data types
- **UserResource**: Enhanced user management with roles and permissions

## Available Components

The package provides custom Filament form and table components:

### Form Components
- `LmpFormTitle`, `LmpFormEmail`, `LmpFormToggle`
- `LmpFormCreatedByStamp`, `LmpFormUpdatedByStamp`
- `LmpFormCurrency`, `LmpFormDate`, `LmpFormDateTimePicker`
- `LmpFormFullName`, `LmpFormGenericText`, `LmpFormIsbn`
- `LmpFormLink`, `LmpFormLocation`, `LmpFormNaPhone`, `LmpFormArPhone`
- `LmpFormQuestion`, `LmpFormRichEditor`, `LmpFormSlug`
- `LmpFormSnake`, `LmpFormTextArea`, `LmpFormTimeStamp`

### Table Components
- `LmpTableTitle`, `LmpTableToggle`, `LmpTableTimeStamp`
- `LmpTableCreatedByStamp`, `LmpTableUpdatedByStamp`
- `LmpTableCurrency`, `LmpTableDate`, `LmpTableIsbn`
- `LmpTableLocation`, `LmpTableNaPhone`, `LmpTableArPhone`
- `LmpTableNumber`, `LmpTablePercentage`, `LmpTableRelationCounter`

## Troubleshooting

### I don’t see Users or Parameters in the Filament sidebar

1. **Did you register the resources?** You must add `->resources(Customization::resources())` (or list `ParameterResource::class`, `UserResource::class`) in your Filament panel provider. Without this, the package resources never appear.
2. Check that the package is installed: `composer show lampminds/customization`
3. Publish config and migrations: `php artisan vendor:publish --tag="lmpcustomization"` then `php artisan migrate`
4. If you use `Customization::resources()`, ensure `config/lmpcustomization.php` has `enable_user_resource` and `enable_parameter_resource` set to `true` (or omit the config so defaults apply). Clear config cache: `php artisan config:clear`

### Model Conflicts

If you have model conflicts, use the model customization feature to bind your own models.

### Customization Not Working

Make sure you've published the config file and cleared the cache:
```bash
php artisan vendor:publish --tag="lmpcustomization-config"
php artisan config:clear
```
