<?php

namespace Lampminds\Customization\Resources\UserResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpViewRecord;
use Lampminds\Customization\Resources\UserResource;
use Filament\Actions;

class ViewUser extends LmpViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
