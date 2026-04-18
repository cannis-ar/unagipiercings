<?php

namespace App\Models\Venta;

use Illuminate\Database\Eloquent\Model;

class VentaProductoModel extends Model
{
    protected $table = 'upbVentaProductos';
    protected $primaryKey = 'vprID';
    public $timestamps = true;

    protected $fillable = [
        'venID',
        'proID',
        'vprCantidad',
        'vprCostoUnitario',
        'vprCostoFinal',
    ];
}
