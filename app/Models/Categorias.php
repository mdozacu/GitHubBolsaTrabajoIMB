<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    // Define la tabla si no sigue la convención de nombres
    protected $table = 'categorias';

    // Si la clave primaria no es id, define el campo id
    protected $primaryKey = 'id_categoria';

    // Si la tabla usa timestamps
    public $timestamps = true;

    // Campos que pueden ser llenados
    protected $fillable = [
        'name_categoria',
        'descripcion_categoria',
        'creador_categoria' // No necesitas incluir created_at y updated_at, ya que son manejados automáticamente por Laravel
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'creador_categoria');
    }
}
