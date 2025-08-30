<?php

namespace Lampminds\Customization\Resources\ParameterResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpListRecords;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Lampminds\Customization\Resources\ParameterResource;

class ListParameters extends LmpListRecords
{
    protected static string $resource = ParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->hidden(!Auth::user()->isAdmin()),
        ];
    }
}
