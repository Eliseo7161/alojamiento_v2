<div class="modal fade" id="nuevoTipoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoTipoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevoTipoModalLabel">
                    <i class="fas fa-plus-circle mr-1"></i> Nuevo Tipo de Habitación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tipos-habitacion.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_tipo" class="font-weight-bold">Nombre del Tipo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            </div>
                            <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control" placeholder="Ej: Simple, Doble, Suite VIP..." required value="{{ old('nombre_tipo') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="precio_noche" class="font-weight-bold">Precio por Noche (S/.) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">S/.</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="precio_noche" id="precio_noche" class="form-control" placeholder="0.00" required value="{{ old('precio_noche') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="capacidad" class="font-weight-bold">Capacidad Máxima (Personas) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-friends"></i></span>
                            </div>
                            <input type="number" name="capacidad" id="capacidad" class="form-control" min="1" max="50" placeholder="Ej: 1, 2, 4..." required value="{{ old('capacidad', 1) }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Tipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
