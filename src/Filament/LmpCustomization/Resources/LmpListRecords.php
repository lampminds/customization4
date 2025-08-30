<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Asmit\ResizedColumn\HasResizableColumn;
use Lampminds\Customization\Filament\LmpCustomization\Traits\HasFilteredRecordCount;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class LmpListRecords extends ListRecords
{
    use HasFilteredRecordCount;

    /**
     * Whether to show the filtered record count in header actions
     * Override this property in your resource to control visibility
     */
    protected bool $showFilteredRecordCount = true;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        if ($this->showFilteredRecordCount) {
            $actions[] = $this->getRecordCountAction();
        }

        return $actions;
    }
}
