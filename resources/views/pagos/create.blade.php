<div class="modal fade" id="nuevoPagoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoPagoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevoPagoModalLabel">
                    <i class="fas fa-coins mr-2"></i> Registrar Movimiento Manual
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pagos.store') }}" method="POST" class="form-confirmar-pago">
                @csrf
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Tipo de Transacción <span class="text-danger">*</span></label>
                        <select name="tipo_transaccion" class="form-control font-weight-bold" required>
                            <option value="INGRESO">🟢 INGRESO (Cobro / Entrada de Dinero)</option>
                            <option value="EGRESO">🔴 EGRESO (Gasto / Salida de Dinero)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Monto (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="monto" class="form-control font-weight-bold" placeholder="0.00" required min="0.01">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Método de Pago <span class="text-danger">*</span></label>
                        <select name="metodo_pago" class="form-control" required>
                            <option value="Efectivo">💵 Efectivo</option>
                            <option value="Transferencia / QR">📱 QR / Transferencia Bancaria</option>
                            <option value="Tarjeta de Débito/Crédito">💳 Tarjeta Débito / Crédito</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Asociar a Hospedaje (Opcional)</label>
                        <select name="id_asignacion" class="form-control">
                            <option value="">-- Ninguno (Gasto general o cobro independiente) --</option>
                            @foreach($asignacionesActivas as $asig)
                                <option value="{{ $asig->id_asignacion }}">
                                    Hab. {{ $asig->habitacion->numero_habitacion }} - {{ $asig->cliente->nombre }} {{ $asig->cliente->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_pago" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
