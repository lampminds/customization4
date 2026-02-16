<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class LmpViewRecord extends ViewRecord
{
    /**
     * Default header actions for view page: Edit and Delete.
     * Override getHeaderActions() in your page class to customize.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Redirects to index list after actions (edit, delete).
     */
    protected function getRedirectUrl(): string
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
