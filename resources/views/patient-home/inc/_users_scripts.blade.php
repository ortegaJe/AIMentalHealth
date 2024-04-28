<!-- Admin lte Timepicker depondaces-->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>

<!-- SweetAlert2 -->
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    $("#users_table").ready(function() {
        $("#users_table").DataTable({
            responsive: true
        })
    });
    $("#patients_table").ready(function() {
        $("#patients_table").DataTable({
            responsive: true
        })
    });
    $("#orientationLtrs_table").ready(function() {
        $("#orientationLtrs_table").DataTable({
            responsive: true
        })
    });
    $("#scans_info").ready(function() {
        $("#scans_info").DataTable({
            responsive: true
        })
    });
    $("#prescriptions_table").ready(function() {
        $("#prescriptions_table").DataTable({
            responsive: true
        })
    });
    $("#appointment_table").ready(function() {
        $("#appointment_table").DataTable({
            responsive: true
        })
    });

    //Date time picker
    $('#dob').datetimepicker({
        format: 'L'
    });

    //Date time picker
    $('#appointmentdate').datetimepicker({
        format: 'L'
    });

    //start_time picker
    $('#start_time').datetimepicker({
        format: 'LT'
    })

    //end_time picker
    $('#end_time').datetimepicker({
        format: 'LT'
    })

    // inputmsk Code start (__-__)
    $('[data-mask]').inputmask()



    $(document).ready(function() {

        const token = "{{ csrf_token() }}"
        // Populate Select2 with AJAX data

        // 1. get the patients whose name and last names match the query provided
        $('.select2-patient-ajax').select2({

            ajax: {
                url: "{{ route('patients.findByQuery') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: token,
                        queryTerm: params.term,
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                cache: true
            }
        });

        // 2. get the doctors whose name and last names match the query provided
        $('.select2-doctor-ajax').select2({

            ajax: {
                url: "{{ route('users.findByQuery') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: token,
                        queryTerm: params.term,
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                // cache: true
            }
        });

        // 3. get the programa whose name match the query provided
        $('.select2-program-ajax').select2({

            ajax: {
                url: "{{ route('programs.findByQuery') }}",
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: token,
                        queryTerm: params.term,
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    }
                },
                // cache: true
            }
        });

    });
</script>
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    // patientAddToTable();
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<script>
    // Broadcast messages
    document.querySelector("form").addEventListener("submit", function(event) {
        event.preventDefault();

        console.log('id del paciente',document.querySelector("#patient_id").value);

        // Stop empty messages
        if (document.querySelector("form #message").value.trim() === '') {
            return;
        }

        // Disable form
        document.querySelector("form #message").disabled = true;
        document.querySelector("form button").disabled = true;

        // Capturar fecha de la respuesta
        const d = new Date();
        let date = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear() + ' ' +
            d.toLocaleTimeString();

        fetch('/patient/home/chatServices', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    content: document.querySelector("form #message").value,
                    patient: document.querySelector("#patient_id").value,
                    date: date,
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // Populate sending message
                const sendingMessage = '<div class="d-flex flex-row justify-content-end mb-4">' +
                    '<div class="p-3 ms-3" style="border-radius: 15px; background-color: #fbfbfb;">' +
                    '<p class="small mb-0">' + document.querySelector("form #message").value + '</p>' +
                    '</div>' +
                    '<img src="{{ asset('media/icons/chatuser.svg') }}" alt="user" style="width: 45px; height: 100%;">' +
                    '</div>';

                // Populate receiving message
                const receivingMessage = '<div class="d-flex flex-row justify-content-start mb-4">' +
                    '<img src="{{ asset('media/icons/avichatbot.png') }}" alt="avatar 1" style="width: 45px; height: 100%;">' +
                    '<div class="p-3 ms-3" style="border-radius: 15px; background-color: rgb(200, 255, 244);">' +
                    '<p class="small mb-0" style="color: rgb(38, 109, 107);"><strong>' + data +
                    '</strong></p>' +
                    '</div>' +
                    '</div>';

                // Append messages
                document.querySelector(".messages").insertAdjacentHTML('beforeend', sendingMessage);
                document.querySelector(".messages").insertAdjacentHTML('beforeend', receivingMessage);

                // Cleanup
                document.querySelector("form #message").value = '';
                window.scrollTo(0, document.body.scrollHeight);

                // Enable form
                document.querySelector("form #message").disabled = false;
                document.querySelector("form button").disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                // Handle error
            });
    });
</script>

<!-- only include _errors subview if there is errors-->
@includeWhen($errors->any(), 'patient-home.inc._errors')

{{-- sucess msg --}}
<!--TODO: check if I m working (sucess msg is displayed after successful add of a user)-->
@includeWhen(session('success'), 'patient-home.inc._success')
