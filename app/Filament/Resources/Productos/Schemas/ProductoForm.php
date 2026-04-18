<?php

namespace App\Filament\Resources\Productos\Schemas;

use App\Models\Productos\CategoriaModel;
use App\Models\Productos\MaterialModel;
use App\Models\Productos\SubCategoriaModel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            /** CATEGORÍA */
            Select::make('catID')
                ->label('Categoría')
                ->options(
                    CategoriaModel::query()
                        ->orderBy('catNombre')
                        ->pluck('catNombre', 'catID')
                        ->toArray()
                )
                ->required()
                ->live() // 🔥 clave en v4
                ->afterStateUpdated(function (callable $set) {
                    $set('scaID', null);
                }),

            /** SUBCATEGORÍA */
            Select::make('scaID')
                ->label('Subcategoría')
                ->reactive() // 🔥 clave
                ->options(function (callable $get) {
                    $catID = $get('catID');

                    if (! $catID) {
                        return [];
                    }

                    return SubCategoriaModel::query()
                        ->where('catID', $catID)
                        ->orderBy('scaNombre')
                        ->pluck('scaNombre', 'scaID')
                        ->toArray();
                })
                ->required()
                ->visible(fn (callable $get) => (bool) $get('catID')),

            Select::make('matID')
                ->label('Material')
                ->relationship(
                    name: 'material',
                    titleAttribute: 'matNombre'
                )
                ->required(),

            TextInput::make('proNombre')
                ->label('Nombre')
                ->required()
                ->maxLength(150),

            Textarea::make('proDescripcion')
                ->label('Descripción')
                ->rows(3)
                ->columnSpanFull(),

            FileUpload::make('proImagen')
                ->label('Imagen')
                ->image()
                ->disk('public')
                ->directory('productos')
                ->columnSpanFull(),

            TextInput::make('proGrosor')
                ->label('Grosor (mm)')
                ->numeric(),

            TextInput::make('proLargo')
                ->label('Largo (mm)')
                ->numeric(),

            TextInput::make('proTopTamano')
                ->label('Tamaño Top (mm)')
                ->numeric(),

            TextInput::make('proEsferaTamano')
                ->label('Tamaño Esfera (mm)')
                ->numeric(),

            TextInput::make('proDiametro')
                ->label('Diámetro (mm)')
                ->numeric(),

            TextInput::make('proTipoCierre')
                ->label('Tipo de cierre')
                ->maxLength(50),

            TextInput::make('proPrecio')
                ->label('Precio')
                ->numeric()
                ->required(),

            TextInput::make('proPorcentajeDescuento')
                ->label('Descuento (%)')
                ->numeric()
                ->default(0),

            TextInput::make('proStock')
                ->label('Stock')
                ->numeric()
                ->required(),
        ]);
    }
}
