@extends('layouts.app') <!-- Asegúrate de que tu aplicación tenga un layout -->

@section('content')
<div class="container mx-auto mt-5">
    <h2 class="text-center mb-4">Agregar Nueva Oferta de Trabajo</h2>

    <form action="{{ route('ofertas.registrar') }}" method="POST" enctype="multipart/form-data"> <!-- Agregar enctype -->
        @csrf

        <!-- Campo de Título -->
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <!-- Campo de Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4" class="form-control" required></textarea>
        </div>

        <!-- Campo de Dirección -->
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" required>
        </div>

         <!-- Campo de Departamento -->
         <div class="mb-3">
            <label for="departamento">Departamento:</label>
            <select name="departamento_oferta" id="departamento_oferta" class="form-control" required>
                <option value="">Seleccione un departamento</option>
                @foreach($departamentos as $departamento)
                    <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Campo de Provincia -->
        <div class="mb-3">
            <label for="provincia">Provincia:</label>
            <select name="provincia_oferta" id="provincia_oferta" class="form-control" required>
                <option value="">Seleccione una provincia</option>
            </select>
        </div>

        <!-- Campo de Distrito -->
        <div class="mb-3">
            <label for="distrito">Distrito:</label>
            <select name="distrito_oferta" id="distrito_oferta" class="form-control" required>
                <option value="">Seleccione un distrito</option>
            </select>
        </div>

        <!-- Campo de Categoría -->
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select name="id_categoria" id="categoria" class="form-select" required>
                <option value="" disabled selected>Selecciona una categoría</option>
                @foreach($categorias as $categoria) <!-- Cambiar a $categorias -->
                    <option value="{{ $categoria->id_categoria }}">{{ $categoria->name_categoria }}</option> <!-- Cambiar a $categoria -->
                @endforeach
            </select>
        </div>

        <!-- Campo de Tipo de Contrato -->
        <div class="mb-3">
            <label for="tipo_contrato" class="form-label">Tipo de Contrato</label>
            <select name="tipo_contrato" id="tipo_contrato" class="form-select" required>
                <option value="prácticas">Prácticas</option>
                <option value="tiempo completo">Tiempo Completo</option>
                <option value="medio tiempo">Medio Tiempo</option>
                <option value="temporal">Temporal</option>
                <option value="freelance">Freelance</option>
            </select>
        </div>

        <!-- Campo de Salario -->
        <div class="mb-3">
            <label for="salario" class="form-label">Salario</label>
            <input type="number" name="salario" id="salario" step="0.01" class="form-control" required>
        </div>

        <!-- Campo de Fecha de Cierre -->
        <div class="mb-3">
            <label for="fecha_cierre" class="form-label">Fecha de Cierre</label>
            <input type="date" name="fecha_cierre" id="fecha_cierre" class="form-control">
        </div>

        <!-- Campo de Requisitos -->
        <div class="mb-3">
            <label for="requisitos" class="form-label">Requisitos</label>
            <textarea name="requisitos" id="requisitos" rows="3" class="form-control"></textarea>
        </div>

        <!-- Campo de Beneficios -->
        <div class="mb-3">
            <label for="beneficios" class="form-label">Beneficios</label>
            <textarea name="beneficios" id="beneficios" rows="3" class="form-control"></textarea>
        </div>

        <!-- Campo de Experiencia Requerida -->
        <div class="mb-3">
            <label for="experiencia_requerida" class="form-label">Experiencia Requerida</label>
            <input type="text" name="experiencia_requerida" id="experiencia_requerida" class="form-control">
        </div>

        <!-- Campo de Imágenes -->
        <div class="mb-3">
            <label for="imagenes" class="form-label">Imágenes</label>
            <input type="file" name="imagenes[]" id="imagenes" multiple class="form-control">
            <div class="form-text">Puedes subir varias imágenes (máximo 2 MB cada una).</div>
        </div>

        <button type="submit" class="btn btn-primary">Crear Oferta</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const departamentosSelect = document.getElementById('departamento_oferta');
        const provinciasSelect = document.getElementById('provincia_oferta');
        const distritosSelect = document.getElementById('distrito_oferta');

        const provincias = @json($provincias);
        const distritos = @json($distritos);

        // Limpiar y actualizar provincias basadas en el departamento seleccionado
        departamentosSelect.addEventListener('change', function () {
            const departamentoId = parseInt(this.value, 10); // Convertir a entero

            // Resetear provincias y distritos
            provinciasSelect.innerHTML = '<option value="">Seleccione una provincia</option>';
            distritosSelect.innerHTML = '<option value="">Seleccione un distrito</option>';

            // Filtrar y agregar provincias correspondientes
            const filteredProvincias = provincias.filter(provincia => parseInt(provincia.departamento_id, 10) === departamentoId);
            filteredProvincias.forEach(provincia => {
                const option = document.createElement('option');
                option.value = provincia.id;
                option.textContent = provincia.nombre;
                provinciasSelect.appendChild(option);
            });
        });

        // Limpiar y actualizar distritos basados en la provincia seleccionada
        provinciasSelect.addEventListener('change', function () {
            const provinciaId = parseInt(this.value, 10); // Convertir a entero

            // Resetear distritos
            distritosSelect.innerHTML = '<option value="">Seleccione un distrito</option>';

            // Filtrar y agregar distritos correspondientes
            const filteredDistritos = distritos.filter(distrito => parseInt(distrito.provincia_id, 10) === provinciaId);
            filteredDistritos.forEach(distrito => {
                const option = document.createElement('option');
                option.value = distrito.id;
                option.textContent = distrito.nombre;
                distritosSelect.appendChild(option);
            });
        });
    });
</script>

@endsection
