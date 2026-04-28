<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['uuid', 'nombre', 'razon_social', 'cuenta_corriente', 'socio_id', 'ciclo_facturacion', 'moneda'];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function centrosCosto()
    {
        return $this->hasMany(CentroCosto::class);
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }
}
