<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;

class LmpTableCreatedBy
{
    static function make(string $label = 'Created by', string $name = 'created_by_nickname'): TextColumn
    {
        // try to use the translation if it exists
        if($label == 'Created by') {
            $label = __('Created by');
        }

        return TextColumn::make($name)
            ->label($label)
//            ->formatStateUsing(function ($state, $record) use ($name) {
//                return nickname($record->{$name});
//            })
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
