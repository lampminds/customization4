<?php

namespace Lampminds\Customization\Resources\UserResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpEditRecord;
use Lampminds\Customization\Resources\UserResource;
use Filament\Actions;

class EditUser extends LmpEditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
