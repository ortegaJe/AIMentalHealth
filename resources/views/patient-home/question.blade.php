<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @yield('title', 'AI Mental Health')
    </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <!-- For Time Picker - Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- Custom style -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>

<body>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-3 mx-auto">
                    <div class="modal fade" id="modalQuestions">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="modal-title">Estamos aquí para escucharte: responde estas preguntas</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" method="POST"
                                        action="{{ route('questions.store', $patient->id) }}" novalidate>
                                        @csrf
                                        @method('POST')
                                        <input type="number" id="patient_id" name="patient_id"
                                            value="{{ $patient->id }}" hidden>
                                        @foreach ($questions as $question)
                                            <div class="form-group">
                                                <label for="{{ $question->id }}">{{ $question->name }}</label><br>
                                                <input type="radio" id="{{ $question->id }}_si"
                                                    name="question_{{ $question->id }}" value="1">
                                                <label for="{{ $question->id }}_si">Sí</label>
                                                <input type="radio" id="{{ $question->id }}_no"
                                                    name="question_{{ $question->id }}" value="0">
                                                <label for="{{ $question->id }}_no">No</label>
                                            </div>
                                        @endforeach
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary btn-block">Seguir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ./wrapper-->
    @include('inc._users_scripts')

    <script>
        $(document).ready(function() {
            $("#modalQuestions").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>

</body>

</html>
