<?php
namespace Lampminds\Customization\Filament\LmpCustomization\TableComponents;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

/**
 * Class LmpTableRelationCounter
 *
 * This class is used to create a column that counts the number of related records in a table.
 * @param string $label The label of the column.
 * @param string $relation_name The name of the relation that should be present in the model.
 *
 */
class LmpTableRelationCounter
{
    static function make(string $label, string $relation_name) : TextColumn
    {
        return TextColumn::make($relation_name. '_count')
            ->counts(Str::plural($relation_name))
            ->sortable()
            ->label(__($label));
    }
}
