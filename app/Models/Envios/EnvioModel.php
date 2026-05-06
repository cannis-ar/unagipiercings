<?php

namespace App\Models\Envios;

use App\Models\Pedidos\PedidoModel;
use Illuminate\Database\Eloquent\Model;

class EnvioModel extends Model
{
    protected $table      = 'upbEnvios';
    protected $primaryKey = 'envID';

    protected $fillable = ['pedID', 'envDireccion', 'envRetiro'];

    public function pedido()
    {
        return $this->belongsTo(PedidoModel::class, 'pedID', 'pedID');
    }
}
