<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    protected $fillable = ['centro_costo_id', 'nombre_completo', 'documento', 'telefono'];

    public function centroCosto()
    {
        return $this->belongsTo(CentroCosto::class);
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }
}
