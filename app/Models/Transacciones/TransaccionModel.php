<?php

namespace App\Models\Transacciones;

use App\Models\Pedidos\PedidoModel;
use Illuminate\Database\Eloquent\Model;

class TransaccionModel extends Model
{
    protected $table      = 'upbTransacciones';
    protected $primaryKey = 'traID';

    const FINANCIADOR_MERCADOPAGO = 'MP';

    const ESTADO_CANCELADA = 'CA';
    const ESTADO_PENDIENTE = 'PE';
    const ESTADO_COMPLETA  = 'CO';

    protected $fillable = [
        'pedID', 'traFinanciador', 'traEstado',
        'traMonto', 'traMontoComision', 'traIVA', 'traIIBB',
        'traFecAlta', 'traFecAcreditacion', 'traWebhook',
    ];

    protected $casts = [
        'traWebhook'          => 'array',
        'traFecAlta'          => 'datetime',
        'traFecAcreditacion'  => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoModel::class, 'pedID', 'pedID');
    }
}
