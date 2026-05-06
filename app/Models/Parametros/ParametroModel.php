<?php

namespace App\Models\Parametros;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ParametroModel extends Model
{
    protected $table      = 'upbParametros';
    protected $primaryKey = 'parID';

    protected $fillable = ['parNombre', 'parValor'];

    public static function get(string $nombre): string
    {
        return Cache::remember('param_' . $nombre, 3600, function () use ($nombre) {
            return (string) static::where('parNombre', $nombre)->value('parValor');
        });
    }
}
