@extends('adminlte::page')

@section('title', 'Módulo de Ventas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-cash-register text-success mr-2"></i> Historial de Ventas</h1>
        <button type="button" class="btn btn-success font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalNuevaVenta">
            <i class="fas fa-cart-plus mr-1"></i> Nueva Venta
        </button>
    </div>
@stop

@section('content')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted font-weight-bold">Total de ventas realizadas: {{ count($ventas) }}</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <input type="text" id="filtroVentas" class="form-control form-control-sm" placeholder="Buscar por cliente, habitación, vendedor...">
                </div>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tablaVentas">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 80px;" class="text-center"># Venta</th>
                        <th style="width: 170px;">Fecha y Hora</th>
                        <th>Cliente / Huésped</th>
                        <th style="width: 160px;" class="text-center">Habitación</th>
                        <th>Atendido por</th>
                        <th style="width: 120px;" class="text-center">Ítems</th>
                        <th style="width: 150px;" class="text-right">Total Venta</th>
                        <th style="width: 170px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                        <tr>
                            <td class="align-middle text-center font-weight-bold text-muted">#{{ $v->id_venta }}</td>
                            <td class="align-middle">
                                <i class="fas fa-calendar-alt text-success mr-1"></i> {{ $v->fecha_venta ? $v->fecha_venta->format('d/m/Y H:i') : '' }}
                            </td>
                            <td class="align-middle font-weight-bold text-dark">
                                @if($v->cliente)
                                    <i class="fas fa-user text-primary mr-1"></i> {{ $v->cliente->nombre }} {{ $v->cliente->apellido }}
                                    <br><small class="text-muted">Doc: {{ $v->cliente->documento }}</small>
                                @else
                                    <span class="text-muted"><i class="fas fa-user-tag mr-1"></i> Cliente Ocasional / Mostrador</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @if($v->asignacion && $v->asignacion->habitacion)
                                    <span class="badge badge-info p-2 font-weight-bold">
                                        <i class="fas fa-bed mr-1"></i> Hab. {{ $v->asignacion->habitacion->numero_habitacion }}
                                    </span>
                                @else
                                    <span class="badge badge-light border text-muted">Mostrador</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border"><i class="fas fa-user mr-1 text-secondary"></i> {{ $v->usuario->name ?? 'Usuario' }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-secondary p-2">{{ $v->detalles->count() }} prod.</span>
                            </td>
                            <td class="align-middle text-right font-weight-bold text-success" style="font-size: 1.1rem;">
                                Bs. {{ number_format($v->total, 2) }}
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info font-weight-normal" data-toggle="modal" data-target="#detalleVentaModal{{ $v->id_venta }}" title="Ver Detalle">
                                        <i class="fas fa-eye"></i> Detalle
                                    </button>
                                    <form action="{{ route('ventas.destroy', $v->id_venta) }}" method="POST" class="d-inline ml-1 form-anular-venta">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Anular Venta">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-cash-register fa-3x mb-3 text-secondary d-block"></i>
                                No se han registrado ventas hasta el momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modales Detalle de cada Venta -->
    @foreach($ventas as $v)
        @include('ventas.show', ['v' => $v])
    @endforeach

    <!-- Modal Nueva Venta -->
    @include('ventas.create', [
        'clientes' => $clientes,
        'asignacionesActivas' => $asignacionesActivas,
        'productos' => $productos
    ])
@stop

@section('js')
<script>
    $('#filtroVentas').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tablaVentas tbody tr").filter(function() {
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

    // Confirmación al Anular / Eliminar Venta
    $(document).on('submit', '.form-anular-venta', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¡No podrá revertir esta acción! El stock se devolverá a inventario y se eliminará el ingreso de caja.",
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

    // Confirmación al Guardar Nueva Venta
    $(document).on('submit', '#formVentaModal', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea finalizar y cobrar esta venta?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, cobrar venta',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Lógica dinámica del Modal de Venta
    $('input[name="tipo_venta"]').on('change', function() {
        if($(this).val() === 'huesped') {
            $('#modal_bloqueHuesped').show();
            $('#modal_bloquePublico').hide();
        } else {
            $('#modal_bloqueHuesped').hide();
            $('#modal_bloquePublico').show();
        }
    });

    $('#modal_venta_prod_select').on('change', function() {
        const precio = $(this).find(':selected').data('precio') || '';
        $('#modal_venta_prod_precio').val(precio);
    });

    let modalVentaFilaIndex = 0;

    $('#modal_venta_btn_agregar').on('click', function() {
        const select = $('#modal_venta_prod_select option:selected');
        const prodId = $('#modal_venta_prod_select').val();
        const prodNombre = select.data('nombre');
        const maxStock = parseInt(select.data('stock')) || 0;
        const cantidad = parseInt($('#modal_venta_prod_cant').val()) || 0;
        const precio = parseFloat($('#modal_venta_prod_precio').val()) || 0;

        if(!prodId) {
            alert('Por favor selecciona un producto.');
            return;
        }
        if(cantidad <= 0) {
            alert('La cantidad debe ser mayor a 0.');
            return;
        }
        if(precio <= 0) {
            alert('El precio unitario no es válido.');
            return;
        }
        if(cantidad > maxStock) {
            alert(`Stock insuficiente en almacén. Solo quedan ${maxStock} unidades.`);
            return;
        }

        $('#modalVentaEmptyRow').remove();

        const subtotal = cantidad * precio;
        const rowId = `venta_item_${modalVentaFilaIndex}`;

        const html = `
            <tr id="${rowId}">
                <td class="align-middle font-weight-bold text-dark">
                    <input type="hidden" name="productos[${modalVentaFilaIndex}][id]" value="${prodId}">
                    ${prodNombre}
                </td>
                <td class="align-middle text-center">
                    <input type="hidden" name="productos[${modalVentaFilaIndex}][cant]" value="${cantidad}">
                    ${cantidad}
                </td>
                <td class="align-middle text-right">
                    <input type="hidden" name="productos[${modalVentaFilaIndex}][precio]" value="${precio.toFixed(2)}">
                    Bs. ${precio.toFixed(2)}
                </td>
                <td class="align-middle text-right font-weight-bold text-dark modal-venta-subtotal" data-subtotal="${subtotal}">
                    Bs. ${subtotal.toFixed(2)}
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarFilaModalVenta('${rowId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#modalVentaDetallesBody').append(html);
        modalVentaFilaIndex++;

        // Reset
        $('#modal_venta_prod_select').val('');
        $('#modal_venta_prod_cant').val('1');
        $('#modal_venta_prod_precio').val('');

        calcularTotalModalVenta();
    });

    window.eliminarFilaModalVenta = function(id) {
        $(`#${id}`).remove();
        if($('#modalVentaDetallesBody tr').length === 0) {
            $('#modalVentaDetallesBody').html(`
                <tr id="modalVentaEmptyRow">
                    <td colspan="5" class="text-center py-4 text-muted">
                        No has agregado ningún producto a la venta todavía.
                    </td>
                </tr>
            `);
        }
        calcularTotalModalVenta();
    };

    function calcularTotalModalVenta() {
        let total = 0;
        $('.modal-venta-subtotal').each(function() {
            total += parseFloat($(this).data('subtotal')) || 0;
        });

        $('#modalTotalVentaDisplay').text(`Bs. ${total.toFixed(2)}`);

        if(total > 0) {
            $('#modalBtnGuardarVenta').prop('disabled', false);
        } else {
            $('#modalBtnGuardarVenta').prop('disabled', true);
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
