# Installing the Package Locally

Since this package is not published to Packagist, you need to install it from a local path or Git repository.

## Option 1: Install from Local Path

If the package is on the same machine or accessible via a path:

1. **Add repository to your project's `composer.json`**:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/lampminds-customization"
        }
    ],
    "require": {
        "lampminds/customization": "*"
    }
}
```

2. **Or use the command line**:

```bash
composer config repositories.lampminds-customization path /path/to/lampminds-customization
composer require lampminds/customization:@dev
```

## Option 2: Install from Git Repository

If the package is in a Git repository (GitHub, GitLab, etc.):

1. **Add repository to your project's `composer.json`**:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/your-username/lampminds-customization.git"
        }
    ],
    "require": {
        "lampminds/customization": "dev-master"
    }
}
```

2. **Or use the command line**:

```bash
composer config repositories.lampminds-customization vcs https://github.com/your-username/lampminds-customization.git
composer require lampminds/customization:dev-master
```

## Option 3: Install from Local Directory (Docker/Volume)

If you're using Docker and have the package in a volume:

1. **Mount the package directory** in your Docker container
2. **Add repository**:

```bash
composer config repositories.lampminds-customization path /path/to/mounted/package
composer require lampminds/customization:@dev
```

## Example: Docker Setup

If your package is in a volume at `/volume_packages/lampminds-customization`:

```bash
# In your project directory
composer config repositories.lampminds-customization path /volume_packages/lampminds-customization
composer require lampminds/customization:@dev
```

## After Installation

Once installed, follow the regular installation steps:

```bash
php artisan vendor:publish --tag="lmpcustomization-migrations"
php artisan migrate
php artisan vendor:publish --tag="lmpcustomization-config"
```

Then register the resources in your `AdminPanelProvider.php`:

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
        ]);
}
```

