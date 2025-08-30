<?php

namespace Lampminds\Customization\Resources;

use Lampminds\Customization\Filament\LmpCustomization\Resources\LmpResource;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormTitle;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormEmail;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormToggle;
use Lampminds\Customization\Filament\LmpCustomization\FormComponents\LmpFormTimeStamp;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableTitle;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableToggle;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableTimeStamp;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableCreatedByStamp;
use Lampminds\Customization\Filament\LmpCustomization\TableComponents\LmpTableUpdatedByStamp;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Lampminds\Customization\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\Exceptions\Halt;

class UserResource extends LmpResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function getMainFormSchema(Form $form): array
    {
        return [
            Tabs::make('User Information')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Basic Information')
                        ->schema([
                            LmpFormTitle::make('Full Name', 'name'),

                            LmpFormEmail::make('Email Address', 'email'),

                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn (string $context): bool => $context === 'create')
                                ->helperText('Leave blank to keep current password when editing'),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Account Status')
                        ->schema([
                            LmpFormToggle::make('Kicked Out', 'kicked_out'),

                            LmpFormTimeStamp::make('Email Verified At'),
                            LmpFormTimeStamp::make('Last Login At'),
                            LmpFormTimeStamp::make('Last Seen At'),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Roles & Permissions')
                        ->schema([
                            CheckboxList::make('roles')
                                ->label('Roles')
                                ->relationship('roles', 'name')
                                ->options(Role::all()->pluck('name', 'id'))
                                ->descriptions(
                                    Role::all()->pluck('name', 'id')->mapWithKeys(function ($name, $id) {
                                        $role = Role::find($id);
                                        $permissionCount = $role ? $role->permissions()->count() : 0;
                                        return [$id => "{$permissionCount} permissions"];
                                    })->toArray()
                                )
                                ->searchable()
                                ->bulkToggleable()
                                ->gridDirection('row')
                                ->columns(2)
                                ->helperText('Select the roles this user should have'),

                            CheckboxList::make('permissions')
                                ->label('Direct Permissions')
                                ->relationship('permissions', 'name')
                                ->options(Permission::all()->pluck('name', 'id'))
                                ->searchable()
                                ->bulkToggleable()
                                ->gridDirection('row')
                                ->columns(3)
                                ->helperText('Select additional permissions this user should have directly (not through roles)'),
                        ])
                        ->columns(1),
                ]),
        ];
    }

    public static function getCreateOptionFormSchema(): array
    {
        return [
            LmpFormTitle::make('Full Name', 'name'),

            LmpFormEmail::make('Email', 'email'),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->minLength(8)
                ->default('password123')
                ->helperText('Default password: password123'),

        ];
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                LmpTableTitle::make('Name', 'name'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Verified' : 'Unverified')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('info')
                    ->separator(', ')
                    ->searchable()
                    ->toggleable(),

                LmpTableToggle::make('Kicked Out', 'kicked_out'),

                LmpTableTimeStamp::make('Last Login', 'last_login_at')
                    ->toggleable(),

                LmpTableTimeStamp::make('Last Seen', 'last_seen_at')
                    ->toggleable(),

                LmpTableCreatedByStamp::make(),

                LmpTableUpdatedByStamp::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->nullable(),

                TernaryFilter::make('kicked_out')
                    ->label('Kicked Out'),

                Filter::make('last_login_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('last_login_from')
                            ->label('Last Login From'),
                        \Filament\Forms\Components\DatePicker::make('last_login_until')
                            ->label('Last Login Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['last_login_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_login_at', '>=', $date),
                            )
                            ->when(
                                $data['last_login_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('last_login_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Email' => $record->email,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
