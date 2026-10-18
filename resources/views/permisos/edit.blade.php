<div class="modal fade" id="editarPermisoModal{{ $permiso->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editarPermisoModalLabel{{ $permiso->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPermisoModalLabel{{ $permiso->id }}">
                    <i class="fas fa-edit mr-1"></i> Editar Permiso
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('permisos.update', $permiso->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Nombre del Permiso: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required
                               value="{{ old('name', $permiso->name) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Asignar a Roles:</label>
                        <div class="row">
                            @foreach($roles as $rol)
                                <div class="col-sm-6">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox"
                                               name="roles[]"
                                               value="{{ $rol->name }}"
                                               id="rol_edit_{{ $permiso->id }}_{{ $rol->id }}"
                                               {{ $permiso->roles->contains('name', $rol->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rol_edit_{{ $permiso->id }}_{{ $rol->id }}">
                                            {{ $rol->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            @if($roles->isEmpty())
                                <div class="col-12">
                                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>No hay roles registrados aún.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt mr-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
