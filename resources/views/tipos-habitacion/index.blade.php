@extends('adminlte::page')

@section('title', 'Tipos de Habitación')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-list-alt text-primary mr-2"></i> Tipos de Habitación</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevoTipoModal">
            <i class="fas fa-plus mr-1"></i> Nuevo Tipo
        </button>
    </div>
@stop

@section('content')
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong><i class="fas fa-exclamation-triangle mr-1"></i> Por favor corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header border-0 bg-white py-3">
            <div class="card-tools float-right">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="tableSearch" class="form-control" placeholder="Buscar tipo...">
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tiposTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th>Nombre del Tipo</th>
                        <th style="width: 180px;">Precio por Noche</th>
                        <th style="width: 180px;">Capacidad</th>
                        <th style="width: 160px;" class="text-center">Habitaciones</th>
                        <th style="width: 200px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tipos as $tipo)
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">
                                #{{ $tipo->id_tipo_habitacion }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark" style="font-size: 1.05rem;">
                                <i class="fas fa-tag mr-2 text-primary"></i> {{ $tipo->nombre_tipo }}
                            </td>
                            <td class="align-middle font-weight-bold text-success" style="font-size: 1rem;">
                                S/. {{ number_format($tipo->precio_noche, 2) }}
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-info px-2 py-1 font-weight-normal">
                                    <i class="fas fa-users mr-1"></i> {{ $tipo->capacidad }} {{ $tipo->capacidad == 1 ? 'persona' : 'personas' }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-light border px-2 py-1">
                                    {{ $tipo->habitaciones_count }} hab.
                                </span>
                            </td>
                            <td class="align-middle text-center text-nowrap">
                                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                    <button type="button" 
                                            class="btn btn-sm btn-warning text-dark font-weight-normal" 
                                            data-toggle="modal" 
                                            data-target="#editarTipoModal{{ $tipo->id_tipo_habitacion }}"
                                            title="Editar Tipo">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </button>

                                    <form action="{{ route('tipos-habitacion.destroy', $tipo->id_tipo_habitacion) }}" method="POST" class="d-inline form-eliminar m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Eliminar Tipo" {{ $tipo->habitaciones_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar individual -->
                        @include('tipos-habitacion.edit', ['tipo' => $tipo])
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-list-alt fa-2x mb-2 d-block text-gray-300"></i>
                                No hay tipos de habitación registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    @include('tipos-habitacion.create')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Buscador interactivo
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tiposTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Alerta SweetAlert2 de éxito (Guardar, Editar, Eliminar)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif

        // Alerta SweetAlert2 de error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @endif

        // Confirmación para eliminar
        $(document).on('submit', '.form-eliminar', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '¿Está seguro?',
                text: "¡No podrá revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@stop
