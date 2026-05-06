<?php

namespace App\Models\Transacciones;

use App\Models\Pedidos\PedidoModel;
use App\Models\Usuario\UsuarioModel;
use Illuminate\Database\Eloquent\Model;

class OperacionModel extends Model
{
    protected $table      = 'upbOperaciones';
    protected $primaryKey = 'opeID';

    const ESTADO_CANCELADO = 'CA';
    const ESTADO_PENDIENTE = 'PE';
    const ESTADO_COMPLETA  = 'CO';

    const TIPO_MERCADOPAGO  = 'MP';
    const TIPO_TRANSFERENCIA = 'TR';

    protected $fillable = [
        'usuID', 'traID', 'pedID', 'comID',
        'opeEstado', 'opeTipo',
        'opeMonto', 'opeMontoComision', 'opeIVA', 'opeIIBB',
        'opeFecAlta', 'opeFecAcreditacion',
    ];

    protected $casts = [
        'opeFecAlta'         => 'datetime',
        'opeFecAcreditacion' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(UsuarioModel::class, 'usuID', 'usuID');
    }

    public function transaccion()
    {
        return $this->belongsTo(TransaccionModel::class, 'traID', 'traID');
    }

    public function pedido()
    {
        return $this->belongsTo(PedidoModel::class, 'pedID', 'pedID');
    }

    public function comprobante()
    {
        return $this->belongsTo(ComprobanteModel::class, 'comID', 'comID');
    }
}
