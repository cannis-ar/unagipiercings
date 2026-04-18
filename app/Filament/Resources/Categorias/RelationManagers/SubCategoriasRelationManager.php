<?php

namespace App\Filament\Resources\Categorias\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SubCategoriasRelationManager extends RelationManager
{
    protected static string $relationship = 'subcategorias';

    protected static ?string $title = 'Subcategorías';

    protected static ?string $modelLabel = 'Subcategoría';
    protected static ?string $pluralModelLabel = 'Subcategorías';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('scaNombre')
                ->label('Nombre')
                ->required()
                ->maxLength(100),

            Textarea::make('scaDescripcion')
                ->label('Descripción')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scaID')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scaNombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
