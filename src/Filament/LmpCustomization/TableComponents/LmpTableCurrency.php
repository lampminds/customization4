<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LmpTableCurrency
{
    static function make($valueOrClosure, string $label = 'Price', string $name = ''): TextColumn
    {
        if ($name === '' && is_string($valueOrClosure)) {
            $name = Str::snake($label);
        }

        return TextColumn::make($name)
            ->sortable()
            ->getStateUsing(function (Model $record) use ($valueOrClosure, $name) {
                $value = is_callable($valueOrClosure)
                    ? $valueOrClosure($record)
                    : $record->{$valueOrClosure};
                return formatNumber($value, true);
            })
            ->alignRight()
            ->label(__($label));
    }
}
