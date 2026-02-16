<?php

namespace Lampminds\Customization\Resources\UserResource\Pages;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpListRecords;
use Lampminds\Customization\Resources\UserResource;

class ListUsers extends LmpListRecords
{
    protected static string $resource = UserResource::class;
}
