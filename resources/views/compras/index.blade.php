@extends('adminlte::page')

@section('title', 'Módulo de Compras')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-shopping-cart text-primary mr-2"></i> Historial de Compras</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalNuevaCompra">
            <i class="fas fa-plus-circle mr-1"></i> Nueva Compra
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
                    <span class="text-muted font-weight-bold">Total de compras efectuadas: {{ count($compras) }}</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <input type="text" id="filtroCompras" class="form-control form-control-sm" placeholder="Buscar por proveedor, usuario, fecha...">
                </div>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tablaCompras">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 80px;" class="text-center"># Compra</th>
                        <th style="width: 170px;">Fecha y Hora</th>
                        <th>Proveedor</th>
                        <th>Registrado por</th>
                        <th style="width: 140px;" class="text-center">Productos</th>
                        <th style="width: 150px;" class="text-right">Total Compra</th>
                        <th style="width: 180px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $c)
                        <tr>
                            <td class="align-middle text-center font-weight-bold text-muted">#{{ $c->id_compra }}</td>
                            <td class="align-middle">
                                <i class="fas fa-calendar-alt text-primary mr-1"></i> {{ $c->fecha_compra ? $c->fecha_compra->format('d/m/Y H:i') : '' }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark">
                                {{ $c->proveedor->razon_social ?? 'Proveedor General' }}
                                <br><small class="text-muted">NIT: {{ $c->proveedor->nit_ruc ?? 'S/N' }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border"><i class="fas fa-user mr-1 text-secondary"></i> {{ $c->usuario->name ?? 'Usuario' }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-info p-2">{{ $c->detalles->count() }} ítems</span>
                            </td>
                            <td class="align-middle text-right font-weight-bold text-danger" style="font-size: 1.05rem;">
                                Bs. {{ number_format($c->total_compra, 2) }}
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info font-weight-normal" data-toggle="modal" data-target="#detalleCompraModal{{ $c->id_compra }}" title="Ver Detalle">
                                        <i class="fas fa-eye"></i> Detalle
                                    </button>
                                    <form action="{{ route('compras.destroy', $c->id_compra) }}" method="POST" class="d-inline ml-1 form-anular">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Anular Compra">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-cart fa-3x mb-3 text-secondary d-block"></i>
                                No hay compras registradas en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modales Detalle de cada Compra -->
    @foreach($compras as $c)
        @include('compras.show', ['c' => $c])
    @endforeach

    <!-- Modal Nueva Compra -->
    @include('compras.create', ['proveedores' => $proveedores, 'productos' => $productos])
@stop

@section('js')
<script>
    $('#filtroCompras').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tablaCompras tbody tr").filter(function() {
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

    // Confirmación al Anular / Eliminar Compra
    $(document).on('submit', '.form-anular', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¡No podrá revertir esta acción! El stock se revertirá de almacén y se eliminará el egreso de caja.",
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

    // Confirmación al Guardar Nueva Compra
    $(document).on('submit', '#formCompraModal', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea confirmar y registrar esta compra de abastecimiento?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, registrar compra',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Lógica dinámica del Modal de Compra
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
        const rowId = `compra_row_${modalCompraFilaIndex}`;

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

    // Impresión limpia directa desde el Modal
    window.imprimirModalArea = function(modalId, titulo) {
        const modal = document.getElementById(modalId);
        const cuerpo = modal.querySelector('.modal-body').innerHTML;
        const ventana = window.open('', '_blank', 'height=650,width=850');
        ventana.document.write(`
            <html>
                <head>
                    <title>${titulo}</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
                    <style>
                        body { font-family: Arial, sans-serif; padding: 25px; }
                        @media print { .no-print { display: none; } }
                    </style>
                </head>
                <body>
                    <div class="text-center mb-4">
                        <h4 class="font-weight-bold">${titulo}</h4>
                        <p class="text-muted small">Sistema de Gestión de Alojamiento y Hospedaje</p>
                        <hr>
                    </div>
                    ${cuerpo}
                </body>
            </html>
        `);
        ventana.document.close();
        ventana.focus();
        setTimeout(function() {
            ventana.print();
            ventana.close();
        }, 350);
    };
</script>
@stop
