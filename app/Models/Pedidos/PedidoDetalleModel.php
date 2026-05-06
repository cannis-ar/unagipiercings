<?php

namespace App\Models\Pedidos;

use App\Models\Productos\ProductoModel;
use Illuminate\Database\Eloquent\Model;

class PedidoDetalleModel extends Model
{
    protected $table      = 'upbPedidosDetalles';
    protected $primaryKey = 'pdeID';

    protected $fillable = [
        'pedID', 'proID',
        'pdeNombre', 'pdePrecioUnitario', 'pdePrecioPagado',
        'pdePorcentajeDescuento', 'pdeCantidad', 'pdeSubtotal',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoModel::class, 'pedID', 'pedID');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoModel::class, 'proID', 'proID');
    }
}
