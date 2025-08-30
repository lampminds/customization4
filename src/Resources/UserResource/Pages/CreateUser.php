<?php

namespace Lampminds\Customization\Resources\UserResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpCreateRecord;
use Lampminds\Customization\Resources\UserResource;

class CreateUser extends LmpCreateRecord
{
    protected static string $resource = UserResource::class;
}
