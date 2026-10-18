@extends('adminlte::page')

@section('title', 'Asignaciones de Habitación')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-clipboard-list text-primary mr-2"></i> Asignaciones de Habitación (Hospedaje)</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevaAsignacionModal">
            <i class="fas fa-plus mr-1"></i> Nueva Asignación (Check-In)
        </button>
    </div>
@stop

@section('content')
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong><i class="fas fa-exclamation-triangle mr-1"></i> Por favor corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header border-0 bg-white py-3">
            <form method="GET" action="{{ route('asignaciones.index') }}" class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="estado" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Todos los Estados --</option>
                        <option value="Activa" {{ request('estado') === 'Activa' ? 'selected' : '' }}>🟢 Activa (Ocupando)</option>
                        <option value="Finalizada" {{ request('estado') === 'Finalizada' ? 'selected' : '' }}>🔵 Finalizada</option>
                        <option value="Cancelada" {{ request('estado') === 'Cancelada' ? 'selected' : '' }}>🔴 Cancelada</option>
                    </select>
                </div>

                @if(request()->has('estado') && request('estado') != '')
                    <div class="col-md-1 mb-2 mb-md-0">
                        <a href="{{ route('asignaciones.index') }}" class="btn btn-sm btn-outline-secondary btn-block" title="Limpiar filtro">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                @endif

                <div class="col-md-3 ml-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Buscar cliente, hab, doc...">
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="asignacionesTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th style="width: 120px;">Habitación</th>
                        <th>Cliente</th>
                        <th style="width: 150px;">Ingreso</th>
                        <th style="width: 150px;">Salida</th>
                        <th style="width: 130px;" class="text-center">Estado</th>
                        <th>Recepcionista</th>
                        <th style="width: 220px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones as $asig)
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">
                                #{{ $asig->id_asignacion }}
                            </td>
                            <td class="align-middle font-weight-bold text-primary" style="font-size: 1.05rem;">
                                <i class="fas fa-bed mr-1 text-secondary"></i> Hab. {{ $asig->habitacion->numero_habitacion ?? 'N/A' }}
                                <div class="text-muted text-xs font-weight-normal">
                                    {{ $asig->habitacion->tipoHabitacion->nombre_tipo ?? '' }}
                                </div>
                            </td>
                            <td class="align-middle">
                                <strong>{{ $asig->cliente->nombre_completo ?? 'Cliente eliminado' }}</strong>
                                <div class="text-muted text-xs">
                                    <i class="fas fa-id-card mr-1"></i> {{ $asig->cliente->documento ?? '-' }}
                                    @if($asig->cliente && $asig->cliente->telefono)
                                        | <i class="fas fa-phone mr-1"></i> {{ $asig->cliente->telefono }}
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light border">
                                    <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                    {{ $asig->fecha_ingreso ? $asig->fecha_ingreso->format('d/m/Y H:i') : '-' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                @if($asig->fecha_salida)
                                    <span class="badge badge-light border">
                                        <i class="fas fa-calendar-check text-muted mr-1"></i>
                                        {{ $asig->fecha_salida->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="badge badge-warning text-dark font-weight-normal">Pendiente</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">
                                @if($asig->estado === 'Activa')
                                    <span class="badge badge-success px-2 py-1 font-weight-bold">
                                        <i class="fas fa-door-open mr-1"></i> Activa
                                    </span>
                                @elseif($asig->estado === 'Finalizada')
                                    <span class="badge badge-primary px-2 py-1 font-weight-bold">
                                        <i class="fas fa-check-circle mr-1"></i> Finalizada
                                    </span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                                        <i class="fas fa-ban mr-1"></i> Cancelada
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle text-muted text-sm">
                                <i class="fas fa-user-circle mr-1"></i> {{ $asig->usuario->name ?? 'Sistema' }}
                            </td>
                            <td class="align-middle text-center text-nowrap">
                                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                    @if($asig->estado === 'Activa')
                                        <form action="{{ route('asignaciones.finalizar', $asig->id_asignacion) }}" method="POST" class="d-inline m-0 form-finalizar">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-info font-weight-normal" title="Realizar Check-Out">
                                                <i class="fas fa-sign-out-alt mr-1"></i> Check-Out
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button" 
                                            class="btn btn-sm btn-warning text-dark font-weight-normal" 
                                            data-toggle="modal" 
                                            data-target="#editarAsignacionModal{{ $asig->id_asignacion }}"
                                            title="Editar Asignación">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </button>

                                    <form action="{{ route('asignaciones.destroy', $asig->id_asignacion) }}" method="POST" class="d-inline form-eliminar m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Eliminar Registro">
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar individual -->
                        @include('asignaciones.edit', [
                            'asig' => $asig, 
                            'clientes' => $clientes, 
                            'todasHabitaciones' => $todasHabitaciones
                        ])
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-2x mb-2 d-block text-gray-300"></i>
                                No se encontraron asignaciones de habitación registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    @include('asignaciones.create', [
        'clientes' => $clientes, 
        'habitacionesDisponibles' => $habitacionesDisponibles
    ])
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Filtro de búsqueda en tiempo real sobre la tabla
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#asignacionesTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Alerta SweetAlert2 de éxito (Guardar, Editar, Eliminar, Finalizar)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif

        // Alerta SweetAlert2 de error
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @endif

        // Confirmación para Check-Out
        $(document).on('submit', '.form-finalizar', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '¿Confirmar Check-Out?',
                text: "Se registrará la fecha/hora de salida y la habitación pasará a estado de limpieza.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, finalizar hospedaje',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Confirmación antes de eliminar
        $(document).on('submit', '.form-eliminar', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '¿Está seguro?',
                text: "Se eliminará esta asignación de hospedaje.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@stop
