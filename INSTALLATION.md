# LMP Customization Package Installation (Filament 4.x)

## Quick Start

The package provides Filament resources that need to be manually registered in your panel provider.

## Installation Steps

1. **Install the package**:
```bash
composer require lampminds/customization
```

2. **Publish and run migrations**:
```bash
php artisan vendor:publish --tag="lmpcustomization-migrations"
php artisan migrate
```

3. **Publish config file** (recommended for customization):
```bash
php artisan vendor:publish --tag="lmpcustomization-config"
```

4. **Register resources in your Filament Panel**:

In your `app/Providers/Filament/AdminPanelProvider.php` (or your main panel provider), add the resources:

```php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Import the LMP Customization resources
use Lampminds\Customization\Resources\ParameterResource;
use Lampminds\Customization\Resources\UserResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            // Add LMP Customization resources here
            ->resources([
                ParameterResource::class,
                UserResource::class, // Only if you want to use the custom User resource
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

That's it! The resources will now appear in your Filament admin panel.

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

If you want to use your own User or Parameter models:

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

### Manual Registration (Alternative)

If you prefer manual control, you can disable auto-registration and register manually:

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

### Resources Not Showing Up

1. Check that the package is properly installed: `composer show lampminds/customization`
2. Verify the service provider is registered in `composer.json`
3. Check the configuration in `config/lmpcustomization.php`
4. Clear config cache: `php artisan config:clear`

### Model Conflicts

If you have model conflicts, use the model customization feature to bind your own models.

### Customization Not Working

Make sure you've published the config file and cleared the cache:
```bash
php artisan vendor:publish --tag="lmpcustomization-config"
php artisan config:clear
```
