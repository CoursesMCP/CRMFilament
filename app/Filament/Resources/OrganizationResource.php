<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Helpers\Filament\CommonFormInputs;
use App\Helpers\Filament\CommonColumns;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationGroup(): string
    {
        return __('Clients');
    }

    public static function getNavigationLabel(): string
    {
        return __('Organizations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Organization Information')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('Organization Name'))
                                    ->placeholder(__('Enter organization name'))
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label(__('Assigned to Seller'))
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label(__('Description'))
                            ->placeholder(__('Brief description of the organization'))
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Contact Information')
                    ->description('Communication details')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                CommonFormInputs::identificationNumber(fieldName: 'nit', label: 'NIT', placeholder: 'Enter identification number'),
                                CommonFormInputs::email(fieldName: 'email', label: 'Email Address', placeholder: 'contact@organization.com'),
                            ]),

                        Forms\Components\Grid::make()
                            ->schema([
                                CommonFormInputs::phoneNumber(fieldName: 'cellphone', label: 'Mobile Phone', placeholder: 'Enter phone number'),
                                CommonFormInputs::phoneNumber(fieldName: 'phone', label: 'Office Phone', placeholder: 'Enter phone number'),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CommonColumns::baseTextColumn(fieldName: 'name', label: 'Name'),
                CommonColumns::baseIconCopyableTextColumn(fieldName: 'nit', label: 'NIT', iconName: 'heroicon-o-identification'),
                CommonColumns::baseIconCopyableTextColumn(fieldName: 'cellphone', label: 'Cellphone', iconName: 'heroicon-o-phone'),
                CommonColumns::baseIconCopyableTextColumn(fieldName: 'phone', label: 'Phone', iconName: 'heroicon-o-phone'),
                CommonColumns::baseIconCopyableTextColumn(fieldName: 'email', label: 'Email', iconName: 'heroicon-o-envelope'),
                CommonColumns::baseTextColumn(fieldName: 'user.name', label: 'Assigned to'),
                CommonColumns::createdAt()
                    ->label('Registered On'),
                CommonColumns::updatedAt()
                    ->label('Last Updated'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->requiresConfirmation(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'view' => Pages\ViewOrganization::route('/{record}'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
