<?php

namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class LmpTableCreatedByStamp
{
    static function make(bool $isToggleable = true, string $label = 'Created'): TextColumn
    {
        return TextColumn::make('created_by_nickname')
            ->label(__($label))
            ->formatStateUsing(function ($state, $record): HtmlString {
                $user = '<em><b> ' . $record->created_by_nickname . '</b></em>';
                if ($record->created_at) {
                    return new HtmlString($record->created_at->diffForHumans() . '<br>' .
                        $user . '<br>' .
                        localized_date($record->created_at) . '<br>' .
                        localized_time($record->created_at));
                } else {
                    return new HtmlString('(N/A)<br>' . $user);
                }
            })
            ->size(TextColumn\TextColumnSize::ExtraSmall)
            ->icon('heroicon-o-shield-check')
            ->alignment('center')
            ->toggleable($isToggleable);
    }
}
