<div class="modal fade" id="editarAsignacionModal{{ $asig->id_asignacion }}" tabindex="-1" role="dialog" aria-labelledby="editarAsignacionModalLabel{{ $asig->id_asignacion }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold" id="editarAsignacionModalLabel{{ $asig->id_asignacion }}">
                    <i class="fas fa-edit mr-2"></i> Modificar Asignación #{{ $asig->id_asignacion }}
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('asignaciones.update', $asig->id_asignacion) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-left">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_cliente_{{ $asig->id_asignacion }}" class="font-weight-bold text-dark">
                                <i class="fas fa-user text-warning mr-1"></i> Cliente <span class="text-danger">*</span>
                            </label>
                            <select name="id_cliente" id="edit_cliente_{{ $asig->id_asignacion }}" class="form-control" required>
                                @foreach($clientes as $cli)
                                    <option value="{{ $cli->id_cliente }}" {{ $asig->id_cliente == $cli->id_cliente ? 'selected' : '' }}>
                                        {{ $cli->documento }} - {{ $cli->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="edit_hab_{{ $asig->id_asignacion }}" class="font-weight-bold text-dark">
                                <i class="fas fa-bed text-warning mr-1"></i> Habitación <span class="text-danger">*</span>
                            </label>
                            <select name="id_habitacion" id="edit_hab_{{ $asig->id_asignacion }}" class="form-control" required>
                                @foreach($todasHabitaciones as $h)
                                    <option value="{{ $h->id_habitacion }}" {{ $asig->id_habitacion == $h->id_habitacion ? 'selected' : '' }}>
                                        Hab. {{ $h->numero_habitacion }} - Piso {{ $h->piso }} ({{ $h->tipoHabitacion->nombre_tipo ?? 'Sin tipo' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="edit_ingreso_{{ $asig->id_asignacion }}" class="font-weight-bold text-dark">
                                <i class="fas fa-calendar-alt text-warning mr-1"></i> Fecha Ingreso <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" name="fecha_ingreso" id="edit_ingreso_{{ $asig->id_asignacion }}" class="form-control" value="{{ $asig->fecha_ingreso ? $asig->fecha_ingreso->format('Y-m-d\TH:i') : '' }}" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="edit_salida_{{ $asig->id_asignacion }}" class="font-weight-bold text-dark">
                                <i class="fas fa-calendar-check text-warning mr-1"></i> Fecha Salida
                            </label>
                            <input type="datetime-local" name="fecha_salida" id="edit_salida_{{ $asig->id_asignacion }}" class="form-control" value="{{ $asig->fecha_salida ? $asig->fecha_salida->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label for="edit_estado_{{ $asig->id_asignacion }}" class="font-weight-bold text-dark">
                                <i class="fas fa-tag text-warning mr-1"></i> Estado <span class="text-danger">*</span>
                            </label>
                            <select name="estado" id="edit_estado_{{ $asig->id_asignacion }}" class="form-control font-weight-bold" required>
                                <option value="Activa" {{ $asig->estado === 'Activa' ? 'selected' : '' }}>🟢 Activa (Ocupada)</option>
                                <option value="Finalizada" {{ $asig->estado === 'Finalizada' ? 'selected' : '' }}>🔵 Finalizada (Check-out)</option>
                                <option value="Cancelada" {{ $asig->estado === 'Cancelada' ? 'selected' : '' }}>🔴 Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-dark">
                        <i class="fas fa-sync-alt mr-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
