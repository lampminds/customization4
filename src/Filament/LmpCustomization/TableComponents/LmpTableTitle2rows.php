<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class LmpTableTitle2rows
{
    static function make(string $label, string $name1, string $name2) : TextColumn
    {
        return TextColumn::make($name1)
            ->description(function ($record) use ($name1, $name2) {
                return $record->{$name2};
            })
            ->sortable()
            ->wrap()
            ->words(8)
            ->searchable()
            ->label(__($label));
    }
}
