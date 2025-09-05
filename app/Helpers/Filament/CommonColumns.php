<?php

namespace App\Helpers\Filament;

use Filament\Tables\Columns\TextColumn;

class CommonColumns
{
    public static function baseTextColumn(string $fieldName, string $label): TextColumn
    {
        return TextColumn::make($fieldName)
            ->label(__($label))
            ->searchable()
            ->sortable()
            ->toggleable();
    }

    public static function baseIconCopyableTextColumn(
        string $fieldName,
        string $label,
        string $iconName,
        string $copyMessage = 'Copied!',
        int $duration = 1500
    ): TextColumn {
        return static::baseTextColumn($fieldName, $label)
            ->icon($iconName)
            ->copyable()
            ->copyMessage(__($copyMessage))
            ->copyMessageDuration($duration);
    }

    public static function createdAt(string $label = 'Created At'): TextColumn
    {
        return static::baseTextColumn('created_at', $label)
            ->date('Y-m-d')
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function updatedAt(string $label = 'Updated At'): TextColumn
    {
        return static::baseTextColumn('updated_at', $label)
            ->since()
            ->dateTimeTooltip()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
