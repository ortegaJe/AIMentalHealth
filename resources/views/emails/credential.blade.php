<!DOCTYPE html>
<html>

<head>
    <title>Registro Exitoso - AVi Mental Health Portal</title>

    <style>
        p {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>
</head>

<body>
    <h1>¡Hola {{ $mailData['full_name'] }}!</h1>
    <p>
        En nombre de todo el equipo de AVi Mental Health, te damos una cálida bienvenida a nuestra plataforma
        diseñada para brindarte el apoyo mental que necesitas.<br>
        <br>
        Estamos encantados de que te unas a nuestra comunidad dedicada al bienestar emocional y mental. Queremos que
        sepas que estamos aquí para apoyarte en tu viaje hacia una mejor salud mental y emocional.<br>
        <br>
        Para comenzar, aquí están tus credenciales de acceso:<br>
        <br>
        <b>Usuario: {{ $mailData['email'] }}</b><br>
        <b>Contraseña: {{ $mailData['password'] }}</b><br>
        <br>
        Te invitamos a explorar todas las herramientas disponibles en nuestra plataforma:<br>
        <br>
        <b>(Abrazando Vidas) AVi ChatBot:</b> Nuestro chatbot personalizado, AVi, está aquí para ti. Puedes interactuar con AVi para compartir
        cómo te sientes emocionalmente, agendar citas con profesionales en salud mental<br>
        <br>
        <b>AVi Mental Health Portal:</b> Accede a nuestra plataforma web para ver tus citas asignadas y recibir consejos de profesionales en salud mental.
        Estamos comprometidos a proporcionarte un entorno seguro y de apoyo donde puedas encontrar el equilibrio y la serenidad que necesitas.<br>
        <br>
        No dudes en ponerte en contacto con nosotros si tienes alguna pregunta o necesitas ayuda. Estamos aquí para ti en cada paso del camino.<br>
        <br>
        ¡Bienvenido a AVi Mental Health Portal! Estamos emocionados de acompañarte en tu viaje hacia una vida más
        saludable y feliz.<br>
        <br>
        Con cálidos saludos,
    </p>
    <p>Equipo de AVi Mental Health.</p>
</body>

</html>
