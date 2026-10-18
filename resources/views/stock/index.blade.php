@extends('adminlte::page')

@section('title', 'Control de Stock e Inventario')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-warehouse text-primary mr-2"></i> Control de Stock e Inventario</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalNuevaCompra">
            <i class="fas fa-cart-plus mr-1"></i> Abastecer Stock (Comprar)
        </button>
    </div>
@stop

@section('content')

    <div class="row mb-3">
        @php
            $totalItems = $stocks->sum('cantidad_actual');
            $agotados = $stocks->filter(fn($s) => $s->cantidad_actual <= 0)->count();
            $enAlerta = $stocks->filter(fn($s) => $s->cantidad_actual > 0 && $s->cantidad_actual <= $s->cantidad_minima)->count();
        @endphp
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cubes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Unidades en Almacén</span>
                    <span class="info-box-number font-weight-bold">{{ $totalItems }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Productos por Agotarse</span>
                    <span class="info-box-number font-weight-bold text-warning">{{ $enAlerta }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Productos Agotados</span>
                    <span class="info-box-number font-weight-bold text-danger">{{ $agotados }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted font-weight-bold">Listado de Existencias</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <input type="text" id="filtroStock" class="form-control form-control-sm" placeholder="Buscar producto en stock...">
                </div>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tablaStock">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th style="width: 140px;" class="text-right">Precio Venta</th>
                        <th style="width: 150px;" class="text-center">Stock Actual</th>
                        <th style="width: 140px;" class="text-center">Stock Mínimo</th>
                        <th style="width: 130px;" class="text-center">Estado</th>
                        <th style="width: 140px;" class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        @php
                            $critico = $stock->cantidad_actual <= $stock->cantidad_minima;
                            $cero = $stock->cantidad_actual <= 0;
                        @endphp
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">#{{ $stock->id_stock }}</td>
                            <td class="align-middle font-weight-bold text-dark">{{ $stock->producto->nombre_producto ?? 'N/A' }}</td>
                            <td class="align-middle">
                                <span class="badge badge-light border">{{ $stock->producto->categoria->nombre_categoria ?? 'Sin Categoría' }}</span>
                            </td>
                            <td class="align-middle text-right font-weight-bold text-dark">
                                Bs. {{ number_format($stock->producto->precio_venta ?? 0, 2) }}
                            </td>
                            <td class="align-middle text-center font-weight-bold {{ $cero ? 'text-danger' : ($critico ? 'text-warning' : 'text-success') }}" style="font-size: 1.1rem;">
                                {{ $stock->cantidad_actual }} unids.
                            </td>
                            <td class="align-middle text-center text-muted">
                                {{ $stock->cantidad_minima }} unids.
                            </td>
                            <td class="align-middle text-center">
                                @if($cero)
                                    <span class="badge badge-danger p-2"><i class="fas fa-ban mr-1"></i> Agotado</span>
                                @elseif($critico)
                                    <span class="badge badge-warning p-2 text-dark"><i class="fas fa-exclamation-triangle mr-1"></i> Bajo</span>
                                @else
                                    <span class="badge badge-success p-2"><i class="fas fa-check mr-1"></i> Óptimo</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                <button type="button" class="btn btn-sm btn-info font-weight-normal" data-toggle="modal" data-target="#ajusteModal{{ $stock->id_stock }}">
                                    <i class="fas fa-sliders-h mr-1"></i> Ajustar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                No se encontraron registros de stock.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modales de Ajuste de Stock (Fuera de la tabla) -->
    @foreach($stocks as $stock)
        @include('stock.edit', ['stock' => $stock])
    @endforeach

    <!-- Modal Abastecer Stock (Compra Rápida) -->
    @include('compras.create', ['proveedores' => $proveedores, 'productos' => $productos])
@stop

@section('js')
<script>
    $('#filtroStock').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tablaStock tbody tr").filter(function() {
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

    // Confirmación al Guardar Ajuste de Stock
    $(document).on('submit', '.form-confirmar-ajuste', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea actualizar las cantidades físicas y mínimas de este producto?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, guardar ajuste',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Confirmación al Registrar Compra Rápida (Abastecimiento)
    $(document).on('submit', '#formCompraModal', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea registrar esta compra y actualizar el inventario?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, confirmar compra',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Lógica dinámica del Modal de Compra en Stock
    let modalCompraFilaIndex = 0;

    $('#modal_compra_btn_agregar').on('click', function() {
        const prodId = $('#modal_compra_prod_select').val();
        const prodNombre = $('#modal_compra_prod_select option:selected').data('nombre');
        const cantidad = parseInt($('#modal_compra_prod_cant').val()) || 0;
        const costo = parseFloat($('#modal_compra_prod_costo').val()) || 0;

        if(!prodId) {
            alert('Por favor selecciona un producto.');
            return;
        }
        if(cantidad <= 0) {
            alert('La cantidad debe ser mayor a 0.');
            return;
        }
        if(costo <= 0) {
            alert('El costo unitario debe ser mayor a 0.00');
            return;
        }

        $('#modalCompraEmptyRow').remove();

        const subtotal = cantidad * costo;
        const rowId = `compra_stock_row_${modalCompraFilaIndex}`;

        const html = `
            <tr id="${rowId}">
                <td class="align-middle font-weight-bold text-dark">
                    <input type="hidden" name="productos[${modalCompraFilaIndex}][id]" value="${prodId}">
                    ${prodNombre}
                </td>
                <td class="align-middle text-center">
                    <input type="hidden" name="productos[${modalCompraFilaIndex}][cant]" value="${cantidad}">
                    ${cantidad}
                </td>
                <td class="align-middle text-right">
                    <input type="hidden" name="productos[${modalCompraFilaIndex}][costo]" value="${costo.toFixed(2)}">
                    Bs. ${costo.toFixed(2)}
                </td>
                <td class="align-middle text-right font-weight-bold text-dark modal-compra-subtotal" data-subtotal="${subtotal}">
                    Bs. ${subtotal.toFixed(2)}
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarFilaModalCompra('${rowId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#modalCompraDetallesBody').append(html);
        modalCompraFilaIndex++;

        // Reset
        $('#modal_compra_prod_select').val('');
        $('#modal_compra_prod_cant').val('1');
        $('#modal_compra_prod_costo').val('');

        calcularTotalModalCompra();
    });

    window.eliminarFilaModalCompra = function(id) {
        $(`#${id}`).remove();
        if($('#modalCompraDetallesBody tr').length === 0) {
            $('#modalCompraDetallesBody').html(`
                <tr id="modalCompraEmptyRow">
                    <td colspan="5" class="text-center py-4 text-muted">
                        No has agregado ningún producto a la compra todavía.
                    </td>
                </tr>
            `);
        }
        calcularTotalModalCompra();
    };

    function calcularTotalModalCompra() {
        let total = 0;
        $('.modal-compra-subtotal').each(function() {
            total += parseFloat($(this).data('subtotal')) || 0;
        });

        $('#modalTotalCompraDisplay').text(`Bs. ${total.toFixed(2)}`);

        if(total > 0) {
            $('#modalBtnGuardarCompra').prop('disabled', false);
        } else {
            $('#modalBtnGuardarCompra').prop('disabled', true);
        }
    }
</script>
@stop
