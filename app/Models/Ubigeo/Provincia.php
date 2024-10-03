<?php

namespace App\Models\Ubigeo;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias_peru'; // Nombre de la tabla

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }

    public function distritos()
    {
        return $this->hasMany(Distrito::class, 'provincia_id', 'id');
    }
}
