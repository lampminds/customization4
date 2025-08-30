<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class LmpFormToggle
{
    static function make(string $label, string $name = '') : Toggle
    {
        return Toggle::make($name == '' ? Str::snake($label) : $name)
            ->inline(false)
            ->label(__($label));
    }
}
