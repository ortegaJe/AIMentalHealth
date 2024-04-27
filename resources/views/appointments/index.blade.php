@extends('layouts.backend')
@section('title', 'Appointment Management')
@section('header', 'Lista de Citas')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>{{ \App\Models\Patient::count() }}</h3>
                    <p>Pacientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-light">
                <div class="inner">
                    <h3>{{ $appointments->count() }}</h3>
                    <p>Citas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Appointments -->
    <div class="card">
        <div class="card-body">
            <div id="appointment_table_wrapper" class="dataTables_wrapper dt-bootstrap4">

                <div class="row">
                    <div class="col-sm-12">
                        <table id="appointment_table" class="table table-bordered table-striped dataTable dtr-inline"
                            role="grid" aria-describedby="example1_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending">
                                        Fecha Cita
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Browser: activate to sort column ascending">
                                        Hora Inicio
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                        Identificación
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                        Paciente
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Engine version: activate to sort column ascending">
                                        Estado
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Engine version: activate to sort column ascending">
                                        Motivo
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp

                                @foreach ($appointments as $appointment)
                                    <tr role="row" class="{{ $counter % 2 == 0 ? 'even' : 'odd' }}">
                                        <td class="dtr-control sorting_1" tabindex="0">
                                            {{ \Carbon\Carbon::parse($appointment['date'])->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($appointment['start_time'])->format('g:i A') }}
                                        </td>
                                        <td>{{ $appointment['identification'] }}</td>
                                        <td>{{ $appointment['full_name'] }}</td>
                                        <td>
                                            @if ($appointment['risk'] === 'riesgo bajo')
                                            <span class="badge badge-primary">{{ Str::ucfirst($appointment['risk']) }}</span>
                                            @endif
                                            @if ($appointment['risk'] === 'riesgo moderado')
                                            <span class="badge badge-warning">{{ Str::ucfirst($appointment['risk']) }}</span>
                                            @endif
                                            @if ($appointment['risk'] === 'riesgo alto')
                                            <span class="badge badge-danger">{{ Str::ucfirst($appointment['risk']) }}</span>
                                            @endif
                                            @if ($appointment['risk'] === 'precisa ingreso')
                                            <span class="badge badge-danger">{{ Str::ucfirst($appointment['risk']) }}</span>
                                            @endif
                                        </td>
                                        <td class="truncate">{{ $appointment['motivation'] }}</td>
                                        {{-- <td>
                                            {{ $appointment['status'] == 0 ? 'PENDIENTE' : 'ACTIVO' }}
                                        </td> --}}
                                        <td>
                                            @if (\App\Enums\UserRoles::isDoctor(Auth::user()->role) || \App\Enums\UserRoles::isAdmin(Auth::user()->role))
                                                <button type="button" class="btn btn-block btn-danger"
                                                    onclick="window.location='{{ route('patients.show', [$appointment->patient_id]) }}'">
                                                    <i class="fas fa-notes-medical"></i>
                                                    Evaluación Psicológica
                                                </button>
                                            @endif
                                        </td>
                                        {{-- <td
                                            style="padding-right: -3.25rem;border-right-width: 0px;height: 37px;width: 95.833px;">
                                            TBD later
                                             <a href="{{ route('scans.show', [$appointment->id]) }}"
                                            class="btn btn-profile btn-del"
                                            style="height: 41px;min-width: 46px;margin: 0px;padding: 0px;"
                                            title="preview"><i class="fas fa-external-link-alt"></i></a>

                                        <a href="{{ route('scans.download', $appointment->id) }}"
                                            class="btn btn-app btn-modify"
                                            style="height: 41px;min-width: 46px;margin: 0px;padding: 0px;">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        </td> --}}
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <div class="add-btn-container">
        <button class="Btn" data-toggle="modal" data-target="#modal_add_appointment">
            <div class="sign">
                <svg viewBox="0 -0.5 9 9" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <title>plus_mini [#ffffff]</title>
                        <desc>Created with Sketch.</desc>
                        <defs> </defs>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Dribbble-Light-Preview" transform="translate(-345.000000, -206.000000)" fill="#ffffff">
                                <g id="icons" transform="translate(56.000000, 160.000000)">
                                    <polygon id="plus_mini-[#ffffff]"
                                        points="298 49 298 51 294.625 51 294.625 54 292.375 54 292.375 51 289 51 289 49 292.375 49 292.375 46 294.625 46 294.625 49">
                                    </polygon>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="text">Cita</div>
        </button>
    </div>

    <!-- END Appointments -->
    @include('modals._add_appointment')
@endsection
