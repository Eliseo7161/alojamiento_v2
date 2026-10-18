@extends('adminlte::page')

@section('title', 'Administrar Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-users"></i> Administrar Usuarios</h1>
        <button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#nuevoUsuarioModal">
            Nuevo Usuario
        </button>
    </div>
@stop

@section('content')
    @if (isset($errors) && $errors->any())
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
            <table class="table table-bordered mb-0" id="usersTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th style="width: 100px;">Estado</th>
                        <th style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td class="align-middle">{{ $usuario->id }}</td>
                            <td class="align-middle">{{ $usuario->name }}</td>
                            <td class="align-middle">{{ $usuario->email }}</td>
                            <td class="align-middle">{{ strtolower($usuario->role ?? 'sin rol') }}</td>
                            <td class="align-middle">
                                @if(strtoupper($usuario->estado ?? 'ACTIVO') === 'ACTIVO')
                                    <span class="badge badge-success badge-pill px-2 py-1">ACTIVO</span>
                                @else
                                    <span class="badge badge-danger badge-pill px-2 py-1">INACTIVO</span>
                                @endif
                            </td>
                            <td class="align-middle text-nowrap">
                                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                    <button type="button" 
                                            class="btn btn-sm btn-warning text-dark font-weight-normal" 
                                            data-toggle="modal" 
                                            data-target="#editarUsuarioModal{{ $usuario->id }}">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </button>

                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline form-eliminar m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal">
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar individual para cada registro -->
                        @include('usuarios.edit', ['usuario' => $usuario, 'roles' => $roles])
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-info-circle mr-1"></i> No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    @include('usuarios.create')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Buscador en tiempo real
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#usersTable tbody tr');
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
    $('.form-eliminar').submit(function(e) {
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