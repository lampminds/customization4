<?php

namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\TextInput;

class LmpFormUpdatedByStamp
{
    static function make(string $label = 'Updated'): TextInput
    {
        // Try to use translation, if available
        if ($label == 'Updated') {
            $label = __('Updated');
        }

        return TextInput::make('updated_by_nickname')
            ->label(__($label))
            ->readOnly()
            ->formatStateUsing(fn($state, $record) =>
            isset($record->updated_at)
                ? sprintf(
                    __('%s by %s [%s %s]'),
                    $record->updated_at->diffForHumans(),
                    $record->updated_by_nickname,
                    localized_date($record->updated_at),
                    localized_time($record->updated_at)
                )
                : 'N/A')
            ->prefixIcon('heroicon-s-clock')
            ->suffixIcon('heroicon-o-user')
            ->hiddenOn(['create'])
            ->disabledOn(['edit']);
    }
}
