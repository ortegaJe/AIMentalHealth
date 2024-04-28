@extends('patient-home.layouts.backend')
@section('title', 'Patient Home')
@section('header', 'Mis Citas')

@section('content')
    <style>
        .chat {
            min-height: auto;
            max-height: 80%;
            width: 500px;
            position: fixed;
            bottom: 80px;
            right: 20px;
            margin: 0;
            border-radius: 10px;
            overflow: auto;
        }

        .chat {
            border: none;
        }

        .modal-backdrop.fade.show {
            display: none;
        }

        .chatBtn {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            background-color: #FFE53B;
            background-image: linear-gradient(45deg, rgb(46, 206, 182), rgb(200, 255, 244));
            cursor: pointer;
            padding-top: 3px;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.164);
            position: relative;
            background-size: 300%;
            background-position: left;
            transition-duration: 1s;
        }

        .tooltip {
            position: absolute;
            top: -40px;
            opacity: 0;
            background-color: rgb(46, 206, 182);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition-duration: .5s;
            pointer-events: none;
            letter-spacing: 0.5px;
        }

        .chatBtn:hover .tooltip {
            opacity: 1;
            transition-duration: .5s;
        }

        .chatBtn:hover {
            background-position: right;
            transition-duration: 1s;
        }
    </style>
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
                                        Profesional de Bienestar
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
                                            <button type="button" data-toggle="modal" data-target="#modal-show-remisions"
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
    </div>
    <div class="add-btn-container">
        <button class="chatBtn" data-toggle="modal" data-target="#modalMiniChat">
            <svg height="1.6em" fill="white" xml:space="preserve" viewBox="0 0 1000 1000" y="0px" x="0px" version="1.1">
                <path
                    d="M881.1,720.5H434.7L173.3,941V720.5h-54.4C58.8,720.5,10,671.1,10,610.2v-441C10,108.4,58.8,59,118.9,59h762.2C941.2,59,990,108.4,990,169.3v441C990,671.1,941.2,720.5,881.1,720.5L881.1,720.5z M935.6,169.3c0-30.4-24.4-55.2-54.5-55.2H118.9c-30.1,0-54.5,24.7-54.5,55.2v441c0,30.4,24.4,55.1,54.5,55.1h54.4h54.4v110.3l163.3-110.2H500h381.1c30.1,0,54.5-24.7,54.5-55.1V169.3L935.6,169.3z M717.8,444.8c-30.1,0-54.4-24.7-54.4-55.1c0-30.4,24.3-55.2,54.4-55.2c30.1,0,54.5,24.7,54.5,55.2C772.2,420.2,747.8,444.8,717.8,444.8L717.8,444.8z M500,444.8c-30.1,0-54.4-24.7-54.4-55.1c0-30.4,24.3-55.2,54.4-55.2c30.1,0,54.4,24.7,54.4,55.2C554.4,420.2,530.1,444.8,500,444.8L500,444.8z M282.2,444.8c-30.1,0-54.5-24.7-54.5-55.1c0-30.4,24.4-55.2,54.5-55.2c30.1,0,54.4,24.7,54.4,55.2C336.7,420.2,312.3,444.8,282.2,444.8L282.2,444.8z">
                </path>
            </svg>
            <span class="tooltip">AViChat</span>
        </button>
    </div>

    <div class="modal fade show" id="modalMiniChat" aria-modal="true" role="dialog">
        <div class="modal-dialog chat modal-dialog-scrollable">
            <div class="modal-content chat">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMiniChatTitle">AVi ChatBot</h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="messages">
                            <input type="number" id="patient_id" name="patient_id" value="{{ $patient->id }}" hidden>
                            <div class="d-flex flex-row justify-content-start mb-4 left message">
                                <img src="{{ asset('media/icons/avichatbot.png') }}" alt="avatar 1"
                                    style="width: 45px; height: 100%;">
                                <div class="p-3 ms-3 ml-2"
                                    style="border-radius: 15px; background-color: rgb(200, 255, 244);">
                                    <p class="small mb-0" style="color: rgb(38, 109, 107);">
                                        <Strong>¡Hola! {{ $patient->full_name }}</Strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form>
                    <div class="card-footer bg-transparent d-flex justify-content-start align-items-center p-3">
                        <img src="{{ asset('media/icons/chatuser.svg') }}" alt="avatar 3"
                            style="width: 40px; height: 100%;">
                        <input type="text" class="form-control form-control-lg" id="message" name="message"
                            autocomplete="off" placeholder="Type message">
                        <button class="ms-1 border-0 bg-transparent ml-2" style="color: royalblue;" title="Enviar">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
