<div class="modal fade" id="nuevoProveedorModal" tabindex="-1" role="dialog" aria-labelledby="nuevoProveedorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevoProveedorModalLabel">
                    <i class="fas fa-truck mr-2"></i> Nuevo Proveedor
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('proveedores.store') }}" method="POST" class="form-confirmar-crear">
                @csrf
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Razón Social <span class="text-danger">*</span></label>
                        <input type="text" name="razon_social" class="form-control" placeholder="Ej: Distribuidora Los Andes S.R.L." required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">NIT / RUC <span class="text-danger">*</span></label>
                        <input type="text" name="nit_ruc" class="form-control" placeholder="Ej: 1023456789" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Ej: 71234567">
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Dirección</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Ej: Av. Principal #123">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Registrar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
