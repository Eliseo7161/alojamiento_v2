@php
    $permisosAgrupados = $permissions->groupBy(function($item) {
        $partes = explode(' ', trim($item->name));
        return count($partes) > 1 ? $partes[0] : 'General';
    });
@endphp

<div class="modal fade" id="editarRolModal{{ $rol->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editarRolModalLabel{{ $rol->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal" id="editarRolModalLabel{{ $rol->id }}">
                    Editar Permisos del Rol: <strong>{{ $rol->name }}</strong>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('roles.update', $rol->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                    @forelse($permisosAgrupados as $modulo => $listaPermisos)
                        <div class="mb-3">
                            <div class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                Permisos de {{ $modulo }}
                            </div>
                            <div class="d-flex flex-wrap align-items-center" style="column-gap: 20px; row-gap: 8px;">
                                @foreach($listaPermisos as $permission)
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               id="perm_edit_{{ $rol->id }}_{{ $permission->id }}"
                                               {{ $rol->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-normal text-dark" for="perm_edit_{{ $rol->id }}_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info py-2 mb-0">
                            <small><i class="fas fa-info-circle mr-1"></i> No hay permisos registrados en el sistema.</small>
                        </div>
                    @endforelse
                </div>

                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary px-3">
                        Actualizar Permisos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
