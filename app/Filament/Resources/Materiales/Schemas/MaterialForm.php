<?php

namespace App\Filament\Resources\Materiales\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('matNombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),

                Textarea::make('matDescripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),

                Textarea::make('matCuidados')
                    ->label('Cuidados')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
