<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class LmpTableDate
{
    /**
     * Create a new TextColumn date instance.
     * If $show_human_format is true, it will show the human readable format of the date right below the date.
     *
     * @param string $label
     * @param bool $show_human_format
     * @param string $name
     * @return TextColumn
     */
    static function make(string $label = 'Date', bool $show_human_format = false, string $name = '') : TextColumn
    {
        $name = ($name == '' ? Str::snake($label) : $name);
        $human_name = Str::camel($name);

        return TextColumn::make($name)
            ->date()
            ->formatStateUsing(fn($state) => $state ? $state->format(config('lmpcustomization.display_date_format')) : '')
            ->sortable()
            ->searchable()
            ->label(__($label))
            ->description(function ($record) use ($show_human_format, $name) {
                return $record->{$name} && $show_human_format
                    ? $record->{$name}->diffForHumans()
                    : '';
            });

    }
}
