    <!--popOut add user  model -->
    <div class="modal fade" id="addUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo Usuario</h4>
                </div>
                <div class="modal-body">
                    <!-- Main content -->
                    <!-- partial:index.partial.html -->
                    <div class="container">
                        <form class="needs-validation" method="post" action="{{ route('users.store') }}" novalidate>
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-sm">
                                    <label for="name">Nombres</label>
                                    <input type="text" id="name" name="name"
                                        class="@error('name') error-border @enderror form-control "
                                        value="{{ old('name') }}" placeholder=" " required>
                                    <!-- TODO check if i m working-->
                                    @error('name')
                                        <div class="error">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="lastname">Apellidos</label>
                                    <input type="text" id="lastname" name="lastname"
                                        class="@error('lastname') error-border @enderror form-control "
                                        value="{{ old('lastname') }}" placeholder=" " required>
                                    <!-- TODO check if i m working-->
                                    @error('lastname')
                                        <div class="error">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="username">Usuario</label>
                                    <input type="text" id="username" name="username"
                                        class="@error('username') error-border @enderror form-control "
                                        value="{{ old('username') }}" placeholder=" " required>
                                    <!-- TODO check if i m working-->
                                    @error('username')
                                        <div class="error">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email"
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
                                    <label for="password">Contraseña</label>
                                    <input type="password" id="password" name="password"
                                        class="@error('password') error-border @enderror form-control "
                                        value="{{ old('password') }}" placeholder=" " required>
                                    <!-- TODO check if i m working-->
                                    @error('password')
                                        <div class="error">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="role">Rol</label>
                                        <select class="form-control" id="role" name="role"
                                            class=" @error('role') error-border @enderror form-control"
                                            required>
                                            <option selected>Seleccione...</option>
                                            @foreach (App\Enums\UserRoles::values() as $key => $value)
                                            <option value="{{ $value}}">{{ $key }}</option>
                                            @endforeach
                                            @error('role')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </select>
                                    </div>
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
