<?php
namespace Lampminds\Customization\Filament\LmpCustomization\FormComponents;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class LmpFormTimeStamp
{
    static function make(string $label, string $name = '') : TextInput
    {
        $name = $name ?: Str::snake(Str::lower($label));
        return TextInput::make($name)
            ->label(__($label))
            ->readOnly()
            ->dehydrated(false)
            ->formatStateUsing(fn ($state, $record) =>
            isset($record->$name)
                ? $record->$name->diffForHumans().' ['.
                $record->$name->format('M d, Y h:ia').']'
                : match($name) {
                    'email_verified_at' => 'Not verified',
                    'last_login_at' => 'Never logged in',
                    'last_seen_at' => 'Never seen',
                    default => 'N/A'
                })
            ->prefixIcon('heroicon-s-clock')
            ->hiddenOn(['create']);
    }
}
