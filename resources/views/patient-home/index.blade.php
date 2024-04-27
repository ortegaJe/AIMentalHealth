@extends('patient-home.layouts.backend')
@section('title', 'Patient Home')
@section('header', 'Mis Citas')

@section('content')
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
                                        Fecha Cita</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                        Nombre del Profesional
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Browser: activate to sort column ascending">
                                        Hora Inicio</th>
                                    <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Platform(s): activate to sort column ascending">
                                        Sede Atención</th>
                                    {{--                                     <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                        colspan="1" aria-label="Engine version: activate to sort column ascending">
                                        Motivo</th> --}}
                                    {{--                                     <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Estado</th> --}}
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
                                        <td>{{ $appointment['name'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment['start_time'])->format('g:i A') }}</td>
                                        <td>{{ $appointment['location'] }}</td>
                                        {{--                                         <td>
                                        {{ $appointment['status'] == 0 ? 'PENDIENTE' : 'ACTIVO' }}
                                    </td> --}}
                                        <td>
                                            <button type="button"
                                                onclick="window.location='{{ route('patients.show', [$appointment->patient_id]) }}'"
                                                class="btn btn-primary">
                                                <i class="fas fa-comments"></i>
                                                Comentarios
                                            </button>
                                            <button type="button"
                                                data-toggle="modal" 
                                                data-target="#modal-show-remisions"
                                                class="btn btn-warning">
                                                <i class="fas fa-chevron-right"></i>
                                                Remisiones
                                            </button>
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
    <!-- END Appointments -->
@include('modals._show_remisions', ['remisions' => $remisions])
@endsection
