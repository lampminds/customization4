<?php

namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;

class LmpFormCreatedByStamp
{
    static function make(string $label = 'Created'): TextInput
    {
        // Try to use translation, if available
        if ($label == 'Created') {
            $label = __('Created');
        }

        return TextInput::make('created_by_nickname')
            ->label(__($label))
            ->readOnly()
            ->formatStateUsing(fn($state, $record) =>
            isset($record->created_at)
                ? sprintf(
                    __('%s by %s [%s %s]'),
                    $record->created_at->diffForHumans(),
                    $record->created_by_nickname,
                localized_date($record->created_at),
                localized_time($record->created_at)
                )
                : 'N/A')
            ->prefixIcon('heroicon-s-clock')
            ->suffixIcon('heroicon-o-user')
            ->hiddenOn(['create'])
            ->disabledOn(['edit']);
    }
}
