@extends('layouts.backend', ['includeNavbar' => false])
@section('title', 'Register')

@section('content')
    <div class="register-box mx-auto align-self-center">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href=" {{ route('patient.login') }}" class="h1"><b>AVi</b>{{ env('APP_NAME') }}</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>
                <form class="needs-validation" action="{{ route('patient.register.store') }}" method="POST" novalidate>
                    @csrf
                    @method('POST')
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" type="number" id="identification" name="identification"
                            placeholder="Número de Identificación"
                            class="@error('identification') error-border @enderror form-control"
                            value="{{ old('identification') }}" placeholder=" " required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            placeholder="Nombres y Apellidos"
                            class="@error('full_name') error-border @enderror form-control" value="{{ old('full_name') }}"
                            placeholder=" " required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-control">
                            <option selected>--Seleccione Género--</option>
                            <option value="F">Femenino</option>
                            <option value="M">Masculino</option>
                            <option value="ND">No definido</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email"
                            class="@error('email') error-border @enderror form-control" value="{{ old('email') }}"
                            placeholder=" " required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Telefono"
                            class=" @error('phone') error-border @enderror form-control"
                            data-inputmask="'mask': ['999-999-9999']" data-mask required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <label for="date">Fecha de Nacimiento</label>
                    <div class="input-group date mb-3" id="dob" name="dob" data-target-input="nearest">
                        <input type="date" name="dob" class=" @error('dob') error-border @enderror form-control"
                            data-target="#dob" placeholder="Fecha de Nacimiento" required>
                        <div class="input-group-append" data-target="#dob" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>

                    </div>
                </form>
                <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
            </div>

        </div>
    </div>
@endsection
