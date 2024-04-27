@extends('layouts.backend')
@section('title', 'Actualizar Paciente :' . $patient->full_name . ' ID : ' . $patient->identification)
@section('header', 'Actualizar Paciente :' . $patient->full_name . ' - ID : ' . $patient->identification)

@section('content')
    <div class="card">
        <div class="card-body">
            <form class="needs-validation" method="post" action="{{ route('patients.update', [$patient]) }}" novalidate>
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="identification">Identificación</label>
                    <input id="identification" name="identification" type="text"
                        class="@error('identification') error-border @enderror form-control "
                        value="{{ old('identification', $patient->identification) }}" placeholder=" " required />
                    <!-- TODO check if i m working-->
                    @error('identification')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="full_name">Nombres Y Apellidos</label>
                    <input id="full_name" name="full_name" type="text"
                        class="@error('full_name') error-border @enderror form-control "
                        value="{{ old('full_name', $patient->full_name) }}" placeholder=" " required />
                    <!-- TODO check if i m working-->
                    @error('full_name')
                        <div class="error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="age">Edad</label>
                    <input id="age" name="age" type="number"
                        class="@error('age') error-border @enderror form-control " value="{{ $patient->age }}"
                        placeholder=" " disabled />
                    @error('age')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Genero</label>
                    <select class="form-control">
                        <option value="F">Femenino</option>
                        <option value="M">Masculino</option>
                        <option value="ND">No definido</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="text"
                        class=" @error('email') error-border @enderror form-control"
                        value="{{ old('email', $patient->email) }}" placeholder=" " required />
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <div class="input-group">
                        <input type="text" id="phone" name="phone"
                            class=" @error('phone') error-border @enderror form-control"
                            data-inputmask="'mask': ['999-999-9999']" data-mask required
                            value="{{ old('phone', $patient->phone) }}">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fas fa-phone"></i></div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label for="dob">Fecha de Nacimiento</label>
                    <input type="date"id="dob" name="dob" class="form-control " data-target="#dob" required
                        value="{{ old('dob', $patient->dob) }}" />
                    @error('dob')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input id="address" name="address" type="text"
                        class=" @error('address') error-border @enderror form-control"
                        value="{{ old('address', $patient->address) }}" placeholder=" " required />
                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="neighborhood">Barrio</label>
                    <input id="neighborhood" name="neighborhood" type="text"
                        class=" @error('neighborhood') error-border @enderror form-control"
                        value="{{ old('neighborhood', $patient->neighborhood) }}" placeholder=" " required />
                    @error('neighborhood')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="city">Ciudad</label>
                    <input id="city" name="city" type="text"
                        class=" @error('city') error-border @enderror form-control"
                        value="{{ old('city', $patient->city) }}" placeholder=" " required />
                    @error('city')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Programa</label>
                    <div class="model-field__control">
                        <select name='program_id' class="form-control select2-program-ajax">
                            @foreach ($programs as $program)
                                <option value="{{ $program->id_program }}" selected>{{ $program->program }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cuatrimestre">Cuatrimestre</label>
                    <input id="cuatrimestre" name="cuatrimestre" type="number"
                        class=" @error('cuatrimestre') error-border @enderror form-control"
                        value="{{ old('cuatrimestre', $patient->cuatrimestre) }}" placeholder=" " required />
                    @error('cuatrimestre')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection
