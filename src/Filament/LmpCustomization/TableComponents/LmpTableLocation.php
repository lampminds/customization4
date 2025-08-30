<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class LmpTableLocation
{
    static function make(string $label = 'Location', string $name = '') : TextColumn
    {
        return TextColumn::make($name == '' ? Str::snake($label) : $name)
            ->sortable()
            ->wrap()
            ->words(8)
            ->searchable()
            ->label(__($label));
    }
}
