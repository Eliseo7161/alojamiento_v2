<div class="modal fade" id="ajusteModal{{ $stock->id_stock }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-sliders-h mr-2"></i> Ajustar Stock / Inventario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('stock.update', $stock->id_stock) }}" method="POST" class="form-confirmar-ajuste">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-left">
                    <div class="p-3 bg-light rounded border mb-3">
                        <h6 class="font-weight-bold text-dark mb-1">
                            <i class="fas fa-box text-primary mr-1"></i> {{ $stock->producto->nombre_producto ?? 'Producto' }}
                        </h6>
                        <small class="text-muted">Categoría: {{ $stock->producto->categoria->nombre_categoria ?? 'Sin Categoría' }} | Precio Venta: Bs. {{ number_format($stock->producto->precio_venta ?? 0, 2) }}</small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Cantidad Actual Física en Almacén <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad_actual" class="form-control font-weight-bold" value="{{ $stock->cantidad_actual }}" required min="0" style="font-size: 1.15rem;">
                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Ingresa el conteo físico real de unidades en estante o heladera.</small>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Stock Mínimo (Alerta de reposición) <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad_minima" class="form-control" value="{{ $stock->cantidad_minima }}" required min="0">
                        <small class="text-muted">Si el stock llega a este número, se pondrá en amarillo o rojo.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info font-weight-bold shadow-sm">
                        <i class="fas fa-check mr-1"></i> Guardar Ajuste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
