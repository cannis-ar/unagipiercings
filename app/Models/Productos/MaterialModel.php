<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Model;

class MaterialModel extends Model
{
    protected $table = 'upbMateriales';
    protected $primaryKey = 'matID';
    public $timestamps = true;

    protected $fillable = [
        'matNombre',
        'matDescripcion',
        'matCuidados',
    ];
}
