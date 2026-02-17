<?php
namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class LmpCreateRecord extends CreateRecord
{
    // redirects to index list after creating a new record
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    /**
     * Pre-fill form with copied record data if available
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Check if we have copy data in session
        if (session()->has('copy_record_data')) {
            $copyData = session()->get('copy_record_data');
            session()->forget('copy_record_data'); // Clear session data
            session()->forget('is_copying_record'); // Clear copying flag

            // Merge copy data with any existing form data
            $data = array_merge($copyData, $data);
        }

        return $data;
    }

    /**
     * Override mount to set initial form data if copying
     */
    public function mount(): void
    {
        parent::mount();

        // If we have copy data, pre-fill the form
        if (session()->has('copy_record_data')) {
            $copyData = session()->get('copy_record_data');
            $this->form->fill($copyData);

            // Show notification that we're copying
            Notification::make()
                ->title('Copying Record')
                ->body('This form has been pre-filled with data from an existing record. You can modify it before saving.')
                ->info()
                ->persistent()
                ->send();
        }
    }

}
