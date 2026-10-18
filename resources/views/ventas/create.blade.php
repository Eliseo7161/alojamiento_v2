<div class="modal fade" id="modalNuevaVenta" tabindex="-1" role="dialog" aria-labelledby="modalNuevaVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold" id="modalNuevaVentaLabel">
                    <i class="fas fa-cash-register mr-2"></i> Registrar Nueva Venta (Punto de Venta)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ventas.store') }}" method="POST" id="formVentaModal">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Destino de la Venta -->
                        <div class="col-lg-4 col-md-5">
                            <div class="card shadow-sm border mb-3">
                                <div class="card-header bg-light font-weight-bold text-dark">
                                    <i class="fas fa-user-check text-success mr-1"></i> Destino del Cobro
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Tipo de Venta <span class="text-danger">*</span></label>
                                        <div class="d-flex" style="gap: 15px;">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="modal_tipoPublico" name="tipo_venta" value="publico" class="custom-control-input" checked>
                                                <label class="custom-control-label font-weight-normal" for="modal_tipoPublico">Público General</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="modal_tipoHuesped" name="tipo_venta" value="huesped" class="custom-control-input">
                                                <label class="custom-control-label font-weight-normal" for="modal_tipoHuesped">Cargar a Huésped</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bloque para Huésped -->
                                    <div class="form-group mb-3" id="modal_bloqueHuesped" style="display: none;">
                                        <label class="font-weight-bold text-dark">Seleccionar Habitación / Huésped <span class="text-danger">*</span></label>
                                        <select name="id_asignacion" class="form-control font-weight-bold">
                                            <option value="">-- Habitaciones Activas --</option>
                                            @foreach($asignacionesActivas as $asig)
                                                <option value="{{ $asig->id_asignacion }}">
                                                    Hab. {{ $asig->habitacion->numero_habitacion ?? 'S/N' }} - {{ $asig->cliente->nombre ?? '' }} {{ $asig->cliente->apellido ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Bloque para Público General -->
                                    <div class="form-group mb-3" id="modal_bloquePublico">
                                        <label class="font-weight-bold text-dark">Cliente Opcional (o Mostrador)</label>
                                        <select name="id_cliente" class="form-control">
                                            <option value="">-- Mostrador / Venta Rápida --</option>
                                            @foreach($clientes as $cli)
                                                <option value="{{ $cli->id_cliente }}">
                                                    {{ $cli->nombre }} {{ $cli->apellido }} (Doc: {{ $cli->documento }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Método de Cobro <span class="text-danger">*</span></label>
                                        <select name="metodo_pago" class="form-control font-weight-bold" required>
                                            <option value="Efectivo">💵 Efectivo</option>
                                            <option value="Transferencia / QR">📱 QR / Transferencia</option>
                                            <option value="Tarjeta de Débito/Crédito">💳 Tarjeta Débito / Crédito</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Fecha y Hora</label>
                                        <input type="datetime-local" name="fecha_venta" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>

                                    <div class="bg-light p-2 rounded border border-light mb-0 text-muted small">
                                        <i class="fas fa-info-circle text-info mr-1"></i> Descontará el stock y registrará el INGRESO en la caja automáticamente.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selector y Detalle de Productos -->
                        <div class="col-lg-8 col-md-7">
                            <div class="card shadow-sm border mb-3">
                                <div class="card-header bg-light font-weight-bold text-dark">
                                    <i class="fas fa-boxes text-success mr-1"></i> Agregar Productos a la Venta
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-end mb-3">
                                        <div class="col-md-5 mb-2 mb-md-0">
                                            <label class="font-weight-bold text-dark">Producto con Stock</label>
                                            <select id="modal_venta_prod_select" class="form-control">
                                                <option value="">-- Seleccionar Producto --</option>
                                                @foreach($productos as $prod)
                                                    <option value="{{ $prod->id_producto }}" 
                                                            data-nombre="{{ $prod->nombre_producto }}" 
                                                            data-precio="{{ $prod->precio_venta }}"
                                                            data-stock="{{ $prod->stock->cantidad_actual ?? 0 }}">
                                                        {{ $prod->nombre_producto }} (Stock: {{ $prod->stock->cantidad_actual ?? 0 }} | Bs. {{ number_format($prod->precio_venta, 2) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="font-weight-bold text-dark">Cantidad</label>
                                            <input type="number" id="modal_venta_prod_cant" class="form-control" value="1" min="1">
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="font-weight-bold text-dark">Precio (Bs.)</label>
                                            <input type="number" step="0.01" id="modal_venta_prod_precio" class="form-control" placeholder="0.00" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="modal_venta_btn_agregar" class="btn btn-success btn-block font-weight-bold">
                                                <i class="fas fa-plus"></i> Añadir
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tabla de Ítems a Vender -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th style="width: 100px;" class="text-center">Cant.</th>
                                                    <th style="width: 130px;" class="text-right">Precio Unit.</th>
                                                    <th style="width: 130px;" class="text-right">Subtotal</th>
                                                    <th style="width: 60px;" class="text-center">Quitar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="modalVentaDetallesBody">
                                                <tr id="modalVentaEmptyRow">
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        No has agregado ningún producto a la venta todavía.
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-light">
                                                    <th colspan="3" class="text-right font-weight-bold" style="font-size: 1.05rem;">TOTAL A COBRAR:</th>
                                                    <th class="text-right font-weight-bold text-success" style="font-size: 1.25rem;" id="modalTotalVentaDisplay">Bs. 0.00</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success font-weight-bold shadow-sm" id="modalBtnGuardarVenta" disabled>
                        <i class="fas fa-check-circle mr-1"></i> Finalizar y Cobrar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
