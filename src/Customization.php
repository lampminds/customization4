<?php

namespace Lampminds\Customization;

use Lampminds\Customization\Resources\ParameterResource;
use Lampminds\Customization\Resources\UserResource;

/**
 * Entry point for using the package in your Filament panel.
 * Register the package resources in one line: ->resources(Customization::resources())
 */
class Customization
{
    /**
     * Returns the list of Filament resource classes to register.
     * Respects config: enable_user_resource, enable_parameter_resource.
     */
    public static function resources(): array
    {
        $config = config('lmpcustomization', []);
        $resources = [];

        if ($config['enable_parameter_resource'] ?? true) {
            $resources[] = ParameterResource::class;
        }

        if ($config['enable_user_resource'] ?? true) {
            $resources[] = UserResource::class;
        }

        return $resources;
    }

    /**
     * All available resource classes (ignores config). Use when you want to register
     * and control visibility via Filament's canViewAny / etc.
     */
    public static function allResources(): array
    {
        return [
            ParameterResource::class,
            UserResource::class,
        ];
    }
}
