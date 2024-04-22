<!DOCTYPE html>
<html>

<head>
    <title>Alerta de Riesgo - AVi ChatBot</title>

    <style>
        p {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
</head>

<body>
    <h1>Mensaje de Alerta: {{ $mailData['risk'] }} del Estudiante {{ $mailData['title'] }}!</h1>
    <p>
        Estimado equipo de asistenciales de psicología,

        Queremos informarles que, según los resultados recientes de una encuesta, se ha identificado a un estudiante con un riesgo alto.
        <br>
        Los indicadores sugieren la necesidad de una intervención inmediata y un seguimiento cercano por parte de los profesionales de la salud mental.
        <br>
        Por favor, revisen los detalles sobre el estudiante en cuestión y tomen las medidas necesarias para proporcionar el apoyo y la atención adecuados lo antes posible.
        <br>
        Su pronta acción es fundamental para garantizar la seguridad y el bienestar del estudiante.
        <br>
        Gracias por su atención y colaboración.
        <br>
        Atentamente,
    </p>
    <p>AVi ChatBot</p>
</body>

</html>
