@extends('layouts.vertical', ['title' => 'Dashboard'])
    @section('content')

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Registrar Empresa</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                <li class="breadcrumb-item active">Form Validation</li>
            </ol>
        </div>
    </div>
        
    <!-- General Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registrar Empresa</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <form method="POST" action="{{ route('empresa.registrar') }}">
                        @csrf

                        <!-- Tipo de Empresa -->
                        <div class="mb-3">
                            <label for="tipo_empresa" class="form-label">Tipo de Empresa</label>
                            <select id="tipo_empresa" class="form-select" name="tipo_empresa" required onchange="toggleDocumentInput()">
                                <option value="natural">Natural (DNI)</option>
                                <option value="juridico">Jurídico (RUC)</option>
                            </select>
                            @error('tipo_empresa')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Documento -->
                        <div class="mb-3">
                            <label for="documento" class="form-label">Documento</label>
                            <input id="documento" class="form-control" type="text" name="documento" value="{{ old('documento') }}" required onchange="fetchData()" />
                            @error('documento')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Razón Social -->
                        <div class="mb-3">
                            <label for="razon_social" class="form-label">Razón Social</label>
                            <input id="razon_social" class="form-control" type="text" name="razon_social" value="{{ old('razon_social') }}" required readonly />
                            @error('razon_social')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo -->
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <input id="tipo" class="form-control" type="text" name="tipo" value="{{ old('tipo') }}" required readonly />
                            @error('tipo')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input id="direccion" class="form-control" type="text" name="direccion" value="{{ old('direccion') }}" required readonly />
                            @error('direccion')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input id="telefono" class="form-control" type="text" name="telefono" value="{{ old('telefono') }}" required />
                            @error('telefono')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sitio Web -->
                        <div class="mb-3">
                            <label for="sitio_web" class="form-label">URL Red Social</label>
                            <input id="sitio_web" class="form-control" type="url" name="sitio_web" value="{{ old('sitio_web') }}" required />
                            @error('sitio_web')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <!-- Botón Listar Empresas (alineado a la izquierda) -->
                            <div class="col-auto">
                                <button type="button" class="btn btn-secondary">
                                            {{ __('Listar Categorías') }}
                                </button>
                            </div>

                            <!-- Botón Registrar Empresa (alineado a la derecha) -->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar Empresa') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

<script>
    // Cambiar el placeholder del campo de documento dependiendo del tipo de empresa
    function toggleDocumentInput() {
        const tipoEmpresa = document.getElementById('tipo_empresa').value;
        const documentoInput = document.getElementById('documento');

        if (tipoEmpresa === 'natural') {
            documentoInput.placeholder = 'Ingrese su DNI';
        } else {
            documentoInput.placeholder = 'Ingrese su RUC';
        }

        clearFormFields(); // Limpia los campos si cambia el tipo
    }

    // Función para limpiar los campos del formulario
    function clearFormFields() {
        document.getElementById('razon_social').value = '';
        document.getElementById('tipo').value = '';  
        document.getElementById('direccion').value = '';
    }

    // Función para realizar la consulta
    function fetchData() {
        const documento = document.getElementById('documento').value;
        const tipo_empresa = document.getElementById('tipo_empresa').value;

        // Limpia los campos si el campo de documento está vacío
        if (documento.length === 0) {
            clearFormFields();
            return; // Salir de la función si no hay documento
        }

        // Verifica el tipo de empresa y la longitud del documento
        if (tipo_empresa === 'natural' && documento.length === 8) { // DNI
            fetch(`https://api.perudevs.com/api/v1/dni/ruc-validate?document=${documento}&key={{ env('SUNAT_API_KEY') }}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la API');
                }
                return response.json();
            })
            .then(data => {
                if (data.estado) {
                    // Autocompletar los campos del formulario con los datos recibidos
                    document.getElementById('razon_social').value = data.resultado.razon_social || '';
                    document.getElementById('tipo').value = data.resultado.tipo || '';  
                    // Combinar los datos en el campo de dirección
                    const direccionCompleta = `${data.resultado.departamento || ''}, ${data.resultado.provincia || ''}, ${data.resultado.distrito || ''}, ${data.resultado.direccion}`.trim().replace(/, +/g, ', ');
                    document.getElementById('direccion').value = direccionCompleta || '';
                } else {
                    console.error('Error en la respuesta de la API:', data.mensaje);
                }
            })
            .catch(error => console.error('Error en la solicitud:', error));
        } else if (tipo_empresa === 'juridico' && documento.length === 11) { // RUC
            fetch(`https://api.perudevs.com/api/v1/ruc?document=${documento}&key={{ env('SUNAT_API_KEY') }}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la API');
                }
                return response.json();
            })
            .then(data => {
                if (data.estado) {
                    // Autocompletar los campos del formulario con los datos recibidos
                    document.getElementById('razon_social').value = data.resultado.razon_social || '';
                    document.getElementById('tipo').value = data.resultado.tipo || '';  
                    // Combinar los datos en el campo de dirección
                    const direccionCompleta = `${data.resultado.departamento || ''}, ${data.resultado.provincia || ''}, ${data.resultado.distrito || ''}, ${data.resultado.direccion}`.trim().replace(/, +/g, ', ');
                    document.getElementById('direccion').value = direccionCompleta || '';
                } else {
                    console.error('Error en la respuesta de la API:', data.mensaje);
                }
            })
            .catch(error => console.error('Error en la solicitud:', error));
        } else {
            clearFormFields(); // Limpia los campos si el documento no tiene la longitud adecuada
        }
    }

    // Escucha cambios en el campo de documento para realizar autocompletado
    document.getElementById('documento').addEventListener('input', fetchData);

    // Escucha cambios en el tipo de empresa
    document.getElementById('tipo_empresa').addEventListener('change', fetchData);
</script>

@endsection

@section('script')
@endsection

