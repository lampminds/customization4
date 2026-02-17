<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class LmpTableTimeStamp
{
    static function make(string $label, string $name = '') : TextColumn
    {
        $name = $name ?: Str::snake(Str::lower($label));
        return TextColumn::make($name)
            ->formatStateUsing(function($state, $record) use ($name) : HtmlString {
                return isset($record->$name)
                    ? new HtmlString($record->$name->diffForHumans().'<br>'.
                        $record->$name->format('M d, Y').'<br>'.
                        $record->$name->format('h:ia'))
                    : new HtmlString('N/A');
            })
            ->size(TextSize::ExtraSmall)
            ->alignment('center')
            ->sortable()
            ->searchable()
            ->label(__($label));
    }
}
