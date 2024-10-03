<?php

namespace App\Http\Controllers\Categoria;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

//Vinculación con Modales
use App\Models\User;
use App\Models\Categorias; // Importa el modelo correcto



class CategoriaController extends Controller
{

    // Validación de los datos recibidos del Formulario de Registro de Categoría
    public function registrarCategoria(Request $request) {
        $request -> validate ([

            // NOMBRE DE FORMULARIO
            'nombre_categoria' => 'required|string|max:30',
            'descripcion_categoria' => 'required|string|max:200',
        ]);

        // Obtén el usuario autenticado
        $user = auth()->user();

        // Asegúrate de que hay un usuario autenticado
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes estar autenticado para crear una categoría.');
        }

        // Categorias es el nombre del Modal
        $categoria = Categorias::create([
            // NOMBRE DATABASE
            'name_categoria' => $request->input('nombre_categoria'),
            'descripcion_categoria' => $request->input('descripcion_categoria'),
            'creador_categoria' => $user->id,
            ]);

        // Redirigir al perfil o dashboard después del registro exitoso
        return redirect()->route('profile.edit'); // Redirigir a la ruta del perfil o dashboard

    }

}
