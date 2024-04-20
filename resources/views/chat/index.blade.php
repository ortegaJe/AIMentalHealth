<!DOCTYPE html>
<html lang="en">
<head>
  {{-- <title>Chat GPT Laravel | Code with Ross</title> --}}
 <title>AI Chat | Mental Health</title>
  <link rel="icon" href="https://assets.edlin.app/favicon/favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- JavaScript -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- End JavaScript -->

  <!-- CSS -->
  <link rel="stylesheet" href="/css/style.chat.css">
  <!-- End CSS -->

</head>

<body>
<div class="chat">

  <!-- Header -->
  <div class="top">
    <img src="{{ asset('media/icons/chatbot.png') }}" width="128" height="128" alt="ai_chatbot">
    <div>
      <p>AVi ChatBot</p>
      <small>Online</small>
    </div>
  </div>
  <!-- End Header -->

  <!-- Chat -->
  <div class="messages">
    <div class="left message">
      <img src="{{ asset('media/icons/chatbot.png') }}" alt="Avatar">
      <p>¡Hola {{ $patient->full_name }}! ¿Cómo estás hoy? ¿En qué puedo ayudarte?</p>
      <input type="number" id="patient_id" name="patient_id" value="{{ $patient->id }}" hidden>
    </div>
  </div>
  <!-- End Chat -->

  <!-- Footer -->
  <div class="bottom">
    <form>
      <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
      <button>
        <div class="svg-wrapper-1">
          <div class="svg-wrapper">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              width="24"
              height="24"
            >
              <path fill="none" d="M0 0h24v24H0z"></path>
              <path
                fill="currentColor"
                d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"
              ></path>
            </svg>
          </div>
        </div>
        <span>Send</span>
      </button>
    </form>
  </div>
  <!-- End Footer -->

</div>
</body>

<script>
  // Broadcast messages
  document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault();

    // Stop empty messages
    if (document.querySelector("form #message").value.trim() === '') {
      return;
    }

    // Disable form
    document.querySelector("form #message").disabled = true;
    document.querySelector("form button").disabled = true;

    // Capturar fecha de la respuesta
    const d = new Date();
    let date = d.getDate() +'-'+ (d.getMonth() + 1) + '-' + d.getFullYear() + ' ' + d.toLocaleTimeString();

    fetch('/chat-services', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': "{{csrf_token()}}"
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
      const sendingMessage = '<div class="right message">' +
        '<p>' + document.querySelector("form #message").value + '</p>' +
        '<img src="{{ asset('media/icons/chatuser.png') }}" alt="ai-chat">' +
        '</div>';

      // Populate receiving message
      const receivingMessage = '<div class="left message">' +
        '<img src="{{ asset('media/icons/chatbot.png') }}" alt="ai-chat">' +
        '<p>' + data + '</p>' +
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
