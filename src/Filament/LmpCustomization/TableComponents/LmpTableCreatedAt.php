<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;

class LmpTableCreatedAt
{
    static function make(string $label = 'Created At', string $name = 'created_at') : TextColumn
    {
        // Try to use the translation if available
        if($label == 'Created At') {
            $label = __('Created At');
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
