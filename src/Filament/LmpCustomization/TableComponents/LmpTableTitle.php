<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class LmpTableTitle
{
    static function make(string $label = 'Title', string $name = '') : TextColumn
    {
        return TextColumn::make($name == '' ? Str::snake($label) : $name)
            ->sortable()
            ->wrap()
            ->words(8)
            ->searchable()
            ->label(__($label));
    }
}
