<?php

namespace Lampminds\Customization;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Facades\Gate;

class CustomizationServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('lmpcustomization')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }

    public function boot(): void
    {
        parent::boot();

        // Publish all package assets with a single tag (config + migrations + views)
        $publishArray = [
            __DIR__ . '/config/lmpcustomization.php' => config_path('lmpcustomization.php'),
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ];
        if (is_dir(__DIR__ . '/resources/views')) {
            $publishArray[__DIR__ . '/resources/views'] = resource_path('views/vendor/lmpcustomization');
        }
        $this->publishes($publishArray, 'lmpcustomization');

        // Register Filament resources if enabled in config
        $this->registerFilamentResources();

        // Register model bindings
        $this->registerModelBindings();

        // Register gates and policies
        $this->registerGates();
    }

    public function register(): void
    {
        parent::register();
        
        $this->mergeConfigFrom(
            __DIR__.'/config/lmpcustomization.php', 
            'lmpcustomization'
        );
    }

    protected function registerFilamentResources(): void
    {
        // For now, we'll rely on manual registration
        // This provides more control and avoids complex auto-registration issues
    }

    protected function registerModelBindings(): void
    {
        $config = config('lmpcustomization', []);
        
        // Allow customization of model classes
        if ($userModel = $config['user_model'] ?? null) {
            $this->app->bind(\Lampminds\Customization\Models\User::class, $userModel);
        }
        
        if ($parameterModel = $config['parameter_model'] ?? null) {
            $this->app->bind(\Lampminds\Customization\Models\Parameter::class, $parameterModel);
        }
    }

    protected function registerGates(): void
    {
        // Register custom gates for the package
        Gate::define('manage-parameters', function ($user) {
            return $user->canManageParameters();
        });
        
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}

