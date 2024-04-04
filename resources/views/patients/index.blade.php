@extends('layouts.backend')
@section('title', 'Patients Management')
@section('header', 'Lista de Pacientes')

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="patients_table_wrapper" class="dataTables_wrapper dt-bootstrap4">

                <div class="row">
                    <div class="col-sm-12">
                        <table id="patients_table" class="table table-bordered table-striped dataTable dtr-inline"
                            role="grid" aria-describedby="patients_table_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="patients_table" rowspan="1"
                                        colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending">
                                        Nombre</th>
                                    <th class="sorting" tabindex="0" aria-controls="patients_table" rowspan="1"
                                        colspan="1" aria-label="Platform(s): activate to sort column ascending">
                                        Fecha Nacimiento</th>
                                    <th class="sorting" tabindex="0" aria-controls="patients_table" rowspan="1"
                                        colspan="1" aria-label="Engine version: activate to sort column ascending">
                                        Telefono</th>
                                    <th class="sorting" tabindex="0" aria-controls="patients_table" rowspan="1"
                                        colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                        Email</th>
                                    <th rowspan="1" colspan="1">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp

                                @foreach ($patients as $patient)
                                    <tr role="row" class="{{ $counter % 2 == 0 ? 'even' : 'odd' }}">
                                        <td class="dtr-control sorting_1" tabindex="0">
                                            {{ $patient['full_name'] }}</td>
                                        <td>{{ $patient['dob'] }}</td>
                                        <td>{{ $patient['phone'] }}</td>
                                        <td>{{ $patient['email'] }}</td>
                                        <td>
                                            <button type="button" onclick="window.location='{{ route('patients.show', [$patient]) }}'"
                                                class="btn btn-primary">
                                                <i class="fas fa-notes-medical"></i>
                                            </button>

                                            <button type="button" onclick="window.location='{{ route('patients.edit', [$patient]) }}'"
                                                class="btn btn-success">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </td>
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
            <div class="add-btn-container">
                <button class="Btn" data-toggle="modal" data-target="#modal-add-patient">
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
                    <div class="text">Add</div>
                </button>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    @include('modals._add_patient')

@endsection
