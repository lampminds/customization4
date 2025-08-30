<?php

namespace Lampminds\Customization\Resources\ParameterResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpEditRecord;
use Filament\Actions;
use Lampminds\Customization\Resources\ParameterResource;

class EditParameter extends LmpEditRecord
{
    protected static string $resource = ParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
