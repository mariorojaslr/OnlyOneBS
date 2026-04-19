<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $fillable = ['nombre', 'ciudad', 'uuid', 'parent_id', 'nivel', 'email', 'phone'];

    public function parent()
    {
        return $this->belongsTo(Socio::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Socio::class, 'parent_id');
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class);
    }
}
