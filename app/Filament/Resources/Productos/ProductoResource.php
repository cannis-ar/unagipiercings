<?php

namespace App\Filament\Resources\Productos;

use App\Filament\Resources\Productos\Pages\CreateProducto;
use App\Filament\Resources\Productos\Pages\EditProducto;
use App\Filament\Resources\Productos\Pages\ListProductos;
use App\Filament\Resources\Productos\Schemas\ProductoForm;
use App\Filament\Resources\Productos\Tables\ProductosTable;
use App\Models\Productos\ProductoModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductoResource extends Resource
{
    protected static ?string $model = ProductoModel::class;

    protected static ?string $slug = 'productos';

    protected static string|null|\UnitEnum $navigationGroup = 'Productos';
    protected static ?string $navigationLabel = 'Productos';

    protected static ?string $modelLabel = 'Producto';
    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?string $recordTitleAttribute = 'proNombre';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return ProductoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductosTable::configure($table);
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
            'index'  => ListProductos::route('/'),
            'create' => CreateProducto::route('/create'),
            'edit'   => EditProducto::route('/{record}/edit'),
        ];
    }
}
