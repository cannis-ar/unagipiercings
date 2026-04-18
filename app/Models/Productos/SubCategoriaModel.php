<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Model;

class SubCategoriaModel extends Model
{
    protected $table = 'upbSubCategorias';
    protected $primaryKey = 'scaID';
    public $timestamps = true;

    protected $fillable = [
        'catID',
        'scaNombre',
        'scaDescripcion',
    ];

    /**
     * Categoría padre
     */
    public function categoria()
    {
        return $this->belongsTo(
            CategoriaModel::class,
            'catID',
            'catID'
        );
    }
}
