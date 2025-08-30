<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

class LmpFormTextArea
{
    static function make(string $label, string $name = '') : Textarea
    {
        return Textarea::make($name == '' ? Str::snake($label) : $name)
            ->label(__($label))
            ->columnSpan(2);
    }
}
