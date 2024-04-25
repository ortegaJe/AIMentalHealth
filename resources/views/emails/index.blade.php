<!DOCTYPE html>
<html>

<head>
    <title>Recordatorio de Cita</title>

    <style>
        p {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
</head>

<body>
    <h1>Hola! {{ $mailData['title'] }}</h1>
    {{--     <p>{{ $mailData['body'] }}</p> --}}
    <p>Querido {{ $mailData['title'] }},</p>
    <p>
        Espero que te encuentre bien. Me gustaría recordarte que tenemos una cita programada, <b>{{ $mailData['body'] }}</b>.
        Estoy aquí para ti y estoy emocionado/a de poder brindarte el apoyo que necesitas en este viaje hacia una mejor salud mental.
        <br>
        <br>
        Entiendo que a veces puede ser difícil enfrentarse a los desafíos que la vida nos presenta, pero quiero que
        sepas que no estás solo/a en este proceso. Juntos/as podemos explorar las emociones y los pensamientos que estás
        experimentando, y trabajar en estrategias para ayudarte a encontrar claridad y bienestar.
        <br>
        <br>
        Recuerda que cada paso que des, por pequeño que parezca, es un paso hacia adelante. Tu bienestar es mi prioridad, y estoy comprometido/a a apoyarte en cada paso del camino.
        <br>
        <br>
        Espero verte en nuestra cita y continuar nuestro trabajo juntos/as.
    </p>

    <p>Con cálido apoyo,</p>

    <p>UIB Área de Bienestar</p>
</body>

</html>
