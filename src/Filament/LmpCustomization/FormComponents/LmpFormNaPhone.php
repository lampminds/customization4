<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormNaPhone
{
    /**
     * Create a new North American phone number input.
     * This format is valid for the United States and Canada.
     *
     * @param string $label
     * @param string $name
     * @return \Filament\Forms\Components\TextInput
     */
    static function make(string $label = 'Phone', string $name = '') : TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->suffixIcon('heroicon-o-phone')
            ->tel()
            ->mask('(999) 999-9999')
            ->placeholder('(123) 456-7890')
            ->maxLength(50);
    }
}
