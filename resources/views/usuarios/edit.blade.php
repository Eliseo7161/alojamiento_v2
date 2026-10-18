@php
    $idUsuario = $usuario->id_usuario ?? $usuario->id;
    $currentRole = strtolower($usuario->role ?? $usuario->rol->nombre_rol ?? '');
    $currentEstado = strtoupper($usuario->estado ?? 'ACTIVO');
@endphp

<div class="modal fade" id="editarUsuarioModal{{ $idUsuario }}" tabindex="-1" role="dialog"
    aria-labelledby="editarUsuarioModalLabel{{ $idUsuario }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="editarUsuarioModalLabel{{ $idUsuario }}">
                    Editar Usuario
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('usuarios.update', $idUsuario) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre Completo: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required value="{{ old('name', $usuario->name) }}" placeholder="Nombre Completo">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Email: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required value="{{ old('email', $usuario->email) }}" placeholder="ejemplo@correo.com">
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Contraseña:</label>
                            <input type="password" class="form-control" name="password" placeholder="">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Seleccione Rol: <span class="text-danger">*</span></label>
                            <select class="form-control custom-select" name="role" required>
                                @foreach($roles as $rolOption)
                                    <option value="{{ $rolOption }}" {{ strtolower($rolOption) === $currentRole ? 'selected' : '' }}>
                                        {{ $rolOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="font-weight-bold">Estado:</label>
                            <select class="form-control custom-select" name="estado">
                                <option value="ACTIVO" {{ $currentEstado === 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                                <option value="INACTIVO" {{ $currentEstado === 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary px-3">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>