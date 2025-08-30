<?php
namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Filament\Forms\Components\View;
use Filament\Resources\Pages\ViewRecord;

class LmpViewRecord extends ViewRecord
{
    // redirects to index list after creating a new record
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
