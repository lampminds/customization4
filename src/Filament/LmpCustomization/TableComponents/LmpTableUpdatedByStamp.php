<?php

namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class LmpTableUpdatedByStamp
{
    static function make(string $label = 'Updated'): TextColumn
    {
        // Try to use the translation if available
        if ($label == 'Updated') {
            $label = __('Updated');
        }

        return TextColumn::make('updated_by_nickname')
            ->label(__($label))
            ->formatStateUsing(function ($state, $record): HtmlString {
                $user = '<em><b> ' . $record->updated_by_nickname . '</b></em>';
                if ($record->updated_at) {
                    return new HtmlString($record->updated_at->diffForHumans() . '<br>' .
                        $user . '<br>' .
                        localized_date($record->updated_at) . '<br>' .
                        localized_time($record->updated_at));
                } else {
                    return new HtmlString('(N/A)<br>' . $user);
                }
            })
            ->size(TextColumn\TextColumnSize::ExtraSmall)
            ->icon('heroicon-o-shield-check')
            ->alignment('center')
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
