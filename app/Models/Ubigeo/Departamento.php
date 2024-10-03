<?php

namespace App\Models\Ubigeo;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos_peru'; // Nombre de la tabla

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'departamento_id', 'id');
    }
}
