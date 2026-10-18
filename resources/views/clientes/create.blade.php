<div class="modal fade" id="nuevoClienteModal" tabindex="-1" role="dialog" aria-labelledby="nuevoClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="nuevoClienteModalLabel">
                    <i class="fas fa-user-plus mr-2"></i> Registrar Nuevo Cliente
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label for="documento" class="font-weight-bold text-dark">
                            <i class="fas fa-id-card text-primary mr-1"></i> Documento / DNI / Pasaporte <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="documento" id="documento" class="form-control" placeholder="Ej: 74839201" value="{{ old('documento') }}" required maxlength="20">
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="nombre" class="font-weight-bold text-dark">
                                <i class="fas fa-user text-primary mr-1"></i> Nombres <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Juan Carlos" value="{{ old('nombre') }}" required maxlength="50">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label for="apellido" class="font-weight-bold text-dark">
                                <i class="fas fa-user text-primary mr-1"></i> Apellidos <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="apellido" id="apellido" class="form-control" placeholder="Ej: Pérez Gómez" value="{{ old('apellido') }}" required maxlength="50">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="telefono" class="font-weight-bold text-dark">
                            <i class="fas fa-phone text-primary mr-1"></i> Teléfono / Celular
                        </label>
                        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Ej: 987654321" value="{{ old('telefono') }}" maxlength="20">
                    </div>

                    <div class="form-group mb-0">
                        <label for="email" class="font-weight-bold text-dark">
                            <i class="fas fa-envelope text-primary mr-1"></i> Correo Electrónico
                        </label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Ej: cliente@correo.com" value="{{ old('email') }}" maxlength="100">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
