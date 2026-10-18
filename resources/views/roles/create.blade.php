<div class="modal fade" id="nuevoRolModal" tabindex="-1" role="dialog" aria-labelledby="nuevoRolModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="nuevoRolModalLabel">
                    Nuevo Rol
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre del Rol: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required
                               value="{{ old('name') }}" placeholder="">
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary px-3">
                        Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
