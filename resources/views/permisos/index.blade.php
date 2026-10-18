@extends('adminlte::page')

@section('title', 'Administrar Permisos')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-dark"><i class="fas fa-key"></i> Administrar Permisos</h1>
    <button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#nuevoPermisoModal">
        Nuevo Permiso
    </button>
</div>
@stop

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
            <div class="input-group input-group-sm" style="width: 200px;">
                <input type="text" id="tableSearch" class="form-control" placeholder="Buscar...">
            </div>
        </div>
    </div>

    <div class="card-body p-0 table-responsive">
        <table class="table table-bordered mb-0" id="permisosTable">
            <thead class="bg-light">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Nombre del Permiso</th>
                    <th>Guard</th>
                    <th>Roles con este Permiso</th>
                    <th style="width: 200px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permisos as $permiso)
                    <tr>
                        <td class="align-middle">{{ $permiso->id }}</td>
                        <td class="align-middle">
                            <span class="badge badge-success badge-pill px-2 py-1">
                                {{ $permiso->name }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <small class="text-muted">{{ $permiso->guard_name }}</small>
                        </td>
                        <td class="align-middle">
                            @forelse($permiso->roles as $rol)
                                <span class="badge badge-info mr-1">{{ $rol->name }}</span>
                            @empty
                                <span class="text-muted"><i class="fas fa-ban mr-1"></i>Sin roles</span>
                            @endforelse
                        </td>
                        <td class="align-middle text-nowrap">
                            <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                <button type="button" class="btn btn-sm btn-warning text-dark font-weight-normal"
                                    data-toggle="modal" data-target="#editarPermisoModal{{ $permiso->id }}">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>

                                <form action="{{ route('permisos.destroy', $permiso->id) }}" method="POST"
                                    class="d-inline form-eliminar m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger font-weight-normal">
                                        <i class="fas fa-trash mr-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Editar -->
                    @include('permisos.edit', ['permiso' => $permiso])
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle mr-1"></i> No hay permisos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear -->
@include('permisos.create')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Buscador en tiempo real
    document.getElementById('tableSearch').addEventListener('keyup', function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#permisosTable tbody tr');
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
        });
    });

    // Alerta SweetAlert2 de éxito
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

    // Confirmación antes de eliminar
    $('.form-eliminar').submit(function (e) {
        e.preventDefault();
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
                this.submit();
            }
        });
    });
</script>
@stop