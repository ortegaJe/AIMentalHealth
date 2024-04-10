@extends('layouts.backend')
@section('title', 'Administrar Usuarios')
@section('header', 'Lista de Usuarios')

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="users_table" class="table table-bordered table-striped dataTable dtr-inline" role="grid"
                            aria-describedby="example1_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="users_table" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending">
                                        Nombres
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="users_table"
                                        aria-label="Browser: activate to sort column ascending">
                                        Apellidos
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="users_table"
                                        aria-label="Platform(s): activate to sort column ascending">
                                        Login
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="users_table"
                                        aria-label="CSS grade: activate to sort column ascending">
                                        Email
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="users_table"
                                        aria-label="CSS grade: activate to sort column ascending">
                                        Rol
                                    </th>
                                    <th>
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($users as $user)
                                    <tr role="row" class="{{ $counter % 2 == 0 ? 'even' : 'odd' }}">
                                        <td class="dtr-control sorting_1" tabindex="0">
                                            {{ $user['name'] }}</td>
                                        <td>{{ $user['lastname'] }}</td>
                                        <td>{{ $user['username'] }}</td>
                                        <td>{{ $user['email'] }}</td>
                                        <td class="text-center"><span class="badge badge-{{ $user['role']->name == 'ADMIN' ? 'danger' : 'primary' }} btn-block">{{ $user['role']->name }}</span></td>
                                        <td style="padding-right: -3.25rem;border-right-width: 0px;height: 37px;width: 95.833px;">
                                            <a href="{{ route('users.edit', [$user]) }}"
                                                class="btn btn-success">
                                                <i class="fas fa-user-edit"></i>
                                            </a>

                                            <!-- TODO check authorization -->
                                            <form method="POST" action="{{ route('users.destroy', [$user]) }}"
                                                class="form-modify">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger"
                                                    style="height: 41px;min-width: 46px;margin-left: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
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
            {{-- add user btn  --}}
            <div class="add-btn-container">
                <button class="button add-btn" data-toggle="modal" data-target="#addUser">
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
            {{-- end  add user btn --}}

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- FIXME: this not consistence for put&del we used view and for post used modal-->
    {{-- add user --}}
    @include('modals._add_user')

    {{-- end  modals --}}
@endsection
