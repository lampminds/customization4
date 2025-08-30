<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;

class LmpTableUpdatedAt
{
    static function make(string $label = 'Updated At', string $name = 'updated_at'): TextColumn
    {
        // try to use the translation if it exists
        if($label == 'Updated At') {
            $label = __('Updated At');
        }
        return TextColumn::make($name)
            ->label($label)
            ->formatStateUsing(function ($state, $record) use ($name) {
                return $record->{$name}->format('M d, Y H:i');
            })
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
