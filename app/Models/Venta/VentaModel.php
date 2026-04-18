<?php

namespace App\Models\Venta;

use Illuminate\Database\Eloquent\Model;

class VentaModel extends Model
{
    protected $table = 'upbVentas';
    protected $primaryKey = 'venID';
    public $timestamps = true;

    protected $fillable = [
        'usuID',
        'venPersonaFisica',
        'venEstado',
        'venCantidadProductos',
        'venCostoFinal',
        'venEnvio',
    ];
}
