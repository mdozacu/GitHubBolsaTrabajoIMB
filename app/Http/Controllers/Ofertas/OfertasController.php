<?php

namespace App\Http\Controllers\Ofertas;

use App\Models\Categorias; // Asegúrate de que este nombre coincide con tu modelo

use App\Models\Ubigeo\Departamento;
use App\Models\Ubigeo\Provincia;
use App\Models\Ubigeo\Distrito;

use App\Models\OfertaTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller; // Importa la clase Controller aquí

class OfertasController extends Controller
{
    /**
     * Muestra el formulario para crear una nueva oferta de trabajo.
     */
    public function create()
    {
        // Cargar todas las categorías para el formulario
        $categorias = Categorias::all();
        // Cargar datos de departamentos, provincias y distritos
        $departamentos = Departamento::all();
        $provincias = Provincia::all();
        $distritos = Distrito::all();

        return view('ofertas.agregar_oferta', compact('categorias','departamentos','provincias','distritos'));
    }

    /**
     * Procesa y guarda los datos del formulario de oferta de trabajo.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'titulo' => 'required|max:100',
            'descripcion' => 'required',
            'direccion' => 'required|max:255',
            'departamento_oferta' => 'required|string|max:2',
            'provincia_oferta' => 'required|string|max:4',
            'distrito_oferta' => 'required|string|max:6',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'tipo_contrato' => 'required|in:prácticas,tiempo completo,medio tiempo,temporal,freelance',
            'salario' => 'required|numeric|min:0',
            'fecha_cierre' => 'nullable|date',
            'requisitos' => 'nullable',
            'beneficios' => 'nullable',
            'experiencia_requerida' => 'nullable|max:255',
            'imagenes.*' => 'nullable|image|max:2048',
        ]);

        // Depura los datos validados
        //dd($validatedData);

        // Manejo de imágenes (si se suben)
        $imagenes = [];
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('ofertas', 'public');
                $imagenes[] = $path;
            }
        }

        $departamento_id = str_pad($validatedData['departamento_oferta'], 2, '0', STR_PAD_LEFT);
        $provincia_id = str_pad($validatedData['provincia_oferta'], 4, '0', STR_PAD_LEFT);
        $distrito_id = str_pad($validatedData['distrito_oferta'], 6, '0', STR_PAD_LEFT);

        // Crear la oferta de trabajo
        $oferta = OfertaTrabajo::create([
            'id_empresa' => Auth::user()->empresa->id_empresa,
            'id_usuario' => Auth::id(),
            'id_categoria' => $validatedData['id_categoria'],
            'titulo' => $validatedData['titulo'],
            'descripcion' => $validatedData['descripcion'],
            'direccion' => $validatedData['direccion'],
            'departamento_id' => $departamento_id,  // Asegurado con 2 dígitos
            'provincia_id' => $provincia_id,        // Asegurado con 4 dígitos
            'distrito_id' => $distrito_id,          // Asegurado con 6 dígitos
            'tipo_contrato' => $validatedData['tipo_contrato'],
            'salario' => $validatedData['salario'],
            'fecha_cierre' => $validatedData['fecha_cierre'],
            'requisitos' => $validatedData['requisitos'],
            'beneficios' => $validatedData['beneficios'],
            'experiencia_requerida' => $validatedData['experiencia_requerida'],
            'imagenes' => json_encode($imagenes),
        ]);

        if (!$oferta) {
            return back()->withErrors(['error' => 'No se pudo crear la oferta de trabajo.']);
        }

        return redirect()->route('oferta.agregar')->with('success', 'Oferta de trabajo creada con éxito.');
    }


}
