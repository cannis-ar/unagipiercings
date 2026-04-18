<?php

namespace App\Filament\Resources\Materiales;

use App\Filament\Resources\Materiales\Schemas\MaterialForm;
use App\Filament\Resources\Materiales\Tables\MaterialsTable;
use App\Filament\Resources\Materiales\Pages\CreateMaterial;
use App\Filament\Resources\Materiales\Pages\EditMaterial;
use App\Filament\Resources\Materiales\Pages\ListMaterials;
use App\Models\Productos\MaterialModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = MaterialModel::class;

    protected static ?string $slug = 'materiales';

    protected static string|null|\UnitEnum $navigationGroup = 'Productos';
    protected static ?string $navigationLabel = 'Materiales';

    protected static ?string $modelLabel = 'Material';
    protected static ?string $pluralModelLabel = 'Materiales';

    protected static ?string $recordTitleAttribute = 'matNombre';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedBeaker;


    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
