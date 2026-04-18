<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    protected $table = 'upbStock';
    protected $primaryKey = 'stoID';
    public $timestamps = true;

    protected $fillable = [
        'proID',
        'stoTipo',
        'stoCanal',
        'stoViejaCantidad',
        'stoNuevaCantidad',
    ];
}
