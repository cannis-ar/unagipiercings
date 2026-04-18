<?php

namespace App\Filament\Resources\Categorias\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CategoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('catNombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(100),

                Textarea::make('catDescripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
