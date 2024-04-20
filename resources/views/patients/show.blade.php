@extends('layouts.backend')
@section('title', 'Patients Management')
@section('header', 'Perfil del Paciente')

@push('css')
    <style>
        #infochat {
            border: 1px solid #ccc;
            padding: 5px;
            width: 100%;
            height: 300px;
            overflow-y: scroll;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- info non medical  box -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Información del Paciente</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="card-body">
                        <form class="needs-validation" method="post" action="{{ route('patients.update', [$patient]) }}"
                            novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="identification">Número de Identificación</label>
                                <input id="identification" name="identification" type="number" class=" form-control"
                                    value="{{ old('identification', $patient->identification) }}" />
                            </div>

                            <div class="form-group">
                                <label for="full_name">Nombres y Apellidos</label>
                                <input id="full_name" name="full_name" type="text" class="form-control" placeholder=" "
                                    value="{{ old('full_name', $patient->full_name) }}" />
                            </div>

                            <div class="form-group">
                                <label for="age">Edad</label>
                                <input id="age" name="age" type="text" data-inputmask="'mask': ['99']"
                                    data-mask class="form-control" value="{{ old('age', $patient->age) }}" />
                            </div>
                            <div class="form-group">
                                <label>Genero</label>
                                <select class="form-control">
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                    <option value="ND">No definido</option>
                                </select>
                            </div>
                            @php
                                Carbon\Carbon::parse($patient->dob)->format('m/d/Y');
                            @endphp
                            <div class="form-group">
                                <label for="dob">Fecha de Nacimiento</label>
                                <input type="date" name="dob" id="dob" class="form-control"
                                    value="{{ old('dob', Carbon\Carbon::parse($patient->dob)->format('m/d/Y')) }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input id="address" name="address" type="text" class="form-control" placeholder=" "
                                    value="{{ old('address', $patient->address) }}" />
                            </div>

                            <div class="form-group">
                                <label for="neighborhood">Barrio</label>
                                <input id="neighborhood" name="neighborhood" type="text" class="form-control"
                                    placeholder=" " value="{{ old('neighborhood', $patient->neighborhood) }}" />
                            </div>

                            <div class="form-group">
                                <label for="city">Ciudad</label>
                                <input id="city" name="city" type="text" class="form-control" placeholder=" "
                                    value="{{ old('city', $patient->city) }}" />
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="text" class="form-control"
                                    value="{{ old('email', $patient->email) }}" />
                            </div>


                            <div class="form-group">
                                <label for="phone">Telefono</label>
                                <input id="phone" name="phone" type="text" class="form-control"
                                    value="{{ old('phone', $patient->phone) }}" />
                            </div>

                            <div class="form-group">
                                <label>Programa</label>
                                <div class="model-field__control">
                                    <select name='program_id' class="form-control select2-program-ajax">
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id_program }}" selected>{{ $program->program }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cuatrimestre">Cuatrimestre</label>
                                <input id="cuatrimestre" name="cuatrimestre" type="text" data-inputmask="'mask': ['9']"
                                    data-mask class="form-control"
                                    value="{{ old('cuatrimestre', $patient->cuatrimestre) }}" />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Actualizar</button>
                            </div>
                        </form>
                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /. info non medical -->

            <!-- Appointment  box -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Citas</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="appointment_table"
                                    class="table table-bordered table-striped dataTable dtr-inline" role="grid"
                                    aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending">
                                                Fecha</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                Hora Inicio</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1"
                                                aria-label="Platform(s): activate to sort column ascending">
                                                Hora Final</th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                Motivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp

                                        @foreach ($appointments as $appointment)
                                            <tr role="row" class="{{ $counter % 2 == 0 ? 'even' : 'odd' }}">
                                                <td class="dtr-control sorting_1" tabindex="0">
                                                    {{ $appointment['date'] }}</td>
                                                <td>{{ $appointment['start_time'] }}</td>
                                                <td>{{ $appointment['end_time'] }}</td>
                                                <td class="truncate">{{ $appointment['motivation'] }}</td>
                                                {{-- <a href="{{ route('scans.show', [$appointment->id]) }}"
                                            class="btn btn-profile btn-del"
                                            style="height: 41px;min-width: 46px;margin: 0px;padding: 0px;"
                                            title="preview"><i class="fas fa-external-link-alt"></i></a>

                                        <a href="{{ route('scans.download', $appointment->id) }}"
                                            class="btn btn-app btn-modify"
                                            style="height: 41px;min-width: 46px;margin: 0px;padding: 0px;">
                                            <i class="fas fa-download"></i>
                                        </a> --}}
                                            </tr>
                                            @php
                                                $counter++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <button class="button add-btn" data-toggle="modal" data-target="#modal_add_appointment">
                                    <svg viewBox="0 -0.5 9 9" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" width="18"
                                        height="18">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <desc>Created with Sketch.</desc>
                                            <defs> </defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <g id="Dribbble-Light-Preview"
                                                    transform="translate(-345.000000, -206.000000)" fill="#ffffff">
                                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                                        <polygon id="plus_mini-[#ffffff]"
                                                            points="298 49 298 51 294.625 51 294.625 54 292.375 54 292.375 51 289 51 289 49 292.375 49 292.375 46 294.625 46 294.625 49">
                                                        </polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. Appointment  box -->

            <!-- Valoracíon Psicologica box -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Valoracíon Psicologica</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div id="infoMedical" class="card-body" style="display: block;">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="orientationLtrs_table"
                                    class="table table-bordered table-striped dataTable dtr-inline" role="grid"
                                    aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending">
                                                Fecha
                                            </th>
                                            <th rowspan="1" colspan="1">
                                                Descripción
                                            </th>
                                            <th rowspan="1" colspan="1">
                                                Acciones
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp

                                        @foreach ($orientationLtrs as $ltr)
                                            <tr role="row" class="{{ $counter % 2 == 0 ? 'even' : 'odd' }}">
                                                <td class="dtr-control sorting_1" tabindex="0">
                                                    {{ $ltr['updated_at'] }}</td>
                                                <td class="truncate">{{ $ltr['content'] }}</td>
                                                <td>
                                                    <a href="{{ route('orientationLtr.show', [$ltr->id]) }}"
                                                        target="_blank" class="btn btn-success"><i
                                                            class="fa fa-print"></i>
                                                        Print
                                                    </a>
                                                </td>
                                            </tr>
                                            @php
                                                $counter++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <button class="button add-btn" data-toggle="modal"
                                    data-target="#modal-add-orientationLtr">
                                    <svg viewBox="0 -0.5 9 9" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" width="18"
                                        height="18">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <title>plus_mini [#ffffff]</title>
                                            <desc>Created with Sketch.</desc>
                                            <defs> </defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <g id="Dribbble-Light-Preview"
                                                    transform="translate(-345.000000, -206.000000)" fill="#ffffff">
                                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                                        <polygon id="plus_mini-[#ffffff]"
                                                            points="298 49 298 51 294.625 51 294.625 54 292.375 54 292.375 51 289 51 289 49 292.375 49 292.375 46 294.625 46 294.625 49">
                                                        </polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            <!-- /.Valoracíon Psicologica box -->
        </div>
        <div class="col-md-6">
            <!-- info  medical  box -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Información Psicologica del Paciente</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div id="infoMedical" class="card-body" style="display: block;">
                    <div class="card-body">
                        <form class="needs-validation" method="post"
                            action="{{ route('patients.update', [$patient->id]) }}" novalidate>
                            @csrf
                            @method('PUT')
                            <input id="full_name" name="full_name" type="text" class=" form-field__input"
                                placeholder=" " value="{{ old('full_name', $patient->full_name) }}" hidden />
                            <input id="email" name="email" type="text" class=" form-field__input"
                                value="{{ old('email', $patient->email) }}"hidden />
                            <input id="phone" name="phone" type="text" class=" form-field__input"
                                value="{{ old('phone', $patient->phone) }}" hidden />
                            <input id="identification" name="identification" type="text" class=" form-field__input"
                                value="{{ old('identification', $patient->identification) }}" hidden />
                            <input id="dob" name="dob" type="text" class=" form-field__input"
                                value="{{ old('dob', $patient->dob) }}" hidden />
                            <input id="age" name="age" type="text" class=" form-field__input"
                                value="{{ old('age', $patient->age) }}" hidden />
                            <input id="address" name="address" type="text" class=" form-field__input"
                                value="{{ old('address', $patient->address) }}" hidden />
                            <input id="city" name="city" type="text" class=" form-field__input"
                                value="{{ old('city', $patient->city) }}" hidden />
                            <input id="neighborhood" name="neighborhood" type="text" class=" form-field__input"
                                value="{{ old('neighborhood', $patient->neighborhood) }}" hidden />
                            <input id="cuatrimestre" name="cuatrimestre" type="text" class=" form-field__input"
                                value="{{ old('cuatrimestre', $patient->cuatrimestre) }}" hidden />
                            <input id="program_id" name="program_id" type="text" class=" form-field__input"
                                value="{{ old('program_id', $patient->program_id) }}" hidden />

                            <div class="form-group">
                                <label for="infochat">Conversación con AVi ChatBot</label>
                                <div id="infochat" contenteditable="false">
                                    {!! $messagesHtml !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="antecedents">Antecedentes</label>
                                <textarea name="antecedents" id="antecedents" cols="30" rows="5" class="form-control">{{ old('antecedents', $patient->antecedents) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="comments">Comentarios</label>
                                <textarea name="comments" id="comments" cols="30" rows="5" class="form-control">{{ old('comments', $patient->comments) }}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Actualizar</button>
                            </div>
                        </form>
                        <hr>

                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /. info  medical -->

            <!--  Medical scans  box -->
            <div class="card card-secondary ">
                <div class="card-header">
                    <h3 class="card-title">Scans</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="scans_info" class="table table-bordered table-striped dataTable dtr-inline"
                                    role="grid" aria-describedby="scans_info_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="scans_info"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending">
                                                Fecha</th>
                                            <th rowspan="1" colspan="1">Nombre Anexo</th>
                                            <th rowspan="1" colspan="1">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($scans as $scan)
                                            <tr role="row" class="{{ $counter % 2 == 0 ? 'even' : 'odd' }}">
                                                <td class="dtr-control sorting_1" tabindex="0">
                                                    {{ $scan['updated_at'] }}</td>
                                                <td>{{ $scan['type'] }}</td>
                                                <td
                                                    style="padding-right: -3.25rem;border-right-width: 0px;height: 37px;width: 95.833px;">

                                                    @php
                                                        $arr = explode('/', $scan->scan_path);
                                                        $name = end($arr);
                                                        $path = '/images/' . $name;
                                                    @endphp
                                                    <a href={{ $path }}
                                                        onclick="window.open(this.href, '_blank', 'left=50%,top=50%,width=500,height=500,toolbar=1,resizable=1'); return false;"
                                                        class="btn btn-primary"><i class="fas fa-external-link-alt"></i>
                                                        {{-- <img src="{{ url($path) }}" alt="Image" /> --}}
                                                    </a>
                                                    <a href="{{ route('scans.download', $scan->id) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @php
                                                $counter++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <button class="button add-btn" data-toggle="modal" data-target="#modal-scan">
                                    <svg viewBox="0 -0.5 9 9" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" width="18"
                                        height="18">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <desc>Created with Sketch.</desc>
                                            <defs> </defs>
                                            <g id="Page-1" stroke="none" stroke-width="1" fill="none"
                                                fill-rule="evenodd">
                                                <g id="Dribbble-Light-Preview"
                                                    transform="translate(-345.000000, -206.000000)" fill="#ffffff">
                                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                                        <polygon id="plus_mini-[#ffffff]"
                                                            points="298 49 298 51 294.625 51 294.625 54 292.375 54 292.375 51 289 51 289 49 292.375 49 292.375 46 294.625 46 294.625 49">
                                                        </polygon>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </button>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
            <!-- /.Medical scans  box -->
        </div>

    </div>

    @include('modals._add_scan', ['patient' => $patient])
    @include('modals._add_orientationLtr', ['patient' => $patient])
    @include('modals._add_appointment')
    @include('modals._add_prescription', ['patient' => $patient])
@endsection
