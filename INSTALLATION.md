# LMP Customization Package Installation

## Installation Steps

1. **Install the package** (if not already installed):
```bash
composer require lampminds/customization
```

2. **Publish and run migrations**:
```bash
php artisan vendor:publish --tag="lmpcustomization-migrations"
php artisan migrate
```

3. **Publish config file** (optional):
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

## Available Resources

- **ParameterResource**: Manage application parameters
- **UserResource**: Enhanced user management (optional)

## Available Components

The package provides custom Filament form and table components under the `Lampminds\Customization\Filament\LmpCustomization` namespace:

### Form Components
- `LmpFormTitle`, `LmpFormEmail`, `LmpFormToggle`
- `LmpFormCreatedByStamp`, `LmpFormUpdatedByStamp`
- `LmpFormRichEditor`, `LmpFormDate`, `LmpFormCurrency`
- And many more...

### Table Components  
- `LmpTableTitle`, `LmpTableToggle`, `LmpTableTimeStamp`
- `LmpTableCreatedByStamp`, `LmpTableUpdatedByStamp`
- `LmpTableCurrency`, `LmpTableDate`, `LmpTablePercentage`
- And many more...

## Models

- **Parameter**: `Lampminds\Customization\Models\Parameter`
- **User**: `Lampminds\Customization\Models\User` (enhanced with additional methods)

## Helper Functions

The package includes helper functions for common operations (available after installation):
- `getParameterValue()` - Get parameter values
- `nickname()` - Get user nicknames
- And more utility functions
