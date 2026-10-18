<div class="modal fade" id="editarHabitacionModal{{ $hab->id_habitacion }}" tabindex="-1" role="dialog" aria-labelledby="editarHabitacionModalLabel{{ $hab->id_habitacion }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold" id="editarHabitacionModalLabel{{ $hab->id_habitacion }}">
                    <i class="fas fa-edit mr-1"></i> Editar Habitación: {{ $hab->numero_habitacion }}
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('habitaciones.update', $hab->id_habitacion) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="numero_habitacion_{{ $hab->id_habitacion }}" class="font-weight-bold">Número de Habitación <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                            </div>
                            <input type="text" name="numero_habitacion" id="numero_habitacion_{{ $hab->id_habitacion }}" class="form-control" required value="{{ old('numero_habitacion', $hab->numero_habitacion) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="piso_{{ $hab->id_habitacion }}" class="font-weight-bold">Piso <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            </div>
                            <input type="number" name="piso" id="piso_{{ $hab->id_habitacion }}" class="form-control" min="1" max="50" required value="{{ old('piso', $hab->piso) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_tipo_habitacion_{{ $hab->id_habitacion }}" class="font-weight-bold">Tipo de Habitación <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-bed"></i></span>
                            </div>
                            <select name="id_tipo_habitacion" id="id_tipo_habitacion_{{ $hab->id_habitacion }}" class="form-control" required>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id_tipo_habitacion }}" {{ (old('id_tipo_habitacion', $hab->id_tipo_habitacion) == $tipo->id_tipo_habitacion) ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tipo }} (Capacidad: {{ $tipo->capacidad }} pers. - S/. {{ number_format($tipo->precio_noche, 2) }}/noche)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_estado_habitacion_{{ $hab->id_habitacion }}" class="font-weight-bold">Estado <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            </div>
                            <select name="id_estado_habitacion" id="id_estado_habitacion_{{ $hab->id_habitacion }}" class="form-control" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id_estado_habitacion }}" {{ (old('id_estado_habitacion', $hab->id_estado_habitacion) == $estado->id_estado_habitacion) ? 'selected' : '' }}>
                                        {{ $estado->nombre_estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark font-weight-bold">
                        <i class="fas fa-sync mr-1"></i> Actualizar Habitación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
