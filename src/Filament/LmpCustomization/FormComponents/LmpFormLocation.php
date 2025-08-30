<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormLocation
{
    static function make(string $label = 'Location', string $name = '') : TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label));
    }
}
