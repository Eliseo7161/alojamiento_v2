<div class="modal fade" id="editarProdModal{{ $prod->id_producto }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Editar Producto #{{ $prod->id_producto }}</h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('productos.update', $prod->id_producto) }}" method="POST" class="form-confirmar-editar">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_producto" class="form-control" value="{{ $prod->nombre_producto }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Categoría <span class="text-danger">*</span></label>
                        <select name="id_categoria" class="form-control" required>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}" {{ $prod->id_categoria == $cat->id_categoria ? 'selected' : '' }}>
                                    {{ $cat->nombre_categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Precio Venta (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="precio_venta" class="form-control" value="{{ $prod->precio_venta }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark">Alerta Stock Mínimo</label>
                            <input type="number" name="cantidad_minima" class="form-control" value="{{ $prod->stock->cantidad_minima ?? 0 }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning font-weight-bold text-dark shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
