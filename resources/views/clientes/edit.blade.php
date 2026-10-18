<div class="modal fade" id="editarClienteModal{{ $cliente->id_cliente }}" tabindex="-1" role="dialog" aria-labelledby="editarClienteModalLabel{{ $cliente->id_cliente }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title font-weight-bold" id="editarClienteModalLabel{{ $cliente->id_cliente }}">
                    <i class="fas fa-user-edit mr-2"></i> Editar Cliente #{{ $cliente->id_cliente }}
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 text-left">
                    <div class="form-group mb-3">
                        <label for="documento_{{ $cliente->id_cliente }}" class="font-weight-bold text-dark">
                            <i class="fas fa-id-card text-warning mr-1"></i> Documento / DNI / Pasaporte <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="documento" id="documento_{{ $cliente->id_cliente }}" class="form-control" value="{{ old('documento', $cliente->documento) }}" required maxlength="20">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="nombre_{{ $cliente->id_cliente }}" class="font-weight-bold text-dark">
                                <i class="fas fa-user text-warning mr-1"></i> Nombres <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre_{{ $cliente->id_cliente }}" class="form-control" value="{{ old('nombre', $cliente->nombre) }}" required maxlength="50">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="apellido_{{ $cliente->id_cliente }}" class="font-weight-bold text-dark">
                                <i class="fas fa-user text-warning mr-1"></i> Apellidos <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="apellido" id="apellido_{{ $cliente->id_cliente }}" class="form-control" value="{{ old('apellido', $cliente->apellido) }}" required maxlength="50">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefono_{{ $cliente->id_cliente }}" class="font-weight-bold text-dark">
                            <i class="fas fa-phone text-warning mr-1"></i> Teléfono / Celular
                        </label>
                        <input type="text" name="telefono" id="telefono_{{ $cliente->id_cliente }}" class="form-control" value="{{ old('telefono', $cliente->telefono) }}" maxlength="20">
                    </div>

                    <div class="form-group mb-0">
                        <label for="email_{{ $cliente->id_cliente }}" class="font-weight-bold text-dark">
                            <i class="fas fa-envelope text-warning mr-1"></i> Correo Electrónico
                        </label>
                        <input type="email" name="email" id="email_{{ $cliente->id_cliente }}" class="form-control" value="{{ old('email', $cliente->email) }}" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-dark">
                        <i class="fas fa-sync-alt mr-1"></i> Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
