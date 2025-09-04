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
                                Forms\Components\Select::make('dni_type')
                                    ->label(__('ID Type'))
                                    ->options(DniTypeEnum::keyValuesCombined())
                                    ->default(DniTypeEnum::CC->value)
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TextInput::make('dni')
                                    ->label(__('DNI'))
                                    ->placeholder(__('ID Number'))
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->numeric()
                                    ->maxLength(20),
                            ]),
                    ]),

                // Contact Information
                Forms\Components\Section::make('Contact Information')
                    ->description('Communication details')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label(__('Email'))
                                    ->placeholder(__('example@domain'))
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(70),
                                Forms\Components\TextInput::make('cellphone')
                                    ->label(__('Phone Number'))
                                    ->tel()
                                    ->prefix('+')
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder('1 555 123-4567'),
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
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('Full Name'))
                    ->searchable(['name', 'last_name'])
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email Address'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage(__('Copied!'))
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('cellphone')
                    ->label(__('Cellphone'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->copyMessage(__('Copied!'))
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('dni_type')
                    ->label(__('ID Type'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->badge(),

                Tables\Columns\TextColumn::make('dni')
                    ->label(__('ID Number'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->icon('heroicon-o-identification')
                    ->copyable()
                    ->copyMessage(__('Copied!'))
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('visits_per_day')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->searchable()
                    ->sortable()
                    ->date('Y-m-d')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->searchable()
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip()
                    ->toggleable(isToggledHiddenByDefault: true),
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
