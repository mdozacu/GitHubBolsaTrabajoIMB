<?php

namespace App\Models;

use App\Models\Ubigeo\Departamento;
use App\Models\Ubigeo\Provincia;
use App\Models\Ubigeo\Distrito;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OfertaTrabajo extends Model
{
    use HasFactory;

    // Especificar la tabla si no sigue el nombre por convención
    protected $table = 'ofertas_trabajo';

    // La clave primaria personalizada (id_oferta)
    protected $primaryKey = 'id_oferta';

    // Asignar las columnas que son rellenables (mass assignable)
    protected $fillable = [
        'id_empresa',
        'id_usuario',
        'id_categoria',
        'titulo',
        'descripcion',
        'direccion', // Cambiado de 'ubicacion' a 'direccion'
        'tipo_contrato',
        'salario',
        'fecha_publicacion',
        'fecha_cierre',
        'estado',
        'numero_postulaciones',
        'requisitos',
        'beneficios',
        'experiencia_requerida',
        'imagenes',
        'departamento_id', // Añadido
        'provincia_id',    // Añadido
        'distrito_id',     // Añadido
    ];

    // Indicar que estas columnas son tratadas como JSON
    protected $casts = [
        'imagenes' => 'array', // Almacenar varias imágenes en formato JSON
        'fecha_publicacion' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    // Definir la relación con la tabla empresas
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // Definir la relación con la tabla users
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    // Definir la relación con la tabla categorias
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Relación inversa de las postulaciones a la oferta
    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class, 'id_oferta', 'id_oferta');
    }

    // Definir la relación con las tablas de ubicación
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }

    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }
}
