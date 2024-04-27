    <!--popOut add user  model -->
    <div class="modal fade" id="modal-show-remisions">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Mis Remisiones</h4>
                </div>
                <div class="modal-body">
                    <!-- Main content -->
                    <!-- partial:index.partial.html -->
                    <div class="container">
                        <input type="number" name="patient_id" id="patient_id" value="{{ $remisions }}" hidden>

                        <div class="row">
                            <div class="col-sm">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Remite</th>
                                            <th scope="col">Descargar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($remisions as $remision)
                                            <tr>
                                                <th scope="row">{{ \Carbon\Carbon::parse($remision['created_at'])->format('d/m/Y') }}</th>
                                                <td>{{ $remision->content }}</td>
                                                <td>Otto</td>
                                                <td>
                                                    <a href="{{ route('remisions.show', [$remision->id]) }}"
                                                        target="_blank"
                                                        class="btn btn-warning" title="Descargar">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
