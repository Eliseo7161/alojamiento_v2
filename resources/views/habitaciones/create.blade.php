<div class="modal fade" id="nuevaHabitacionModal" tabindex="-1" role="dialog" aria-labelledby="nuevaHabitacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevaHabitacionModalLabel">
                    <i class="fas fa-plus-circle mr-1"></i> Nueva Habitación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('habitaciones.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="numero_habitacion" class="font-weight-bold">Número de Habitación <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                            </div>
                            <input type="text" name="numero_habitacion" id="numero_habitacion" class="form-control" placeholder="Ej: 101, 201, VIP-1" required value="{{ old('numero_habitacion') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="piso" class="font-weight-bold">Piso <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                            </div>
                            <input type="number" name="piso" id="piso" class="form-control" min="1" max="50" placeholder="Ej: 1, 2, 3..." required value="{{ old('piso', 1) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_tipo_habitacion" class="font-weight-bold">Tipo de Habitación <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-bed"></i></span>
                            </div>
                            <select name="id_tipo_habitacion" id="id_tipo_habitacion" class="form-control" required>
                                <option value="" disabled selected>Seleccione un tipo...</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id_tipo_habitacion }}" {{ old('id_tipo_habitacion') == $tipo->id_tipo_habitacion ? 'selected' : '' }}>
                                        {{ $tipo->nombre_tipo }} (Capacidad: {{ $tipo->capacidad }} pers. - S/. {{ number_format($tipo->precio_noche, 2) }}/noche)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_estado_habitacion" class="font-weight-bold">Estado Inicial <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            </div>
                            <select name="id_estado_habitacion" id="id_estado_habitacion" class="form-control" required>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id_estado_habitacion }}" {{ old('id_estado_habitacion', 1) == $estado->id_estado_habitacion ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Habitación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
