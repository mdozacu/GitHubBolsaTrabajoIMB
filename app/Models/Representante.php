<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representante extends Model
{
    use HasFactory;

    // Especifica la tabla asociada con el modelo
    protected $table = 'representantes';

    // Define la clave primaria
    protected $primaryKey = 'id_representante';

    // Indica si la clave primaria es un entero autoincremental
    public $incrementing = true;

    // Indica si el modelo debe utilizar timestamps
    public $timestamps = true;

    // Especifica los atributos que son asignables en masa
    protected $fillable = [
        'id_empresa',
        'nombres_api',
        'apellido_paterno_api',
        'apellido_materno_api',
        'nombre_completo_api',
        'genero_api',
        'fecha_nacimiento_api',
        'numero',
        'correo',
        'documento_identidad',
        'departamento_api',
        'provincia_api',
        'distrito_api',
        'direccion_api',
        'direccion_completa_api',
        'cargo',
        'codigo_verificacion_api',
        'ubigeo_reniec',
        'ubigeo_sunat',
    ];

    // Castear fechas para ser reconocidas como objetos Carbon
    protected $casts = [
        'fecha_nacimiento_api' => 'date',
    ];

    // Define la relación con la tabla empresas
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }
}
