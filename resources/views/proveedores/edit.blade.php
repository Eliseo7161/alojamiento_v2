<div class="modal fade" id="editarProvModal{{ $prov->id_proveedor }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Editar Proveedor #{{ $prov->id_proveedor }}</h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('proveedores.update', $prov->id_proveedor) }}" method="POST" class="form-confirmar-editar">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Razón Social <span class="text-danger">*</span></label>
                        <input type="text" name="razon_social" class="form-control" value="{{ $prov->razon_social }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">NIT / RUC <span class="text-danger">*</span></label>
                        <input type="text" name="nit_ruc" class="form-control" value="{{ $prov->nit_ruc }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ $prov->telefono }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ $prov->direccion }}">
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
