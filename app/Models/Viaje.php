<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $fillable = [
        'uuid',
        'pasajero_id',
        'centro_costo_id',
        'empresa_id',
        'origen',
        'destino',
        'fecha_inicio',
        'fecha_fin',
        'monto',
        'distancia',
        'estado',
        'cierre_facturacion_id'
    ];

    public function cierreFacturacion()
    {
        return $this->belongsTo(CierreFacturacion::class);
    }

    public function pasajero()
    {
        return $this->belongsTo(Pasajero::class);
    }

    public function centroCosto()
    {
        return $this->belongsTo(CentroCosto::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
