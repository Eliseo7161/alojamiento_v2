<div class="modal fade" id="nuevoProductoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoProductoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevoProductoModalLabel"><i class="fas fa-box mr-2"></i> Nuevo Producto</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('productos.store') }}" method="POST" class="form-confirmar-crear">
                @csrf
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_producto" class="form-control" placeholder="Ej: Agua Mineral 500ml, Toalla Extra..." required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Categoría <span class="text-danger">*</span></label>
                        <select name="id_categoria" class="form-control" required>
                            <option value="">-- Seleccionar Categoría --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id_categoria }}">{{ $cat->nombre_categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark">Precio Venta (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="precio_venta" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark">Stock Inicial</label>
                            <input type="number" name="cantidad_inicial" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark">Stock Mínimo</label>
                            <input type="number" name="cantidad_minima" class="form-control" value="5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Crear Categoría Rápida -->
<div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" role="dialog" aria-labelledby="nuevaCategoriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevaCategoriaModalLabel"><i class="fas fa-folder-plus mr-2"></i> Nueva Categoría</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('categorias.rapida') }}" method="POST" class="form-confirmar-categoria">
                @csrf
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" name="nombre_categoria" class="form-control" placeholder="Ej: Bebidas, Snacks, Lavandería, Souvenirs..." required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-secondary font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
