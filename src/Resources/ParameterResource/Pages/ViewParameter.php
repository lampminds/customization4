<?php

namespace Lampminds\Customization\Resources\ParameterResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpViewRecord;
use Filament\Actions;
use Lampminds\Customization\Resources\ParameterResource;

class ViewParameter extends LmpViewRecord
{
    protected static string $resource = ParameterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
