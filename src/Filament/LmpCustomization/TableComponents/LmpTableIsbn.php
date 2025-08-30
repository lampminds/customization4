<?php

namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormIsbn;

class LmpTableIsbn
{
    /**
     * Create a formatted ISBN table column
     *
     * @param string $label The label for the column
     * @param string $name The field name
     * @return TextColumn
     */
    public static function make(string $label, string $name): TextColumn
    {
        return TextColumn::make($name)
            ->label(__($label))
            ->formatStateUsing(function ($state) {
                if (empty($state)) {
                    return '—';
                }

                // Display the ISBN exactly as stored (with user's dashes)
                return $state;
            })
            ->searchable()
            ->sortable()
            ->copyable()
            ->tooltip(function ($state) {
                if (empty($state)) {
                    return null;
                }

                // Show the clean version (digits only) in tooltip
                $cleaned = preg_replace('/[^0-9X]/i', '', $state);
                return "Digits only: {$cleaned}";
            });
    }
}
