    <!--popOut add user  model -->
    <div class="modal fade" id="modal_add_patient">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Paciente</h4>
                </div>
                <div class="modal-body">
                    <!-- Main content -->
                    <!-- partial:index.partial.html -->
                    <div class="container">
                        <form class="needs-validation" method="post" action="{{ route('patients.store') }}" novalidate>
                            @csrf
                            @if (\App\Enums\UserRoles::isDoctor(Auth::user()->role) || \App\Enums\UserRoles::isAdmin(Auth::user()->role))
                                <input type="number" name="doctor_id" value="{{ Auth::user()->id }}" hidden>
                            @endif
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
                                        value="{{ old('age') }}" placeholder=" " data-inputmask="'mask': ['99']"
                                        data-mask required>
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
                                        <div class="input-group ">
                                            <input type="text" id="phone" name="phone"
                                                class=" @error('phone') error-border @enderror form-control"
                                                data-inputmask="'mask': ['999-999-9999']" data-mask required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i class="fas fa-phone"></i></div>
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
                                                data-target="#dob" placeholder=" Date of birth" required />
                                            <div class="input-group-append" data-target="#dob"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label>Programa</label>
                                        <div class="model-field__control">
                                            <select name='program_id'
                                                class=" form-control select2-program-ajax"></select>
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


                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>
