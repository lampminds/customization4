<?php

namespace Lampminds\Customization\Filament\LmpCustomization\Resources;

use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormCreatedByStamp;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormUpdatedByStamp;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Support\Facades\Gate;

/**
 * Extension to the filament Resource class to allow for customizations
 *
 */
abstract class LmpResource extends Resource
{
    public static function form(Form $form): Form
    {
        // if the model has a property $dont_use_audit, then don't show the audit info
        if (!property_exists(static::getModel(), 'dont_use_audit')) {
            return $form->schema(array_merge(
                static::getMainFormSchema($form),
                static::getAuditFooterSchema()
            ));
        } else {
            return $form->schema(static::getMainFormSchema($form));
        }
    }

    // Your resource must define this
    abstract protected static function getMainFormSchema(Form $form): array;

    protected static function getAuditFooterSchema(): array
    {
        return [
            Section::make(__('Audit Info'))
                ->collapsed()
                ->schema([
                    LmpFormCreatedByStamp::make()
                        ->label(__('Created by')),
                    LmpFormUpdatedByStamp::make()
                        ->label(__('Updated by')),
                ])->columns(2)->hiddenOn('create'),
        ];
    }

    /**
     * Use: in the table() method call it as follows:
     *  parent::table($table)
     * This way you can add customizations to the table
     *
     * @param Tables\Table $table
     * @return Tables\Table
     */
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            // action button always located before the columns
            ->actionsPosition(position: ActionsPosition::BeforeColumns)
            // action button is the 3 dots icon: under it, see tableActions()
            ->actions(
                ActionGroup::make(static::tableActions()))
            // filters do not change while logged in
            ->persistFiltersInSession()
            // filters are located above the table content
            ->filtersLayout(FiltersLayout::AboveContent)
            // default bulk actions - can be overridden
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ;
    }

    /**
     * This function returns the typical actions that will be available in the table
     *
     * @param array $actions
     * @return array
     */
    public static function tableActions(array $actions = ['view', 'edit', 'delete']): array
    {
        $array = [];
        if (in_array('view', $actions)) {
            $array[] = static::viewAction();
        }
        if (in_array('edit', $actions)) {
            $array[] = static::editAction();
        }
        if (in_array('delete', $actions)) {
            $array[] = static::deleteAction();
        }
        return $array;
    }

    public static function viewAction(): ViewAction
    {
        return ViewAction::make()->label(__('View'))->icon('heroicon-o-eye');
    }

    public static function editAction(): EditAction
    {
        return EditAction::make()->label(__('Edit'))->icon('heroicon-o-pencil');
    }

    public static function deleteAction(): DeleteAction
    {
        return DeleteAction::make()->label(__('Delete'))->icon('heroicon-o-trash');
    }

    /**
     * This function verifies that the current user is allowed to reorder this resource rows
     *
     * @return bool
     */
    public static function canReorder(): bool
    {
/*        $modelClass = static::getModel(); // Get the model associated with the resource

        if (!class_exists($modelClass)) {
            return false; // Safety check
        }

        $user = auth()->user();

        // Call the model policy method dynamically
        return Gate::allows('update', app($modelClass));*/
        return true;
    }

    /**
     * Override the global search result URL to default to 'view' instead of 'edit'
     * This makes clicking on global search results open the view page by default
     */
    public static function getGlobalSearchResultUrl(\Illuminate\Database\Eloquent\Model $record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }
}
