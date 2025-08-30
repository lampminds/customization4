<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LmpTablePercentage
{
    static function make($valueOrClosure, string $label = 'Percentage', string $name = '', $decimals = 0): TextColumn
    {
        if ($name === '' && is_string($valueOrClosure)) {
            $name = Str::snake($label);
        }

        return TextColumn::make($name)
            ->sortable()
            ->searchable()
            ->getStateUsing(function (Model $record) use ($valueOrClosure, $name, $decimals) {
                $value = is_callable($valueOrClosure)
                    ? $valueOrClosure($record)
                    : $record->{$valueOrClosure};

                return formatPercentage($value, $decimals);
            })
            ->alignRight()
            ->label(__($label));
    }
}
