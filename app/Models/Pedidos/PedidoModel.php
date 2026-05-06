<?php

namespace App\Models\Pedidos;

use App\Models\Envios\EnvioModel;
use App\Models\Parametros\ParametroModel;
use App\Models\Transacciones\ComprobanteModel;
use App\Models\Transacciones\OperacionModel;
use App\Models\Transacciones\TransaccionModel;
use App\Models\Usuario\UsuarioModel;
use Illuminate\Database\Eloquent\Model;

class PedidoModel extends Model
{
    protected $table      = 'upbPedidos';
    protected $primaryKey = 'pedID';

    const ESTADO_EN_PROCESO = 'PR';
    const ESTADO_CANCELADO  = 'CA';
    const ESTADO_PENDIENTE  = 'PE';
    const ESTADO_ABONADO    = 'AB';

    const PAGO_MERCADOPAGO  = 'MP';
    const PAGO_TRANSFERENCIA = 'TR';

    const ENTREGA_ENVIO_INTERNO    = 'EN';
    const ENTREGA_RETIRO           = 'RE';
    const ENTREGA_ENVIO_PROVINCIAL = 'EP';

    protected $fillable = [
        'usuID', 'opeID', 'traID', 'comID', 'envID',
        'pedEstado', 'pedPago', 'pedEntrega',
        'pedTotal', 'pedTotalTransferencia',
    ];

    public function usuario()
    {
        return $this->belongsTo(UsuarioModel::class, 'usuID', 'usuID');
    }

    public function operacion()
    {
        return $this->belongsTo(OperacionModel::class, 'opeID', 'opeID');
    }

    public function transaccion()
    {
        return $this->belongsTo(TransaccionModel::class, 'traID', 'traID');
    }

    public function comprobante()
    {
        return $this->belongsTo(ComprobanteModel::class, 'comID', 'comID');
    }

    public function envio()
    {
        return $this->belongsTo(EnvioModel::class, 'envID', 'envID');
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalleModel::class, 'pedID', 'pedID');
    }

    public function calcularTotalTransferencia(): float
    {
        $descuento = (float) ParametroModel::get('DESCUENTO_TRANSFERENCIA');
        return round($this->pedTotal * (1 - $descuento / 100), 2);
    }
}
