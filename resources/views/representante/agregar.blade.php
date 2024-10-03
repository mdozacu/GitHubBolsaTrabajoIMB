@extends('layouts.vertical', ['title' => 'Registro de Representante'])

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Registro de Representante</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Representantes</a></li>
            <li class="breadcrumb-item active">Agregar Representante</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    
                    <div class="card-body">
                        <div class="container">
                            <h1>Registro de Representante</h1>

                            <!-- Formulario para registrar representante -->
                            <form action="{{ route('representante.registrar') }}" method="POST" id="representante-form">
                                @csrf

                                <!-- Documento de identidad (DNI) -->
                                <div class="mb-4">
                                    <label for="documento_identidad" class="form-label">Documento de Identidad</label>
                                    <input type="text" class="form-control" id="documento_identidad" name="documento_identidad" placeholder="Ingrese DNI" maxlength="8" required>
                                </div>

                                <!-- Nombres y Apellidos (Autocompletado por la API) -->
                                <div class="mb-4">
                                    <label for="nombres_apellidos" class="form-label">Nombres y Apellidos</label>
                                    <input type="text" class="form-control" id="nombres_apellidos" name="nombres_apellidos" placeholder="Este campo se autocompletará" readonly>
                                </div>

                                <!-- Número de contacto -->
                                <div class="mb-4">
                                    <label for="numero" class="form-label">Número de Contacto</label>
                                    <input type="text" class="form-control" id="numero" name="numero" placeholder="Ingrese número de contacto" required>
                                </div>

                                <!-- Correo electrónico -->
                                <div class="mb-4">
                                    <label for="correo" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese correo electrónico" required>
                                </div>

                                <!-- Cargo en la empresa -->
                                <div class="mb-4">
                                    <label for="cargo" class="form-label">Cargo Legal en la Empresa</label>
                                    <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Ingrese cargo legal en la empresa" required>
                                </div>

                                <!-- Botón de enviar -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Registrar Representante</button>
                                </div>
                            </form>
                        </div>
                    </div> <!-- end card-body -->

                </div> <!-- end card -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end col -->
</div> <!-- end row -->

<script>
    // Función para realizar la consulta del DNI y autocompletar los nombres y apellidos
    function fetchData() {
        const dni = document.getElementById('documento_identidad').value;

        // Limpia el campo de nombres si el DNI está vacío
        if (dni.length === 0) {
            document.getElementById('nombres_apellidos').value = '';
            return; // Salir de la función si no hay DNI
        }

        // Verifica si el DNI tiene la longitud correcta
        if (dni.length === 8) {
            fetch(`https://dniruc.apisperu.com/api/v1/dni/${dni}?token={{ env('APISPERU2K') }}`, {
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
                // Si la API devuelve datos correctamente
                if (data.dni) {
                    const nombresApellidos = `${data.nombres} ${data.apellidoPaterno} ${data.apellidoMaterno}`.trim();
                    document.getElementById('nombres_apellidos').value = nombresApellidos || '';
                } else {
                    console.error('Error en la respuesta de la API:', data.mensaje);
                    document.getElementById('nombres_apellidos').value = '';
                }
            })
            .catch(error => console.error('Error en la solicitud:', error));
        } else {
            document.getElementById('nombres_apellidos').value = ''; // Limpia los campos si el DNI no tiene la longitud adecuada
        }
    }

    // Escucha cambios en el campo de DNI para realizar autocompletado
    document.getElementById('documento_identidad').addEventListener('input', fetchData);
</script>

@endsection
