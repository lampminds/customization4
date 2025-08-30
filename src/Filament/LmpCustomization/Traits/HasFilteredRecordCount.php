<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Traits;

use Filament\Actions\Action;
use Livewire\Attributes\Computed;

trait HasFilteredRecordCount
{
    /**
     * Get the count action for header actions
     */
    public function getRecordCountAction(): Action
    {
        return Action::make('recordCount')
            ->label(fn() => $this->getFilteredRecordCount())
            ->color('gray')
            ->icon('heroicon-o-calculator')
            ->disabled()
            ->extraAttributes([
                'class' => 'cursor-default',
                'style' => 'pointer-events: none;'
            ]);
    }

    /**
     * Get the count of records with current filters applied
     */
    #[Computed]
    public function getFilteredRecordCount(): string
    {
        $count = $this->getFilteredTableQuery()->count();
        $modelName = str(class_basename($this->getResource()::getModel()))->plural()->lower();
        return 'Total: ' . number_format($count) . ' ' . $modelName;
    }
}
