<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    protected $table = 'centro_costos';
    protected $fillable = ['empresa_id', 'numero', 'nombre'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function pasajeros()
    {
        return $this->hasMany(Pasajero::class);
    }

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }
}
