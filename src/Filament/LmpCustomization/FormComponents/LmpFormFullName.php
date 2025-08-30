<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormFullName
{
    static function make(string $label = 'Name', string $name = '') : TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->suffixIcon('heroicon-o-user')
            ->regex('/^[a-z0-9 ]+$/i','name')
            ->maxLength(150)
            ->required()
            ->dehydrateStateUsing(fn(string $state): string => ucwords(trim($state)))
            ->helperText(__('Full name, including any middle names.'));
    }
}
