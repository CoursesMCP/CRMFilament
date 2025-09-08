<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\DniTypeEnum;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Helpers\Filament\CommonColumns;
use App\Helpers\Filament\CommonFormInputs;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Personal Information - Main Section
                Forms\Components\Section::make('Personal Information')
                    ->description('Main user data')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Name'))
                                    ->required()
                                    ->placeholder(__('Enter name')),
                                Forms\Components\TextInput::make('last_name')
                                    ->label(__('Last Name'))
                                    ->placeholder(__('Enter last name')),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                CommonFormInputs::idTypeSelect(fieldName: 'dni_type', label: 'ID Type'),
                                CommonFormInputs::identificationNumber(fieldName: 'dni', label: 'DNI', placeholder: 'Enter ID number'),
                            ]),
                    ]),

                // Contact Information
                Forms\Components\Section::make('Contact Information')
                    ->description('Communication details')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                CommonFormInputs::email(fieldName: 'email', label: 'Email', placeholder: 'example@domain'),
                                CommonFormInputs::phoneNumber(fieldName: 'cellphone', label: 'Phone Number', placeholder: '1 555 123-4567'),
                            ]),
                    ]),

                // Account Settings
                Forms\Components\Section::make('Account Settings')
                    ->description('Access settings and preferences')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label(__('Active User'))
                            ->required()
                            ->default(true),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->label(__('Password'))
                                    ->password()
                                    ->revealable()
                                    ->required(function (string $operation): bool {
                                        return $operation === 'create';
                                    })
                                    ->dehydrated(fn($state) => filled($state))
                                    ->minLength(8)
                                    ->helperText(__('Minimum 8 characters')),
                                Forms\Components\TextInput::make('visits_per_day')
                                    ->label(__('Visits Per Day'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(10)
                                    ->suffix(__('visits')),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CommonColumns::baseTextColumn(fieldName: 'full_name', label: 'Full Name')
                    ->searchable(['name', 'last_name']),

                CommonColumns::baseIconCopyableTextColumn(fieldName: 'email', label: 'Email Address', iconName: 'heroicon-o-envelope'),

                CommonColumns::baseIconCopyableTextColumn(fieldName: 'cellphone', label: 'Cellphone', iconName: 'heroicon-o-phone'),

                CommonColumns::baseTextColumn(fieldName: 'dni_type', label: 'ID Type')
                    ->badge(),

                CommonColumns::baseIconCopyableTextColumn(fieldName: 'dni', label: 'ID Number', iconName: 'heroicon-o-identification'),

                Tables\Columns\ToggleColumn::make('active'),

                CommonColumns::baseTextColumn(fieldName: 'visits_per_day', label: 'Visits Per Day')
                    ->numeric(),

                CommonColumns::createdAt(label: 'Registered On'),

                CommonColumns::updatedAt(label: 'Last Updated'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
