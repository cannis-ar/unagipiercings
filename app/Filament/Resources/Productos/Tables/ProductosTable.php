<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proID')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('proNombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('categoria.catNombre')
                    ->label('Categoría')
                    ->sortable(),

                Tables\Columns\TextColumn::make('material.matNombre')
                    ->label('Material')
                    ->sortable(),

                Tables\Columns\TextColumn::make('proPrecio')
                    ->label('Precio')
                    ->money('ARS'),

                Tables\Columns\TextColumn::make('proStock')
                    ->label('Stock')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('proID', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
