@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Agregar Categoría</h4>
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
                    <h5 class="card-title mb-0">Agregar Categoría</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <form method="POST" action="{{ route('categoria.registrar') }}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <!-- nombre_categoria -->
                                <div class="mb-3">
                                    <label for="nombre_categoria" class="form-label">Nombre Categoría</label>
                                    <input id="nombre_categoria" class="form-control" type="text" name="nombre_categoria" value="{{ old('nombre_categoria') }}" required />
                                    @error('nombre_categoria')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- descripcion_categoria -->
                                <div class="mb-3">
                                    <label for="descripcion_categoria" class="form-label">Descripción Categoría</label>
                                    <input id="descripcion_categoria" class="form-control" type="text" name="descripcion_categoria" value="{{ old('descripcion_categoria') }}" required />
                                    @error('descripcion_categoria')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between mt-4">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-secondary">
                                            {{ __('Listar Categorías') }}
                                        </button>
                                    </div>

                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Agregar Categoría') }}
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

@endsection

@section('script')
@endsection
