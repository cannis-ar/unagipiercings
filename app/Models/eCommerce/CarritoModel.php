<?php

namespace App\Models\eCommerce;

use App\Models\Usuario\UsuarioModel;
use Illuminate\Database\Eloquent\Model;

class CarritoModel extends Model
{
    protected $table      = 'upbCarrito';
    protected $primaryKey = 'carID';

    const ESTADO_ACTIVO     = 'AC';
    const ESTADO_INACTIVO   = 'IN';
    const ESTADO_CONVERTIDO = 'CO';

    protected $fillable = [
        'usuID',
        'carToken',
        'carEstado',
        'carProductos',
        'carTotal',
    ];

    protected $casts = [
        'carProductos' => 'array',
    ];

    /* ---- Relaciones ---- */

    public function usuario()
    {
        return $this->belongsTo(UsuarioModel::class, 'usuID', 'usuID');
    }

    /* ---- Scopes ---- */

    public function scopeActivo($query)
    {
        return $query->where('carEstado', self::ESTADO_ACTIVO);
    }

    /* ---- Métodos de negocio ---- */

    public function calcularTotal(): float
    {
        if (!$this->carProductos) return 0.0;
        return (float) array_sum(array_column($this->carProductos, 'subtotal'));
    }

    public function agregarProducto(array $item): void
    {
        $items = $this->carProductos ?? [];
        $index = array_search($item['proID'], array_column($items, 'proID'));

        if ($index !== false) {
            $items[$index]['cantidad'] += $item['cantidad'];
            $items[$index]['subtotal']  = $items[$index]['precioPagado'] * $items[$index]['cantidad'];
        } else {
            $items[] = $item;
        }

        $this->carProductos = array_values($items);
        $this->carTotal     = $this->calcularTotal();
        $this->save();
    }

    public function quitarProducto(int $proID): void
    {
        $items = array_values(
            array_filter($this->carProductos ?? [], fn($i) => $i['proID'] !== $proID)
        );

        $this->carProductos = $items;
        $this->carTotal     = $this->calcularTotal();
        $this->save();
    }

    public function actualizarCantidad(int $proID, int $cantidad): void
    {
        $items = $this->carProductos ?? [];

        foreach ($items as &$item) {
            if ($item['proID'] === $proID) {
                $item['cantidad'] = $cantidad;
                $item['subtotal'] = $item['precioPagado'] * $cantidad;
                break;
            }
        }
        unset($item);

        $this->carProductos = $items;
        $this->carTotal     = $this->calcularTotal();
        $this->save();
    }
}
