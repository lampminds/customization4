<?php
namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Filament\Resources\Pages\EditRecord;

class LmpEditRecord extends EditRecord
{
    // redirects to index list after creating a new record
    public function afterSave()
    {
    }

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
