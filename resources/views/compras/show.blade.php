<div class="modal fade" id="detalleCompraModal{{ $c->id_compra }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-file-invoice mr-2"></i> Detalle de Entrada de Compra #{{ $c->id_compra }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Proveedor:</strong> {{ $c->proveedor->razon_social ?? 'General' }}</p>
                        <p class="mb-1"><strong>NIT/RUC:</strong> {{ $c->proveedor->nit_ruc ?? 'S/N' }}</p>
                        <p class="mb-1"><strong>Teléfono:</strong> {{ $c->proveedor->telefono ?? '—' }}</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p class="mb-1"><strong>Fecha:</strong> {{ $c->fecha_compra ? $c->fecha_compra->format('d/m/Y H:i') : '' }}</p>
                        <p class="mb-1"><strong>Usuario:</strong> {{ $c->usuario->name ?? 'Usuario' }}</p>
                        <p class="mb-1">
                            <strong>Total:</strong> 
                            <span class="badge badge-danger p-2 font-weight-bold" style="font-size: 1.05rem;">
                                Bs. {{ number_format($c->total_compra, 2) }}
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
                                <th style="width: 130px;" class="text-right">Costo Unit.</th>
                                <th style="width: 130px;" class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($c->detalles as $det)
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark">{{ $det->producto->nombre_producto ?? 'N/A' }}</td>
                                    <td class="align-middle text-muted">{{ $det->producto->categoria->nombre_categoria ?? '—' }}</td>
                                    <td class="align-middle text-center font-weight-bold">{{ $det->cantidad }} unids.</td>
                                    <td class="align-middle text-right">Bs. {{ number_format($det->precio_unitario, 2) }}</td>
                                    <td class="align-middle text-right font-weight-bold text-dark">Bs. {{ number_format($det->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right font-weight-bold">TOTAL COMPRA:</th>
                                <th class="text-right font-weight-bold text-danger" style="font-size: 1.15rem;">
                                    Bs. {{ number_format($c->total_compra, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-default font-weight-bold" onclick="imprimirModalArea('detalleCompraModal{{ $c->id_compra }}', 'Detalle de Compra #{{ $c->id_compra }}')">
                    <i class="fas fa-print mr-1"></i> Imprimir
                </button>
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
