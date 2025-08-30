<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormLink
{
    static function make(
        bool $allow_relative_urls = false,
        string $label = 'Link',
        string $name = ''
    ) : TextInput
    {
        if (!$allow_relative_urls) {
            // if urls are absolute, then use the std Filament validator for url()
            return TextInput::make($name == '' ? Str::snake($label) : $name)
                ->url()
                ->label(__($label))
                ->suffixIcon('heroicon-s-link')
                ->maxLength(255)
                ->helperText('Please enter a valid URL like https://...');
        } else {
            // if urls are relative, then use a custom validator
            return TextInput::make($name == '' ? Str::snake($label) : $name)
                ->label(__($label))
                ->regex('/^\/([a-zA-Z0-9\/._-]+)?$/')
                ->suffixIcon('heroicon-s-link')
                ->maxLength(255)
                ->helperText('Please enter a valid URL like https://...');
        }
    }
}
