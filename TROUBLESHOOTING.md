# Troubleshooting Guide

## Common Issues and Solutions

### 1. Resources Not Showing Up

**Problem**: After installing the package, the User and Parameter resources don't appear in your Filament panel.

**Solution**: You need to manually register the resources in your Filament panel provider.

1. Open `app/Providers/Filament/AdminPanelProvider.php`
2. Add the resource imports at the top:
```php
use Lampminds\Customization\Resources\ParameterResource;
use Lampminds\Customization\Resources\UserResource;
```

3. Add the resources to your panel configuration:
```php
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

### 2. "Class not found" Errors

**Problem**: Getting errors like `Class "Lampminds\Customization\Resources\Pages\ListUsers" not found`

**Solution**: This has been fixed in the latest version. Make sure you have the latest package version:

```bash
composer update lampminds/customization
```

### 3. Model Conflicts

**Problem**: Your application already has a User model that conflicts with the package's User model.

**Solution**: Use your own models by configuring them in `config/lmpcustomization.php`:

```php
return [
    'user_model' => \Lampminds\Customization\Models\User::class,
    'parameter_model' => \Lampminds\Customization\Models\Parameter::class,
];
```

Or via environment variables:
```env
LMP_USER_MODEL="App\\Models\\User"
LMP_PARAMETER_MODEL="App\\Models\\Parameter"
```

### 4. Navigation Not Customizing

**Problem**: Navigation groups, labels, or icons aren't changing as expected.

**Solution**: Make sure you've published the config file and cleared the cache:

```bash
php artisan vendor:publish --tag="lmpcustomization-config"
php artisan config:clear
```

Then update your configuration in `config/lmpcustomization.php`:

```php
return [
    'user_navigation_group' => 'My Custom Group',
    'user_navigation_sort' => 1,
    'parameter_navigation_group' => 'Settings',
    'parameter_navigation_sort' => 2,
];
```

### 5. Migration Issues

**Problem**: Migrations fail or tables already exist.

**Solution**: 

1. Check if you have conflicting migrations:
```bash
php artisan migrate:status
```

2. If you have existing users table, you might need to modify the migration:
```bash
php artisan vendor:publish --tag="lmpcustomization-migrations"
```

3. Then edit the migration file to add only the new columns you need.

### 6. Permission Issues

**Problem**: Users can't access the resources or get permission errors.

**Solution**: Make sure you have the required Spatie packages installed and configured:

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Then create roles and assign permissions:
```php
// Create roles
Role::create(['name' => 'admin']);
Role::create(['name' => 'user']);

// Assign roles to users
$user->assignRole('admin');
```

### 7. Custom Components Not Working

**Problem**: Custom form or table components aren't rendering properly.

**Solution**: Make sure you're using the correct component names and they're properly imported:

```php
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormTitle;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableTitle;

// In your resource
LmpFormTitle::make('Name', 'name'),
LmpTableTitle::make('Name', 'name'),
```

### 8. Cache Issues

**Problem**: Changes to configuration or resources aren't taking effect.

**Solution**: Clear all caches:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 9. Filament Panel Not Found

**Problem**: Resources are registered but don't appear in the correct panel.

**Solution**: Make sure you're registering the resources in the correct panel provider. If you have multiple panels, register them in the appropriate one:

```php
// For admin panel
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->resources([
            ParameterResource::class,
            UserResource::class,
        ]);
}
```

### 10. Still Having Issues?

If you're still experiencing problems:

1. **Check the logs**: Look at `storage/logs/laravel.log` for detailed error messages
2. **Verify installation**: Run `composer show lampminds/customization` to confirm the package is installed
3. **Check dependencies**: Ensure all required packages are installed:
   - `filament/filament`
   - `spatie/laravel-permission`
   - `spatie/laravel-medialibrary`
4. **Clear everything**: Run all clear commands and restart your development server
5. **Check Laravel version**: Ensure you're using Laravel 11+ and PHP 8.2+

## Getting Help

If none of these solutions work:

1. Check the [GitHub Issues](https://github.com/lampminds/customization/issues) for similar problems
2. Create a new issue with:
   - Your Laravel version
   - Your Filament version
   - The exact error message
   - Steps to reproduce the issue
