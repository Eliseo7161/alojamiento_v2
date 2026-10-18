@extends('adminlte::page')

@section('title', 'Control de Pagos y Caja')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-credit-card text-primary mr-2"></i> Control de Caja y Pagos</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevoPagoModal">
            <i class="fas fa-plus-circle mr-1"></i> Registrar Movimiento Manual
        </button>
    </div>
@stop

@section('content')

    <!-- Balances y Métricas de Caja -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-arrow-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Ingresos</span>
                    <span class="info-box-number font-weight-bold text-success" style="font-size: 1.25rem;">
                        Bs. {{ number_format($ingresosTotal, 2) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-arrow-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Egresos (Compras/Gastos)</span>
                    <span class="info-box-number font-weight-bold text-danger" style="font-size: 1.25rem;">
                        Bs. {{ number_format($egresosTotal, 2) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon {{ $balance >= 0 ? 'bg-primary' : 'bg-danger' }} elevation-1"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Balance Neto en Caja</span>
                    <span class="info-box-number font-weight-bold {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}" style="font-size: 1.25rem;">
                        Bs. {{ number_format($balance, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted font-weight-bold">Transacciones Registradas: {{ count($pagos) }}</span>
                </div>
                <div class="col-md-4 ml-auto">
                    <input type="text" id="filtroPagos" class="form-control form-control-sm" placeholder="Buscar por método, usuario, tipo...">
                </div>
            </div>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="tablaPagos">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th style="width: 160px;">Fecha y Hora</th>
                        <th style="width: 120px;" class="text-center">Tipo</th>
                        <th>Concepto / Origen</th>
                        <th style="width: 170px;">Método de Pago</th>
                        <th>Responsable</th>
                        <th style="width: 140px;" class="text-right">Monto</th>
                        <th style="width: 70px;" class="text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $p)
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">#{{ $p->id_pago }}</td>
                            <td class="align-middle">
                                {{ $p->fecha_pago ? $p->fecha_pago->format('d/m/Y H:i') : '' }}
                            </td>
                            <td class="align-middle text-center">
                                @if($p->tipo_transaccion === 'INGRESO')
                                    <span class="badge badge-success p-2"><i class="fas fa-plus mr-1"></i> INGRESO</span>
                                @else
                                    <span class="badge badge-danger p-2"><i class="fas fa-minus mr-1"></i> EGRESO</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($p->id_venta)
                                    <span class="text-primary font-weight-bold"><i class="fas fa-cash-register mr-1"></i> Venta #{{ $p->id_venta }}</span>
                                    @if($p->venta && $p->venta->cliente)
                                        <small class="text-muted d-block">Cliente: {{ $p->venta->cliente->nombre }} {{ $p->venta->cliente->apellido }}</small>
                                    @endif
                                @elseif($p->id_compra)
                                    <span class="text-danger font-weight-bold"><i class="fas fa-shopping-cart mr-1"></i> Compra a Proveedor #{{ $p->id_compra }}</span>
                                    @if($p->compra && $p->compra->proveedor)
                                        <small class="text-muted d-block">Proveedor: {{ $p->compra->proveedor->razon_social }}</small>
                                    @endif
                                @elseif($p->id_asignacion)
                                    <span class="text-info font-weight-bold"><i class="fas fa-bed mr-1"></i> Hospedaje Asignación #{{ $p->id_asignacion }}</span>
                                    @if($p->asignacion && $p->asignacion->cliente)
                                        <small class="text-muted d-block">Huésped: {{ $p->asignacion->cliente->nombre }} {{ $p->asignacion->cliente->apellido }}</small>
                                    @endif
                                @else
                                    <span class="text-dark font-weight-bold">Movimiento Manual de Caja</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border">{{ $p->metodo_pago }}</span>
                            </td>
                            <td class="align-middle">
                                <small><i class="fas fa-user text-secondary mr-1"></i> {{ $p->usuario->name ?? 'Usuario' }}</small>
                            </td>
                            <td class="align-middle text-right font-weight-bold {{ $p->tipo_transaccion === 'INGRESO' ? 'text-success' : 'text-danger' }}" style="font-size: 1.05rem;">
                                {{ $p->tipo_transaccion === 'INGRESO' ? '+' : '-' }} Bs. {{ number_format($p->monto, 2) }}
                            </td>
                            <td class="align-middle text-center">
                                <form action="{{ route('pagos.destroy', $p->id_pago) }}" method="POST" class="d-inline form-eliminar-pago">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Movimiento">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-credit-card fa-3x mb-3 text-secondary d-block"></i>
                                No hay movimientos de pagos o caja registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Registrar Movimiento Manual -->
    @include('pagos.create', ['asignacionesActivas' => $asignacionesActivas])
@stop

@section('js')
<script>
    $('#filtroPagos').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tablaPagos tbody tr").filter(function() {
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

    // Confirmación al Eliminar Pago / Movimiento
    $(document).on('submit', '.form-eliminar-pago', function(e) {
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

    // Confirmación al Registrar Movimiento Manual
    $(document).on('submit', '.form-confirmar-pago', function(e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea registrar este movimiento de caja?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@stop
