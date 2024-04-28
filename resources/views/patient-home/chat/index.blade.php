<!DOCTYPE html>
<html lang="en">

<head>
    {{-- <title>Chat GPT Laravel | Code with Ross</title> --}}
    <title>AVi ChatBot Mental Health</title>
    <link rel="icon" href="{{ asset('media/favicons/mhealth-192x192.png') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{ asset('/css/mdb.min.css') }}" />
</head>

<body style="background-color: #aaaaaa;">
    <!-- Modal -->
    <div class="modal fade show" id="exampleModal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalTitle">AVi ChatBot</h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="messages">
                            <input type="number" id="patient_id" name="patient_id" value="{{ $patient->id }}" hidden>
                            <div class="d-flex flex-row justify-content-start mb-4 left message">
                                <img src="{{ asset('media/icons/avichatbot.png') }}" alt="avatar 1"
                                    style="width: 45px; height: 100%;">
                                <div class="p-3 ms-3"
                                    style="border-radius: 15px; background-color: rgb(200, 255, 244);">
                                    <p class="small mb-0" style="color: rgb(38, 109, 107);"><Strong>¡Hola!
                                            {{ $patient->full_name }}</Strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form>
                    <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                        <img src="{{ asset('media/icons/chatuser.svg') }}" alt="avatar 3"
                            style="width: 40px; height: 100%;">
                        <input type="text" class="form-control form-control-lg" id="message" name="message"
                            autocomplete="off" placeholder="Type message">
                        <a class="ms-2 text-muted" href="{{ route('patient.home') }}" data-mdb-ripple-init
                            data-mdb-tooltip-init data-mdb-placement="top" title="Ir a mi portal">
                            <i class="fas fa-home"></i>
                        </a>
                        <button class="ms-1 border-0 bg-transparent" style="color: royalblue;" data-mdb-ripple-init
                            data-mdb-tooltip-init data-mdb-placement="top" title="Enviar">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript" src="{{ asset('/js/mdb.umd.min.js') }}"></script>

<script>
    const instance = new mdb.Modal(
        document.getElementById('exampleModal'), {
            backdrop: false,
            keyboard: false,
        }
    );
    instance.show()
</script>

<script>
    // Broadcast messages
    document.querySelector("form").addEventListener("submit", function(event) {
        event.preventDefault();

        // Stop empty messages
        if (document.querySelector("form #message").value.trim() === '') {
            return;
        }

        // Disable form
        document.querySelector("form #message").disabled = true;
        document.querySelector("form button").disabled = true;

        // Obtener el valor del mensaje del formulario
        const message = document.querySelector("form #message").value;

        // Array de palabras de finalización de chat
        const palabrasFinChat = ["adiós", "chao", "hasta luego", "terminar", "fin"];

        // Verificar si el mensaje contiene alguna palabra de finalización
        const esFinalChat = palabrasFinChat.some(palabra => message.toLowerCase().includes(palabra));

        // Mostrar el resultado
        if (esFinalChat) {
            console.log("El mensaje indica que el chat ha finalizado.");
            setTimeout(function() {
                window.location.href = "/patient/home";
            }, 6000); // 3000 milisegundos = 3 segundos
        } else {
            console.log("El chat continúa...");
        }

        // Capturar fecha de la respuesta
        const d = new Date();
        let date = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear() + ' ' +
            d.toLocaleTimeString();

        fetch('/patient/chatServices', {
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

{{-- <script>
  //Broadcast messages
  $("form").submit(function (event) {
    event.preventDefault();

    //Stop empty messages
    if ($("form #message").val().trim() === '') {
      return;
    }

    //Disable form
    $("form #message").prop('disabled', true);
    $("form button").prop('disabled', true);

    $.ajax({
      url: '/chat-services',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': "{{csrf_token()}}"
      },
      data: {
        "content": $("form #message").val(),
        "patient": $("#patient_id").val()
      }
    }).done(function (res) {

      //Populate sending message
      $(".messages > .message").last().after('<div class="right message">' +
        '<p>' + $("form #message").val() + '</p>' +
        '<img src="{{ asset('media/icons/chatuser.png') }}" alt="ai-chat">' +
        '</div>');

      //Populate receiving message
      $(".messages > .message").last().after('<div class="left message">' +
        '<img src="{{ asset('media/icons/chatbot.png') }}" alt="ai-chat">' +
        '<p>' + res + '</p>' +
        '</div>');

      //Cleanup
      $("form #message").val('');
      $(document).scrollTop($(document).height());

      //Enable form
      $("form #message").prop('disabled', false);
      $("form button").prop('disabled', false);
    });
  });

</script> --}}

</html>
