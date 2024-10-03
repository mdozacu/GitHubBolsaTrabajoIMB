<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function showRegistrationForm()
    {
        return view('empresa.register');
    }

    public function register(Request $request)
    {
        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'tipo_empresa' => 'required',
            'documento' => 'required',
            'razon_social' => 'required',
            'nombre_comercial' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'sitio_web' => 'required|url',
            'email' => 'required|email',
        ]);

        // Lógica para registrar la empresa
        // Aquí puedes manejar la lógica para almacenar la empresa en la base de datos

        return redirect()->route('dashboard')->with('success', 'Empresa registrada correctamente.');
    }
}
