<?php

namespace Lampminds\Customization\Resources\UserResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpListRecords;
use Filament\Actions;
use Lampminds\Customization\Resources\UserResource;

class ListUsers extends LmpListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
