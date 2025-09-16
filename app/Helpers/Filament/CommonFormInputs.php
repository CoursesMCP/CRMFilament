<?php

namespace App\Helpers\Filament;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Enums\DniTypeEnum;

class CommonFormInputs
{
    public static function identificationNumber(
        string $fieldName = 'identification_number',
        string $label = 'Identification Number',
        string $placeholder = 'Enter identification number',
        int $maxLength = 20
    ): TextInput {
        return TextInput::make($fieldName)
            ->label(__($label))
            ->placeholder(__($placeholder))
            ->required()
            ->unique(ignoreRecord: true)
            ->numeric()
            ->maxLength($maxLength);
    }

    public static function email(
        string $fieldName = 'email',
        string $label = 'Email',
        string $placeholder = 'example@domain.com',
        int $maxLength = 70
    ): TextInput {
        return TextInput::make($fieldName)
            ->label(__($label))
            ->placeholder(__($placeholder))
            ->email()
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength($maxLength);
    }

    public static function phoneNumber(
        string $fieldName = 'phone_number',
        string $label = 'Phone Number',
        string $placeholder = '1 555 123-4567',
    ): TextInput {
        return TextInput::make($fieldName)
            ->label(__($label))
            ->placeholder(__($placeholder))
            ->tel()
            ->prefix('+')
            ->required()
            ->unique(ignoreRecord: true)
            ->maxLength(20);
    }

    public static function idTypeSelect(
        string $fieldName = 'id_type',
        string $label = 'ID Type',
    ): Select {
        return Select::make($fieldName)
            ->label(__($label))
            ->options(DniTypeEnum::keyValuesCombined())
            ->default(DniTypeEnum::CC->value)
            ->searchable()
            ->required();
    }
}
