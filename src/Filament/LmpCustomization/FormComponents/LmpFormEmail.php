<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormEmail
{
    /**
     * Create a new email input.
     *
     * @param string $label
     * @param string $name
     * @return \Filament\Forms\Components\TextInput
     */
    static function make(string $label = 'Email', string $name = '') : TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->suffixIcon('heroicon-o-envelope')
            ->email()
            ->maxLength(150);
    }
}
