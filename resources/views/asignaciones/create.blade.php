<div class="modal fade" id="nuevaAsignacionModal" tabindex="-1" role="dialog" aria-labelledby="nuevaAsignacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevaAsignacionModalLabel">
                    <i class="fas fa-clipboard-check mr-2"></i> Nueva Asignación de Habitación (Check-In)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('asignaciones.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="id_cliente" class="font-weight-bold text-dark">
                                <i class="fas fa-user text-primary mr-1"></i> Seleccionar Cliente <span class="text-danger">*</span>
                            </label>
                            <select name="id_cliente" id="id_cliente" class="form-control" required>
                                <option value="">-- Seleccionar cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                        {{ $cliente->documento }} - {{ $cliente->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">¿No está en la lista? Regístralo en el menú Clientes.</small>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="id_habitacion" class="font-weight-bold text-dark">
                                <i class="fas fa-bed text-primary mr-1"></i> Habitación Disponible <span class="text-danger">*</span>
                            </label>
                            <select name="id_habitacion" id="id_habitacion" class="form-control" required>
                                <option value="">-- Seleccionar habitación --</option>
                                @foreach($habitacionesDisponibles as $hab)
                                    <option value="{{ $hab->id_habitacion }}" {{ old('id_habitacion') == $hab->id_habitacion ? 'selected' : '' }}>
                                        Hab. {{ $hab->numero_habitacion }} - Piso {{ $hab->piso }} ({{ $hab->tipoHabitacion->nombre_tipo ?? 'Sin tipo' }} - S/. {{ number_format($hab->tipoHabitacion->precio_noche ?? 0, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @if(count($habitacionesDisponibles) === 0)
                                <small class="text-danger font-weight-bold">No hay habitaciones disponibles en este momento.</small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="fecha_ingreso" class="font-weight-bold text-dark">
                                <i class="fas fa-calendar-alt text-primary mr-1"></i> Fecha y Hora de Ingreso <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', date('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="fecha_salida" class="font-weight-bold text-dark">
                                <i class="fas fa-calendar-check text-primary mr-1"></i> Fecha y Hora de Salida Estimada
                            </label>
                            <input type="datetime-local" name="fecha_salida" id="fecha_salida" class="form-control" value="{{ old('fecha_salida') }}">
                        </div>
                    </div>

                    <div class="alert alert-light border shadow-none mb-0">
                        <i class="fas fa-info-circle text-info mr-1"></i>
                        Al registrar la asignación, la habitación pasará automáticamente a estado <strong>OCUPADA</strong> y se asignará a tu usuario actual.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm" {{ count($habitacionesDisponibles) === 0 ? 'disabled' : '' }}>
                        <i class="fas fa-check mr-1"></i> Realizar Check-In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
