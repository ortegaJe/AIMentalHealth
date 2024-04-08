    <!--popOut add user  model -->
    <div class="modal fade" id="modal-scan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cargar Adjunto</h4>
                </div>
                <div class="modal-body">
                    <!-- Main content -->
                    <!-- partial:index.partial.html -->
                    <div class="container">
                        <form class="needs-validation" method="post" action="{{ route('scans.store') }}" novalidate
                            enctype="multipart/form-data">
                            @csrf
                            <input type="number" name="patient_id" id="patient_id" value="{{ $patient->id }}" hidden>
                            <div class="form-group">
                                <div class="col-sm">
                                    <label for="identification">Tipo</label>
                                    <input type="text" id="type" name="type"
                                        class="@error('type') error-border @enderror form-control "
                                        value="{{ old('type') }}" placeholder=" " required>
                                    <!-- TODO check if i m working-->
                                    @error('type')
                                        <div class="error">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm">
                                    <div class="model-field">
                                        <div class="model-field__control">
                                            <input type="file" id="image" name="image">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Cargar</button>
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
