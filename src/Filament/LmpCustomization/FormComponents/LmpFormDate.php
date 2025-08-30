<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;

class LmpFormDate
{
    static function make(string $label = 'Date', string $name = '') : DatePicker
    {
        return DatePicker::make($name == '' ? Str::snake($label) : $name)
            ->native(false)
            ->live(onBlur: true)
            ->displayFormat(config('lmpcustomization.display_date_format'))
            ->format(config('lmpcustomization.database_date_format'))
            ->label(__($label));
    }
}
