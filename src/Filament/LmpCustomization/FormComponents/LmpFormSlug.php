<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormSlug
{
    static function make(string $label, string $name = '') : TextInput
    {
        return TextInput::make($name == '' ? Str::snake($label) : $name)
            ->helperText(__('Only lowercase letters, numbers, and hyphens are allowed.'))
            ->label(__($label))
            ->tel()
            ->telRegex('/^[a-z0-9\_\-]+$/');
    }
}
