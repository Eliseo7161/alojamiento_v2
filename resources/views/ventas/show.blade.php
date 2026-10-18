<div class="modal fade" id="detalleVentaModal{{ $v->id_venta }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-receipt mr-2"></i> Comprobante de Venta #{{ $v->id_venta }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1">
                            <strong>Cliente:</strong> 
                            {{ $v->cliente ? ($v->cliente->nombre . ' ' . $v->cliente->apellido) : 'Mostrador / Ocasional' }}
                        </p>
                        @if($v->cliente && $v->cliente->documento)
                            <p class="mb-1"><strong>Documento:</strong> {{ $v->cliente->documento }}</p>
                        @endif
                        @if($v->asignacion && $v->asignacion->habitacion)
                            <p class="mb-1"><strong>Habitación:</strong> <span class="badge badge-info p-1">Hab. {{ $v->asignacion->habitacion->numero_habitacion }}</span></p>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p class="mb-1"><strong>Fecha:</strong> {{ $v->fecha_venta ? $v->fecha_venta->format('d/m/Y H:i') : '' }}</p>
                        <p class="mb-1"><strong>Atendido por:</strong> {{ $v->usuario->name ?? 'Usuario' }}</p>
                        <p class="mb-1">
                            <strong>Total:</strong> 
                            <span class="badge badge-success p-2 font-weight-bold" style="font-size: 1.1rem;">
                                Bs. {{ number_format($v->total, 2) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th style="width: 100px;" class="text-center">Cant.</th>
                                <th style="width: 130px;" class="text-right">Precio Unit.</th>
                                <th style="width: 130px;" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($v->detalles as $det)
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark">{{ $det->producto->nombre_producto ?? 'N/A' }}</td>
                                    <td class="align-middle text-muted">{{ $det->producto->categoria->nombre_categoria ?? '—' }}</td>
                                    <td class="align-middle text-center font-weight-bold">{{ $det->cantidad }} unids.</td>
                                    <td class="align-middle text-right">Bs. {{ number_format($det->precio_unitario, 2) }}</td>
                                    <td class="align-middle text-right font-weight-bold text-success">Bs. {{ number_format($det->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right font-weight-bold">TOTAL VENTA:</th>
                                <th class="text-right font-weight-bold text-success" style="font-size: 1.15rem;">
                                    Bs. {{ number_format($v->total, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-default font-weight-bold" onclick="imprimirModalArea('detalleVentaModal{{ $v->id_venta }}', 'Comprobante de Venta #{{ $v->id_venta }}')">
                    <i class="fas fa-print mr-1"></i> Imprimir
                </button>
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
