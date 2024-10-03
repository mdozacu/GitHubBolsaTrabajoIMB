<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Define la tabla si no sigue la convención de nombres
    protected $table = 'empresas';

    // Si la clave primaria no es 'id', define el campo 'id'
    protected $primaryKey = 'id_empresa';

    // Si la tabla usa timestamps
    public $timestamps = true;

    // Campos que pueden ser llenados
    protected $fillable = [
        'id_usuario',
        'numero',
        'nombre_o_razon_social_api',
        'tipo_contribuyente_api',
        'tipo_empresa',
        'estado_api',
        'condicion_api',
        'nombre_comercial_api',
        'sistema_emision_api',
        'actividad_exterior_api',
        'sistema_contabilidad_api',
        'fecha_emision_electronica_api',
        'comprobantes_pago_api',
        'padrones_api',
        'departamento_api',
        'provincia_api',
        'distrito_api',
        'direccion_api',
        'direccion_completa_api',
        'logo_url',
        'url_convenio_generado',
        'url_convenio_firmado',
        'fecha_inicio_actividades',
        'telefono',
        'sitio_web',
        'fecha_registro_sunat',
        'fecha_inscripcion_api',
        'descripcion_empresa',
        'pais',
    ];

    // Relación con el modelo User (una empresa pertenece a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
