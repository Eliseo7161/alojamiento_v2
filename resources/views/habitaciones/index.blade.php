@extends('adminlte::page')

@section('title', 'Gestión de Habitaciones')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark"><i class="fas fa-bed text-primary mr-2"></i> Gestión de Habitaciones</h1>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#nuevaHabitacionModal">
            <i class="fas fa-plus mr-1"></i> Nueva Habitación
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

    {{-- Filtros y Tabla principal --}}
    <div class="card shadow-sm">
        <div class="card-header border-0 bg-white py-3">
            <form method="GET" action="{{ route('habitaciones.index') }}" class="row align-items-center">
                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label class="sr-only">Tipo</label>
                    <select name="tipo_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Todos los Tipos --</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_habitacion }}" {{ request('tipo_id') == $tipo->id_tipo_habitacion ? 'selected' : '' }}>
                                {{ $tipo->nombre_tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                    <label class="sr-only">Estado</label>
                    <select name="estado_id" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Todos los Estados --</option>
                        @foreach($estados as $est)
                            <option value="{{ $est->id_estado_habitacion }}" {{ request('estado_id') == $est->id_estado_habitacion ? 'selected' : '' }}>
                                {{ $est->nombre_estado }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
                    <input type="number" name="piso" class="form-control form-control-sm" placeholder="Piso..." value="{{ request('piso') }}">
                </div>

                <div class="col-md-1 col-sm-6 mb-2 mb-md-0">
                    <button type="submit" class="btn btn-sm btn-outline-primary btn-block"><i class="fas fa-filter"></i></button>
                </div>

                @if(request()->hasAny(['tipo_id', 'estado_id', 'piso']))
                    <div class="col-md-1 col-sm-6 mb-2 mb-md-0">
                        <a href="{{ route('habitaciones.index') }}" class="btn btn-sm btn-outline-secondary btn-block" title="Limpiar filtros">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                @endif

                <div class="col-md-2 ml-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" id="tableSearch" class="form-control" placeholder="Buscar en tabla...">
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-bordered table-hover mb-0" id="habitacionesTable">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">ID</th>
                        <th style="width: 130px;">Habitación</th>
                        <th style="width: 80px;" class="text-center">Piso</th>
                        <th>Tipo de Habitación</th>
                        <th>Capacidad</th>
                        <th>Precio / Noche</th>
                        <th style="width: 170px;" class="text-center">Estado Actual</th>
                        <th style="width: 190px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($habitaciones as $hab)
                        <tr>
                            <td class="align-middle text-center text-muted font-weight-bold">
                                #{{ $hab->id_habitacion }}
                            </td>
                            <td class="align-middle font-weight-bold text-primary" style="font-size: 1.05rem;">
                                <i class="fas fa-door-closed mr-1 text-secondary"></i> {{ $hab->numero_habitacion }}
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-light border px-2 py-1 font-weight-normal">Piso {{ $hab->piso }}</span>
                            </td>
                            <td class="align-middle">
                                <strong>{{ $hab->tipoHabitacion->nombre_tipo ?? 'No asignado' }}</strong>
                            </td>
                            <td class="align-middle">
                                <i class="fas fa-user-friends text-secondary mr-1"></i>
                                {{ $hab->tipoHabitacion->capacidad ?? '-' }} personas
                            </td>
                            <td class="align-middle font-weight-bold text-success">
                                S/. {{ number_format($hab->tipoHabitacion->precio_noche ?? 0, 2) }}
                            </td>
                            <td class="align-middle text-center">
                                @php
                                    $colorBadge = 'badge-secondary';
                                    $estadoNombre = strtoupper($hab->estadoHabitacion->nombre_estado ?? 'DISPONIBLE');
                                    if ($estadoNombre === 'DISPONIBLE') $colorBadge = 'badge-success';
                                    elseif ($estadoNombre === 'OCUPADA') $colorBadge = 'badge-danger';
                                    elseif ($estadoNombre === 'EN LIMPIEZA') $colorBadge = 'badge-warning text-dark';
                                    elseif ($estadoNombre === 'MANTENIMIENTO') $colorBadge = 'badge-secondary';
                                    elseif ($estadoNombre === 'RESERVADA') $colorBadge = 'badge-info';
                                @endphp

                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-xs dropdown-toggle badge {{ $colorBadge }} px-2 py-1 font-weight-bold" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 0.85rem; border: none; cursor: pointer;">
                                        {{ $hab->estadoHabitacion->nombre_estado ?? 'DISPONIBLE' }}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm p-1">
                                        <h6 class="dropdown-header py-1 font-weight-bold">Cambiar estado a:</h6>
                                        @foreach($estados as $estOption)
                                            <form action="{{ route('habitaciones.cambiar-estado', $hab->id_habitacion) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="id_estado_habitacion" value="{{ $estOption->id_estado_habitacion }}">
                                                <button type="submit" class="dropdown-item py-1 text-sm {{ $hab->id_estado_habitacion == $estOption->id_estado_habitacion ? 'active font-weight-bold' : '' }}">
                                                    <i class="fas fa-circle mr-1" style="color: {{ $estOption->color_hex ?? '#6c757d' }}; font-size: 0.7rem;"></i>
                                                    {{ $estOption->nombre_estado }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle text-center text-nowrap">
                                <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                    <button type="button" 
                                            class="btn btn-sm btn-warning text-dark font-weight-normal" 
                                            data-toggle="modal" 
                                            data-target="#editarHabitacionModal{{ $hab->id_habitacion }}"
                                            title="Editar Habitación">
                                        <i class="fas fa-edit mr-1"></i> Editar
                                    </button>

                                    <form action="{{ route('habitaciones.destroy', $hab->id_habitacion) }}" method="POST" class="d-inline form-eliminar m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger font-weight-normal" title="Eliminar Habitación">
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar individual -->
                        @include('habitaciones.edit', ['hab' => $hab, 'tipos' => $tipos, 'estados' => $estados])
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-bed fa-2x mb-2 d-block text-gray-300"></i>
                                No se encontraron habitaciones registradas con los criterios seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    @include('habitaciones.create', ['tipos' => $tipos, 'estados' => $estados])
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Filtro de búsqueda en tiempo real sobre la tabla
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#habitacionesTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Alerta SweetAlert2 de éxito (Guardar, Editar, Eliminar)
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

        // Confirmación antes de eliminar
        $(document).on('submit', '.form-eliminar', function (e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '¿Está seguro?',
                text: "¡No podrá revertir esta acción!",
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
