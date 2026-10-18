@extends('adminlte::page')

@section('title', 'Gestión de Proveedores')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-truck text-primary mr-2"></i> Directorio de Proveedores</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevoProveedorModal">
            <i class="fas fa-plus mr-1"></i> Nuevo Proveedor
        </button>
    </div>
@stop

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted font-weight-bold">Total proveedores registrados: {{ count($proveedores) }}</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <input type="text" id="filtroProveedores" class="form-control form-control-sm" placeholder="Buscar por Razón Social, NIT/RUC, teléfono...">
                </div>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tablaProveedores">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th>Razón Social</th>
                        <th style="width: 150px;">NIT / RUC</th>
                        <th style="width: 150px;">Teléfono</th>
                        <th>Dirección</th>
                        <th style="width: 100px;" class="text-center">Compras</th>
                        <th style="width: 170px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $prov)
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">#{{ $prov->id_proveedor }}</td>
                            <td class="align-middle font-weight-bold text-dark">{{ $prov->razon_social }}</td>
                            <td class="align-middle"><span class="badge badge-info p-2">{{ $prov->nit_ruc }}</span></td>
                            <td class="align-middle">{{ $prov->telefono ?? '—' }}</td>
                            <td class="align-middle text-muted">{{ $prov->direccion ?? '—' }}</td>
                            <td class="align-middle text-center">
                                <span class="badge badge-secondary p-2">{{ $prov->compras_count }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning text-dark font-weight-normal" data-toggle="modal" data-target="#editarProvModal{{ $prov->id_proveedor }}" title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <form action="{{ route('proveedores.destroy', $prov->id_proveedor) }}" method="POST" class="d-inline ml-1 form-eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                                             <!-- Modal Editar Proveedor -->
                        @include('proveedores.edit', ['prov' => $prov])
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-truck fa-3x mb-3 text-secondary d-block"></i>
                                No hay proveedores registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Proveedor -->
    @include('proveedores.create')
@stop

@section('js')
<script>
    $('#filtroProveedores').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tablaProveedores tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Alertas de sesión
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    @endif

    // Confirmación al Eliminar
    $(document).on('submit', '.form-eliminar', function(e) {
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

    // Confirmación al Guardar / Registrar nuevo
    $(document).on('submit', '.form-confirmar-crear', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea registrar este nuevo proveedor?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Confirmación al Guardar Cambios / Editar
    $(document).on('submit', '.form-confirmar-editar', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea guardar las modificaciones en este proveedor?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@stop
