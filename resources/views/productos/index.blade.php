@extends('adminlte::page')

@section('title', 'Productos y Categorías')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-boxes text-primary mr-2"></i> Productos y Categorías</h1>
        <div>
            <button type="button" class="btn btn-secondary font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#nuevaCategoriaModal">
                <i class="fas fa-folder-plus mr-1"></i> Nueva Categoría
            </button>
            <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevoProductoModal">
                <i class="fas fa-plus mr-1"></i> Nuevo Producto
            </button>
        </div>
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
                    <span class="text-muted font-weight-bold">Total productos registrados: {{ count($productos) }}</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <input type="text" id="filtroProductos" class="form-control form-control-sm" placeholder="Buscar por producto, categoría...">
                </div>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tablaProductos">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th style="width: 140px;" class="text-right">Precio Venta</th>
                        <th style="width: 140px;" class="text-center">Stock Actual</th>
                        <th style="width: 130px;" class="text-center">Stock Mínimo</th>
                        <th style="width: 170px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $prod)
                        @php
                            $stockActual = $prod->stock->cantidad_actual ?? 0;
                            $stockMinimo = $prod->stock->cantidad_minima ?? 0;
                        @endphp
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">#{{ $prod->id_producto }}</td>
                            <td class="align-middle font-weight-bold text-dark">{{ $prod->nombre_producto }}</td>
                            <td class="align-middle">
                                <span class="badge badge-light border p-2">
                                    <i class="fas fa-tag text-secondary mr-1"></i> {{ $prod->categoria->nombre_categoria ?? 'Sin Categoría' }}
                                </span>
                            </td>
                            <td class="align-middle text-right font-weight-bold text-success">
                                Bs. {{ number_format($prod->precio_venta, 2) }}
                            </td>
                            <td class="align-middle text-center">
                                @if($stockActual <= $stockMinimo)
                                    <span class="badge badge-danger p-2 font-weight-bold" title="Stock bajo o agotado">
                                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $stockActual }} unids.
                                    </span>
                                @else
                                    <span class="badge badge-success p-2 font-weight-bold">
                                        {{ $stockActual }} unids.
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle text-center text-muted">
                                {{ $stockMinimo }} unids.
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-warning text-dark font-weight-normal" data-toggle="modal" data-target="#editarProdModal{{ $prod->id_producto }}" title="Editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <form action="{{ route('productos.destroy', $prod->id_producto) }}" method="POST" class="d-inline ml-1 form-eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar Producto -->
                        @include('productos.edit', ['prod' => $prod, 'categorias' => $categorias])
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-boxes fa-3x mb-3 text-secondary d-block"></i>
                                No hay productos registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modales Crear Producto y Categoría -->
    @include('productos.create', ['categorias' => $categorias])
@stop

@section('js')
<script>
    $('#filtroProductos').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tablaProductos tbody tr").filter(function() {
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

    // Confirmación al Eliminar Producto
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

    // Confirmación al Crear Producto
    $(document).on('submit', '.form-confirmar-crear', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea registrar este nuevo producto?",
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

    // Confirmación al Editar Producto
    $(document).on('submit', '.form-confirmar-editar', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea guardar las modificaciones en este producto?",
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

    // Confirmación al Crear Categoría
    $(document).on('submit', '.form-confirmar-categoria', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea agregar esta nueva categoría?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            cancelButtonColor: '#3085d6',
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
