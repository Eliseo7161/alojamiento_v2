<div class="modal fade" id="modalNuevaCompra" tabindex="-1" role="dialog" aria-labelledby="modalNuevaCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalNuevaCompraLabel">
                    <i class="fas fa-cart-plus mr-2"></i> Registrar Entrada de Compra (Abastecimiento)
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('compras.store') }}" method="POST" id="formCompraModal">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Datos de Cabecera -->
                        <div class="col-lg-4 col-md-5">
                            <div class="card shadow-sm border mb-3">
                                <div class="card-header bg-light font-weight-bold text-dark">
                                    <i class="fas fa-info-circle text-primary mr-1"></i> Información General
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Proveedor <span class="text-danger">*</span></label>
                                        <select name="id_proveedor" class="form-control" required>
                                            <option value="">-- Seleccionar Proveedor --</option>
                                            @foreach($proveedores as $prov)
                                                <option value="{{ $prov->id_proveedor }}">
                                                    {{ $prov->razon_social }} (NIT: {{ $prov->nit_ruc }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Fecha y Hora <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="fecha_compra" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold text-dark">Método de Pago <span class="text-danger">*</span></label>
                                        <select name="metodo_pago" class="form-control font-weight-bold" required>
                                            <option value="Efectivo">💵 Efectivo (Caja Chica)</option>
                                            <option value="Transferencia / QR">📱 Transferencia / QR</option>
                                            <option value="Tarjeta de Débito/Crédito">💳 Tarjeta Débito / Crédito</option>
                                        </select>
                                    </div>

                                    <div class="alert alert-info py-2 mb-0">
                                        <small><i class="fas fa-info-circle mr-1"></i> Incrementará el stock automáticamente y registrará el EGRESO en Caja.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selector y Detalle de Productos -->
                        <div class="col-lg-8 col-md-7">
                            <div class="card shadow-sm border mb-3">
                                <div class="card-header bg-light font-weight-bold text-dark">
                                    <i class="fas fa-boxes text-primary mr-1"></i> Agregar Productos a la Compra
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-end mb-3">
                                        <div class="col-md-5 mb-2 mb-md-0">
                                            <label class="font-weight-bold text-dark">Seleccionar Producto</label>
                                            <select id="modal_compra_prod_select" class="form-control">
                                                <option value="">-- Elegir Producto --</option>
                                                @foreach($productos as $prod)
                                                    <option value="{{ $prod->id_producto }}" data-nombre="{{ $prod->nombre_producto }}">
                                                        {{ $prod->nombre_producto }} (Stock actual: {{ $prod->stock->cantidad_actual ?? 0 }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2 mb-md-0">
                                            <label class="font-weight-bold text-dark">Cantidad</label>
                                            <input type="number" id="modal_compra_prod_cant" class="form-control" value="1" min="1">
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="font-weight-bold text-dark">Costo Unit. (Bs.)</label>
                                            <input type="number" step="0.01" id="modal_compra_prod_costo" class="form-control" placeholder="0.00">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="modal_compra_btn_agregar" class="btn btn-success btn-block font-weight-bold">
                                                <i class="fas fa-plus"></i> Añadir
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Tabla de Ítems a Comprar -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th style="width: 100px;" class="text-center">Cant.</th>
                                                    <th style="width: 130px;" class="text-right">Costo Unit.</th>
                                                    <th style="width: 130px;" class="text-right">Subtotal</th>
                                                    <th style="width: 60px;" class="text-center">Quitar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="modalCompraDetallesBody">
                                                <tr id="modalCompraEmptyRow">
                                                    <td colspan="5" class="text-center py-4 text-muted">
                                                        No has agregado ningún producto a la compra todavía.
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-light">
                                                    <th colspan="3" class="text-right font-weight-bold" style="font-size: 1.05rem;">TOTAL COMPRA:</th>
                                                    <th class="text-right font-weight-bold text-danger" style="font-size: 1.2rem;" id="modalTotalCompraDisplay">Bs. 0.00</th>
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
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm" id="modalBtnGuardarCompra" disabled>
                        <i class="fas fa-check-circle mr-1"></i> Confirmar y Guardar Compra
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
