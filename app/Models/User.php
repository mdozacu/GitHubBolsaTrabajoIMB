<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Campos que pueden ser llenados
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'tipo_usuario',
        'estado',
    ];

    // Ocultar campos sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casteo de tipos para los campos
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con el modelo Empresa (un usuario tiene una empresa)
    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id_usuario', 'id');
    }
}
