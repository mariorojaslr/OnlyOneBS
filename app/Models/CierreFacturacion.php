<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreFacturacion extends Model
{
    protected $fillable = [
        'empresa_id',
        'fecha_inicio',
        'fecha_fin',
        'monto_total',
        'moneda',
        'cantidad_viajes',
        'estado'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }
}
