<?php

namespace App\Http\Controllers\Representante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Representante;

class RepresentanteController extends Controller
{
    /**
     * Registra un representante.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarRepresentante(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'documento_identidad' => 'required|string|max:15',
            'numero' => 'required|string|max:15',
            'correo' => 'required|string|email|max:60',
            'cargo' => 'required|string|max:30',
        ]);

        // Obtener el usuario autenticado
        $user = auth()->user();

        // Verificar que el usuario esté autenticado
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.',
            ], 401);
        }

        // Obtener la empresa asociada al usuario
        $empresa = $user->empresa;

        // Verificar si el usuario tiene una empresa asociada
        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una empresa asociada al usuario.',
            ], 404);
        }

        // Consulta la API para obtener datos del DNI "FACTILIZA"
        $dni = $request->input('documento_identidad');
        $apiResponse = $this->consultaDNIRepresentante($dni);

        if (!$apiResponse['success']) {
            return response()->json([
                'success' => false,
                'message' => $apiResponse['message'],
            ]);
        }

        $data = $apiResponse['data'];


        // Consulta la API para obtener datos del DNI "PERUDEVS"
        $dniperudevs = $request->input('documento_identidad');
        $apiResponsePeruDEVS = $this->consultaDNI_PeruDEVS($dniperudevs);

        if (!$apiResponsePeruDEVS['success']) {
            return response()->json([
                'success' => false,
                'message' => $apiResponsePeruDEVS['message'],
            ]);
        }

        $dataPeruDEVS = $apiResponsePeruDEVS['data'];


        // Registrar el representante en la base de datos

        $representante = Representante::create([
            'id_empresa' => $empresa->id_empresa,
            'documento_identidad' => $data['numero'] ?? null,
            'nombres_api' => $data['nombres'] ?? null,
            'apellido_paterno_api' => $data['apellido_paterno'] ?? null,
            'apellido_materno_api' => $data['apellido_materno'] ?? null,
            'nombre_completo_api' => ($data['nombres'] ?? '') . ' ' . ($data['apellido_paterno'] ?? '') . ' ' . ($data['apellido_materno'] ?? ''),
            'numero' => $request->input('numero'),
            'correo' => $request->input('correo'),
            'cargo' => $request->input('cargo'),
            'departamento_api' => $data['departamento'] ?? null,
            'provincia_api' => $data['provincia'] ?? null,
            'distrito_api' => $data['distrito'] ?? null,
            'direccion_api' => $data['direccion'] ?? null,
            'direccion_completa_api' => $data['direccion_completa'] ?? null,

            //DATOS PeruDEVS--------------------
            'genero_api' => $dataPeruDEVS['genero'] ?? null,
            'fecha_nacimiento_api' => !empty($dataPeruDEVS['fecha_nacimiento'])? \Carbon\Carbon::createFromFormat('d/m/Y', $dataPeruDEVS['fecha_nacimiento'])->format('Y-m-d'): null,
            'codigo_verificacion_api' => $dataPeruDEVS['codigo_verificacion'] ?? null,
            // ----------------------------------

            'ubigeo_reniec' => $data['ubigeo_reniec'] ?? null,
            'ubigeo_sunat' => $data['ubigeo_sunat'] ?? null,
        ]);


        // Redirigir al perfil o dashboard después del registro exitoso
        return redirect()->route('profile.edit'); // Redirigir a la ruta del perfil o dashboard

        //return response()->json([
        //    'success' => true,
        //    'message' => 'Representante registrado exitosamente.',
        //    'representante' => $representante,
        //]);
    }

    /**
     * Consulta DNI a partir de la API.
     *
     * @param string $documento_identidad
     * @return array
     */
    protected function consultaDNIRepresentante($documento_identidad)
    {
        $apiKey = env('FACTILIZA');  // Asegúrate de tener tu clave API configurada en el archivo .env
        $url = "https://api.factiliza.com/pe/v1/dni/info/{$documento_identidad}";

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data['data'],
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se pudo completar la consulta.',
                ];
            }
        } catch (\Exception $e) {
            Log::error("Error en la consulta del DNI: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno al consultar el DNI.',
            ];
        }
    }







    
    /**
     * Consulta DNI DATOS FALTANTES.
     * FECHA NACIMIENTO - GÉNERO - CÓDIGO DE VERIFICACIÓN
     * @param string $documento_identidad
     * @return array
     */

    protected function consultaDNI_PeruDEVS($documento_identidad){

        $apiKey = env('PERUDEVS');
        $url = "https://api.perudevs.com/api/v1/dni/complete?document={$documento_identidad}&key=$apiKey";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data['resultado']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se pudo completar la consulta.'
                ];
            }
        } catch (\Exception $e) {
            Log::error("Error en la consulta del DNI: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno al consultar el DNI.'
            ];
        }

    }

}
