<?php

namespace App\Http\Controllers\DNI;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class DNIConsultaController extends Controller
{
    /**
     * Consulta RUC o DNI y registra la empresa.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarEmpresa(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'tipo_empresa' => 'required|in:natural,juridico',
            'documento' => 'required|string|max:11',
            'razon_social' => 'required|string',
            'telefono' => 'required|string',
            'email' => 'required|email',
            'direccion' => 'required|string',
            'sitio_web' => 'nullable|url',  // Validación opcional del sitio web
        ]);

        // Consulta a la API según el tipo de empresa
        $documento = $request->input('documento');
        if ($request->input('tipo_empresa') === 'juridico') {
            $apiResponse = $this->consultarRuc($documento);
        } else {
            $apiResponse = $this->consultarRucDelDni($documento);
        }

        // Manejo de la respuesta de la API
        if ($apiResponse['success']) {
            $data = $apiResponse['data'];

            // Crear el usuario
            $user = User::create([
                'id' => $documento, // ID como número de documento
                'name' => $data['razon_social'], // Nombre como razón social
                'email' => $request->input('email'),
                'password' => Hash::make("{$documento}@IMB2024"), // Contraseña encriptada
                'tipo_usuario' => 'empresa', // Tipo de usuario
                'estado' => 1, // Estado activo
            ]);

            // Crear la empresa
            $empresa = Empresa::create([
                'id_usuario' => $user->id,
                'numero' => $documento,
                'nombre_o_razon_social_api' => $data['razon_social'], // Razón social desde la API
                'tipo_contribuyente_api' => $data['tipo'], // Tipo de contribuyente desde la API
                'tipo_empresa' => $request->input('tipo_empresa') === 'juridico' ? 'JURIDICA' : 'NATURAL',
                'estado_api' => $data['estado'] ?? 'ACTIVO', // Estado desde la API
                'condicion_api' => $data['condicion'] ?? 'HABIDO',
                'nombre_comercial_api' => $data['nombre_comercial'] ?? null,
                'sistema_emision_api' => $data['sistema_emision'] ?? null,
                'actividad_exterior_api' => $data['actividad_exterior'] ?? null,
                'sistema_contabilidad_api' => $data['sistema_contabilidad'] ?? null,
                'fecha_emision_electronica_api' => isset($data['fecha_emision_electronica']) ? date('Y-m-d', strtotime($data['fecha_emision_electronica'])) : null,
                'comprobantes_pago_api' => isset($data['comprobante_pago']) ? implode(', ', $data['comprobante_pago']) : null, // Manejo de comprobantes de pago
                'padrones_api' => isset($data['padrones']) ? implode(', ', $data['padrones']) : null,
                'departamento_api' => $data['departamento'] ?? null,
                'provincia_api' => $data['provincia'] ?? null,
                'distrito_api' => $data['distrito'] ?? null,
                'direccion_api' => $data['direccion'] ?? $request->input('direccion'),
                'direccion_completa_api' => "{$data['departamento']}, {$data['provincia']}, {$data['distrito']}, {$data['direccion']}",
                'telefono' => $request->input('telefono'),
                'sitio_web' => $request->input('sitio_web'),
                'fecha_registro_sunat' => isset($data['fecha_inscripcion']) ? date('Y-m-d', strtotime($data['fecha_inscripcion'])) : null, // Fecha de inscripción desde la API
                'fecha_inscripcion_api' => isset($data['fecha_inscripcion']) ? date('Y-m-d', strtotime($data['fecha_inscripcion'])) : null,
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Empresa registrada exitosamente.',
                'user' => $user,
                'empresa' => $empresa,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $apiResponse['message'],
            ]);
        }

        return redirect()->route('profile.edit')->with('success', 'Oferta de trabajo creada con éxito.');
    }

    /**
     * Consulta RUC a partir de un DNI.
     *
     * @param string $dni
     * @return array
     */
    protected function consultarRucDelDni($dni)
    {
        $apiKey = env('SUNAT_API_KEY');
        $url = "https://api.perudevs.com/api/v1/dni/ruc-validate?document={$dni}&key={$apiKey}";

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

    /**
     * Consulta RUC.
     *
     * @param string $ruc
     * @return array
     */
    protected function consultarRuc($ruc)
    {
        $apiKey = env('SUNAT_API_KEY');
        $url = "https://api.perudevs.com/api/v1/ruc?document={$ruc}&key={$apiKey}";

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
            Log::error("Error en la consulta del RUC: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno al consultar el RUC.'
            ];
        }
        
    }
    
}
