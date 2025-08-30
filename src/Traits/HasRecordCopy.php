<?php

namespace Lampminds\Customization\Traits;

use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

trait HasRecordCopy
{
    /**
     * Get the copy record action for table rows
     */
    public static function getCopyRecordAction(): Action
    {
        return Action::make('copy')
            ->icon('heroicon-o-document-duplicate')
            ->label('Copy')
            ->action(function (Model $record) {
                // Get the record data and remove sensitive fields
                $recordData = $record->toArray();
                
                // Remove fields that shouldn't be copied
                $fieldsToRemove = [
                    'id',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                    'deleted_at',
                    'deleted_by',
                ];
                
                foreach ($fieldsToRemove as $field) {
                    unset($recordData[$field]);
                }
                
                // Store the data in session for pre-filling the form
                session()->put('copy_record_data', $recordData);
                session()->put('is_copying_record', true);
                
                // Redirect to create page
                return redirect()->to(static::getUrl('create'));
            });
    }
} 