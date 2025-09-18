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

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
