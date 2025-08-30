<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormArPhone
{
    /**
     * Create a new Argentinian phone number input.
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
            ->mask(getParameterValue('PHONE_MASK'))
            ->placeholder(getParameterValue('PHONE_MASK'))
            ->maxLength(50)
            ->dehydrateStateUsing(fn ($state) => preg_replace('/\D+/', '', $state));
    }
}
