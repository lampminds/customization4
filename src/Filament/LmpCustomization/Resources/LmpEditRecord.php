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
     * Redirects to index list after saving.
     */
    public function afterSave()
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
