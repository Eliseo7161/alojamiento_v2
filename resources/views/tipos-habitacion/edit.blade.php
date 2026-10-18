<div class="modal fade" id="editarTipoModal{{ $tipo->id_tipo_habitacion }}" tabindex="-1" role="dialog" aria-labelledby="editarTipoModalLabel{{ $tipo->id_tipo_habitacion }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold" id="editarTipoModalLabel{{ $tipo->id_tipo_habitacion }}">
                    <i class="fas fa-edit mr-1"></i> Editar Tipo de Habitación
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tipos-habitacion.update', $tipo->id_tipo_habitacion) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_tipo_{{ $tipo->id_tipo_habitacion }}" class="font-weight-bold">Nombre del Tipo <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            </div>
                            <input type="text" name="nombre_tipo" id="nombre_tipo_{{ $tipo->id_tipo_habitacion }}" class="form-control" required value="{{ old('nombre_tipo', $tipo->nombre_tipo) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="precio_noche_{{ $tipo->id_tipo_habitacion }}" class="font-weight-bold">Precio por Noche (S/.) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">S/.</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="precio_noche" id="precio_noche_{{ $tipo->id_tipo_habitacion }}" class="form-control" required value="{{ old('precio_noche', $tipo->precio_noche) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="capacidad_{{ $tipo->id_tipo_habitacion }}" class="font-weight-bold">Capacidad Máxima (Personas) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-friends"></i></span>
                            </div>
                            <input type="number" name="capacidad" id="capacidad_{{ $tipo->id_tipo_habitacion }}" class="form-control" min="1" max="50" required value="{{ old('capacidad', $tipo->capacidad) }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                        <i class="fas fa-sync mr-1"></i> Actualizar Tipo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
