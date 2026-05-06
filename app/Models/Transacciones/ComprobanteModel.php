<?php

namespace App\Models\Transacciones;

use App\Models\Pedidos\PedidoModel;
use Illuminate\Database\Eloquent\Model;

class ComprobanteModel extends Model
{
    protected $table      = 'upbComprobantes';
    protected $primaryKey = 'comID';

    protected $fillable = ['pedID', 'opeID', 'comRuta', 'comFecPago'];

    protected $casts = [
        'comFecPago' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoModel::class, 'pedID', 'pedID');
    }

    public function operacion()
    {
        return $this->belongsTo(OperacionModel::class, 'opeID', 'opeID');
    }
}
