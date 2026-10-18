<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="nuevoUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="nuevoUsuarioModalLabel">
                    Nuevo Usuario
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre Completo: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required value="{{ old('name') }}" placeholder="Nombre Completo">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Email: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required value="{{ old('email') }}" placeholder="ejemplo@correo.com">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Contraseña: <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Seleccione Rol: <span class="text-danger">*</span></label>
                            <select class="form-control custom-select" name="role" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Seleccione Rol --</option>
                                @foreach($roles as $rolOption)
                                    <option value="{{ $rolOption }}" {{ old('role') === $rolOption ? 'selected' : '' }}>
                                        {{ $rolOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Estado:</label>
                            <select class="form-control custom-select" name="estado">
                                <option value="ACTIVO" {{ old('estado', 'ACTIVO') === 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                <option value="INACTIVO" {{ old('estado') === 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary px-3">
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>