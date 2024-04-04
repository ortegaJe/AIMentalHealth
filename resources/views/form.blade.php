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
                    <div class="modal fade" id="myModal0">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title">Registra tus datos</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="needs-validation" method="POST"
                                        action="{{ route('form.savePatientForm') }}" novalidate>
                                        @csrf
                                        @method('POST')
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="identification">Número de Identificación</label>
                                                <input type="number" id="identification" name="identification"
                                                    class="@error('identification') error-border @enderror form-control "
                                                    value="{{ old('identification') }}" placeholder=" " required>
                                                <!-- TODO check if i m working-->
                                                @error('identification')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="full_name">Nombres y Apellidos</label>
                                                <input type="text" id="full_name" name="full_name"
                                                    class="@error('full_name') error-border @enderror form-control "
                                                    value="{{ old('full_name') }}" placeholder=" " required>
                                                <!-- TODO check if i m working-->
                                                @error('full_name')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="age">Edad</label>
                                                <input type="text" id="age" name="age"
                                                    class="@error('age') error-border @enderror form-control "
                                                    value="{{ old('age') }}" placeholder=" "
                                                    data-inputmask="'mask': ['99']" data-mask required>
                                                <!-- TODO check if i m working-->
                                                @error('age')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Genero</label>
                                                    <select class="form-control">
                                                        <option selected>Seleccione...</option>
                                                        <option value="F">Femenino</option>
                                                        <option value="M">Masculino</option>
                                                        <option value="ND">No definido</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="address">Dirección</label>
                                                <input type="text" id="address" name="address"
                                                    class="@error('address') error-border @enderror form-control "
                                                    value="{{ old('address') }}" placeholder=" " required>
                                                <!-- TODO check if i m working-->
                                                @error('address')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="neighborhood">Barrio</label>
                                                <input type="text" id="neighborhood" name="neighborhood"
                                                    class="@error('neighborhood') error-border @enderror form-control "
                                                    value="{{ old('neighborhood') }}" placeholder=" " required>
                                                <!-- TODO check if i m working-->
                                                @error('neighborhood')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="city">Ciudad</label>
                                                <input type="text" id="city" name="city"
                                                    class="@error('city') error-border @enderror form-control "
                                                    value="{{ old('city') }}" placeholder=" " required>
                                                <!-- TODO check if i m working-->
                                                @error('city')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="email">Email</label>
                                                <input type="text" id="email" name="email"
                                                    class="@error('email') error-border @enderror form-control "
                                                    value="{{ old('email') }}" placeholder=" " required>
                                                <!-- TODO check if i m working-->
                                                @error('email')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="email">Telefono</label>
                                                <div class="model-field">
                                                    <div class="input-group">
                                                        <input type="text" id="phone" name="phone"
                                                            class=" @error('phone') error-border @enderror form-control"
                                                            data-inputmask="'mask': ['999-999-9999']" data-mask
                                                            required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-phone"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <label for="email">Fecha de Nacimiento</label>
                                                <div class="model-field">
                                                    <div class="input-group date" id="dob" name="dob"
                                                        data-target-input="nearest">
                                                        <input type="date" name="dob" class="form-control "
                                                            data-target="#dob" placeholder=" Date of birth"
                                                            required />
                                                        <div class="input-group-append" data-target="#dob"
                                                            data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i
                                                                    class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label>Programa Academico</label>
                                                    <div class="model-field__control">
                                                        <select name='program_id'
                                                            class="select2-form-program-ajax"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-6">
                                                <label for="cuatrimestre">Cuatrimestre</label>
                                                <input type="text" id="cuatrimestre" name="cuatrimestre"
                                                    class="@error('cuatrimestre') error-border @enderror form-control "
                                                    value="{{ old('cuatrimestre') }}" placeholder=" "
                                                    data-inputmask="'mask': ['9']" data-mask required>
                                                <!-- TODO check if i m working-->
                                                @error('cuatrimestre')
                                                    <div class="error">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
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
            $("#myModal0").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>

</body>

</html>
