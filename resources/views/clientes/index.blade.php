@extends('adminlte::page')

@section('title', 'Gestión de Clientes')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-address-book text-primary mr-2"></i> Directorio de Clientes</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevoClienteModal">
            <i class="fas fa-user-plus mr-1"></i> Nuevo Cliente
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
            <div class="row align-items-center">
                <div class="col-md-5">
                    <span class="text-muted font-weight-bold">Total de clientes registrados: {{ count($clientes) }}</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Buscar por documento, nombre o teléfono...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="clientesTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th style="width: 150px;">Documento</th>
                        <th>Cliente (Nombre y Apellido)</th>
                        <th style="width: 160px;">Teléfono</th>
                        <th>Email</th>
                        <th style="width: 190px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">
                                #{{ $cliente->id_cliente }}
                            </td>
                            <td class="align-middle font-weight-bold text-primary">
                                <i class="fas fa-id-card mr-1 text-secondary"></i> {{ $cliente->documento }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark" style="font-size: 1.05rem;">
                                {{ $cliente->nombre_completo }}
                            </td>
                            <td class="align-middle">
                                @if($cliente->telefono)
                                    <i class="fas fa-phone text-success mr-1"></i> {{ $cliente->telefono }}
                                @else
                                    <span class="text-muted font-italic">No registrado</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($cliente->email)
                                    <i class="fas fa-envelope text-info mr-1"></i> {{ $cliente->email }}
                                @else
                                    <span class="text-muted font-italic">No registrado</span>
                                @endif
                            </td>
                            <td class="align-middle text-center text-nowrap">
                                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                    <button type="button" 
                                            class="btn btn-sm btn-warning text-dark font-weight-normal" 
                                            data-toggle="modal" 
                                            data-target="#editarClienteModal{{ $cliente->id_cliente }}"
                                            title="Editar Cliente">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </button>

                                    <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" class="d-inline form-eliminar m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Eliminar Cliente">
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar individual -->
                        @include('clientes.edit', ['cliente' => $cliente])
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-address-book fa-2x mb-2 d-block text-gray-300"></i>
                                No se encontraron clientes registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    @include('clientes.create')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Filtro de búsqueda en tiempo real sobre la tabla
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#clientesTable tbody tr").filter(function() {
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

        // Confirmación antes de eliminar
        $(document).on('submit', '.form-eliminar', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '¿Está seguro?',
                text: "Se eliminará el registro del cliente.",
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
