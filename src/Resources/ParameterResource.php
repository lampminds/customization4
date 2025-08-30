<?php

namespace Lampminds\Customization\Resources;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormCreatedByStamp;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormRichEditor;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormUpdatedByStamp;
use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpResource;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableCreatedByStamp;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableTitle;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableUpdatedByStamp;
use Lampminds\Customization\Models\Parameter;
use Lampminds\Customization\Resources\ParameterResource\Pages\CreateParameter;
use Lampminds\Customization\Resources\ParameterResource\Pages\EditParameter;
use Lampminds\Customization\Resources\ParameterResource\Pages\ListParameters;
use Lampminds\Customization\Resources\ParameterResource\Pages\ViewParameter;
use Lampminds\Customization\Traits\HasRecordCopy;

class ParameterResource extends LmpResource
{
    use HasRecordCopy;

    protected static ?string $model = Parameter::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Parameters');
    }

    public static function getModelLabel(): string
    {
        return __('Parameter');
    }

    public static function getFormTitle($record): string
    {
        return $record->code . ' [' . $record->category . '] ';
    }

    // overwrite the default query for this resource
//    public static function getEloquentQuery(): Builder
//    {
//        return parent::getEloquentQuery()
//            ->when(!auth()->user()->hasRole('admin'), function (Builder $query) {
//                $query->where('account_id', Auth::user()->account_id)
//                    ->where('mode_id', '!=', 2);
//            });
//    }

    public static function getMainFormSchema(Form $form): array
    {
        return [Section::make('')
            ->schema([
                TextInput::make('category')
                    ->label(__('Category'))
                    ->required()
                    ->maxLength(50)
                    ->disabled(!Auth::user()->isAdmin()),

                TextInput::make('code')
                    ->label(__('Code'))
                    ->required()
                    ->maxLength(200)
                    ->disabled(!Auth::user()->isAdmin()),

                Select::make('type_id')
                    ->label(__('Type'))
                    ->options(Parameter::TYPES)
                    ->disabled(!Auth::user()->isAdmin())
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Get the current value from the hidden field
                        $currentValue = $get('value');

                        // Clear all typed value fields first
                        $set('value_string', null);
                        $set('value_integer', null);
                        $set('value_boolean', null);
                        $set('value_date', null);
                        $set('value_datetime', null);
                        $set('value_time', null);
                        $set('value_text', null);

                        // Set the appropriate field based on the new type
                        if ($currentValue !== null) {
                            match($state) {
                                0, 6 => $set('value_string', $currentValue),
                                1 => $set('value_integer', $currentValue),
                                2 => $set('value_boolean', $currentValue),
                                3 => $set('value_date', $currentValue),
                                4 => $set('value_datetime', $currentValue),
                                5 => $set('value_time', $currentValue),
                                7 => $set('value_text', $currentValue),
                                default => null,
                            };
                        }
                    })
                    ->required(),

                ///////////////////////////////////////////////

                // 0:STRING - 6:TIMESTAMP
                TextInput::make('value_string')
                    ->label(__('Value'))
                    ->live()
                    ->hidden(function (Get $get) {
                        return $get('type_id') != 0 && $get('type_id') != 6;
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('value', $state);
                    })
                    ->dehydrated(false)
                    ->maxLength(200),

                // 1:INTEGER
                TextInput::make('value_integer')
                    ->label(__('Value'))
                    ->live()
                    ->numeric()
                    ->hidden(function (Get $get) {
                        return $get('type_id') != 1;
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('value', $state);
                    })
                    ->dehydrated(false),

                // 2:BOOLEAN
                Radio::make('value_boolean')
                    ->label(__('Value'))
                    ->live()
                    ->hidden(function (Get $get) {
                        return $get('type_id') != 2;
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('value', $state);
                    })
                    ->dehydrated(false)
                    ->options([
                        'Y' => __('Yes'),
                        'N' => __('No'),
                    ]),

                // 3:DATE
                 DatePicker::make('value_date')
                     ->label(__('value'))
                     ->live()
                     ->hidden(function (Get $get) {
                         return $get('type_id') != 3;
                     })
                     ->afterStateUpdated(function ($state, callable $set) {
                         $set('value', $state);
                     })
                     ->dehydrated(false),

                // 4:DATETIME
                 DateTimePicker::make('value_datetime')
                     ->label(__('value'))
                     ->live()
                     ->hidden(function (Get $get) {
                         return $get('type_id') != 4;
                     })
                     ->afterStateUpdated(function ($state, callable $set) {
                         $set('value', $state);
                     })
                     ->dehydrated(false),

                // 5:TIME
                TimePicker::make('value_time')
                     ->label(__('value'))
                     ->live()
                     ->hidden(function (Get $get) {
                         return $get('type_id') != 5;
                     })
                     ->afterStateUpdated(function ($state, callable $set) {
                         $set('value', $state);
                     })
                     ->dehydrated(false),

                // 7:TEXT
                LmpFormRichEditor::make('value_text')
                    ->label(__('Value'))
                    ->live()
                    ->hidden(function (Get $get) {
                        return $get('type_id') != 7;
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('value', $state);
                    })
                    ->dehydrated(false)
                    ->maxLength(65535),

                // Hidden field to store the actual value
                \Filament\Forms\Components\Hidden::make('value')
                    ->afterStateHydrated(function ($component, $state, $record, callable $set) {
                        if ($record && $record->value !== null && $record->type_id !== null) {
                            // Set the appropriate typed field based on the record's type
                            match($record->type_id) {
                                0, 6 => $set('value_string', $record->value),
                                1 => $set('value_integer', $record->value),
                                2 => $set('value_boolean', $record->value),
                                3 => $set('value_date', $record->value),
                                4 => $set('value_datetime', $record->value),
                                5 => $set('value_time', $record->value),
                                7 => $set('value_text', $record->value),
                                default => null,
                            };
                        }
                    }),

                ///////////////////////////////////////////////

                Select::make('mode_id')
                    ->label(__('Mode'))
                    ->options(Parameter::MODES)
                    ->required()
                    ->disabled(!Auth::user()->isAdmin()),

                LmpFormRichEditor::make('help')
                    ->label(__('Help'))
                    ->maxLength(65535)
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('parameters')
                    ->preserveFilenames()
                    ->placeholder(__('Add a file (optional)'))
                    ->collection('parameters')
                    ->multiple()
                    ->label(__('File'))
                    ->disk('parameters'),

                LmpFormCreatedByStamp::make(),
                LmpFormUpdatedByStamp::make(),
            ])->columns(3)
        ];
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(function (Builder $query) {
                if (!Auth::user()->isAdmin()) {
                    $query->where('mode_id', '!=', 2);
                }
            })
            ->columns([
                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable()
                    ->hidden(fn() => !Auth::user()->isAdmin()),

                LmpTableTitle::make('value')
                    ->label(__('Value'))
                    ->wrap(),

                // show the media file, if any, using imagecolumn
                Tables\Columns\ImageColumn::make('parameters')
                    ->label(__('File'))
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('parameters', 'preview'))
                    ->size(50),

                LmpTableTitle::make('help')
                    ->label(__('Help'))
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->html(),

                TextColumn::make('type_id')
                    ->formatStateUsing(fn($state) => __(Parameter::TYPES[$state] ?? $state))
                    ->hidden(fn() => !Auth::user()->isAdmin())
                    ->toggleable()
                    ->label(__('Type')),

                TextColumn::make('mode_id')
                    ->formatStateUsing(fn($state) => __(Parameter::MODES[$state] ?? $state))
                    ->hidden(fn() => !Auth::user()->isAdmin())
                    ->toggleable()
                    ->label(__('Mode')),

                LmpTableCreatedByStamp::make()->toggleable(isToggledHiddenByDefault: true),
                LmpTableUpdatedByStamp::make()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('category')
                    ->form([
                        Forms\Components\Select::make('category')
                            ->label(__('category'))
                            ->options(Parameter::distinct()
                                ->orderBy('category')
                                ->pluck('category', 'category')
                                ->toArray())
                            ->placeholder(__('category')),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $data['category'] ? $query->where('category', $data['category']) : $query),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)

            ->actions(ActionGroup::make([
                LmpResource::viewAction(),
                LmpResource::editAction()
                    ->visible(function () {
                        return Auth::user()->canManageParameters();
                    }),
                static::getCopyRecordAction(),
                LmpResource::deleteAction()
                    ->visible(function () {
                        return Auth::user()->canManageParameters();
                    }),
            ]))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListParameters::route('/'),
            'create' => CreateParameter::route('/create'),
            'view' => ViewParameter::route('/{record}'),
            'edit' => EditParameter::route('/{record}/edit'),
        ];
    }
}
