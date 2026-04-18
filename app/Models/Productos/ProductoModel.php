<?php

namespace App\Models\Productos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductoModel extends Model
{
    protected $table = 'upbProductos';
    protected $primaryKey = 'proID';
    public $timestamps = true;

    protected $fillable = [
        'catID',
        'scaID',
        'matID',
        'proNombre',
        'proSlug',
        'proDescripcion',
        'proGrosor',
        'proLargo',
        'proTopTamano',
        'proEsferaTamano',
        'proTipoCierre',
        'proDiametro',
        'proImagen',
        'proPrecio',
        'proPorcentajeDescuento',
        'proStock',

        // nuevos
        'proDescuento',
        'proCuotas',
        'proDescuentoTransferencia',
        'proPorcentajeDescuentoTransferencia',
    ];

    protected $appends = [
        'precio_final',
        'precio_transferencia',
        'tiene_descuento',
        'permite_cuotas',
    ];

    public function getPrecioFinalAttribute()
    {
        if (!$this->proPrecio) {
            return null;
        }

        if ($this->tiene_descuento) {
            return round(
                $this->proPrecio * (1 - $this->proPorcentajeDescuento / 100)
            );
        }

        return $this->proPrecio;
    }

    public function getPrecioTransferenciaAttribute()
    {
        if (
            !$this->proPrecio ||
            !$this->proDescuentoTransferencia ||
            !$this->proPorcentajeDescuentoTransferencia
        ) {
            return null;
        }

        return round(
            $this->proPrecio * (1 - $this->proPorcentajeDescuentoTransferencia / 100)
        );
    }

    public function getPermiteCuotasAttribute()
    {
        return (bool) $this->proCuotas;
    }

    /**
     * Categoría del producto
     */
    public function categoria()
    {
        return $this->belongsTo(
            CategoriaModel::class,
            'catID',
            'catID'
        );
    }

    public function subcategoria()
    {
        return $this->belongsTo(SubCategoriaModel::class, 'scaID', 'scaID');
    }

    /**
     * Material del producto
     */
    public function material()
    {
        return $this->belongsTo(
            MaterialModel::class,
            'matID',
            'matID'
        );
    }

    public function getTieneDescuentoAttribute(): bool
    {
        return (bool) ($this->proPorcentajeDescuento && $this->proPorcentajeDescuento > 0);
    }

    // Accessor para generar slug al crear/editar:
    protected static function booted(): void
    {
        static::saving(function (self $producto) {
            if (!$producto->proSlug || $producto->isDirty('proNombre')) {
                $producto->proSlug = self::generateUniqueSlug($producto->proNombre, $producto->proID);
            }
        });
    }

    private static function generateUniqueSlug(string $nombre, ?int $exceptId = null): string
    {
        $base = Str::slug($nombre);
        $slug = $base;
        $i    = 1;

        while (
        self::where('proSlug', $slug)
            ->when($exceptId, fn($q) => $q->where('proID', '!=', $exceptId))
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}

