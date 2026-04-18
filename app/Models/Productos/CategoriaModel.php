<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Model;

class CategoriaModel extends Model
{
    protected $table = 'upbCategorias';
    protected $primaryKey = 'catID';
    public $timestamps = true;

    protected $fillable = [
        'catNombre',
        'catDescripcion',
    ];

    public function subcategorias()
    {
        return $this->hasMany(
            SubCategoriaModel::class,
            'catID',
            'catID'
        );
    }
}
