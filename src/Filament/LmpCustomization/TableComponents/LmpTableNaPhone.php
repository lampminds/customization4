<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class LmpTableNaPhone
{
    static function make(string $label = 'Phone', string $name = '') : TextColumn
    {
        $name = $name == '' ? Str::snake($label) : $name;
        return TextColumn::make($name)
            ->sortable()
            ->formatStateUsing(function ($record) use ($name) {
                return $record->$name
                    ? sprintf('(%s) %s-%s', substr($record->$name, 0, 3), substr($record->$name, 3, 3), substr($record->$name, 6, 4))
                    : 'N/A';
            })
            ->searchable();
    }
}
