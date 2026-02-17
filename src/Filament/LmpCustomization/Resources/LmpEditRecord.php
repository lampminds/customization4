<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class LmpEditRecord extends EditRecord
{
    /**
     * Default header actions for edit page: View and Delete.
     * Override getHeaderActions() in your page class to customize.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Empty hook - override in subclass to run logic after save.
     */
    protected function afterSave(): void
    {
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        $resource = static::getResource();

        if (method_exists($resource, 'getFormTitle')) {
            return $resource::getFormTitle($this->record);
        }

        return parent::getTitle(); // fallback to Filament default
    }

}
