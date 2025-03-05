<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Usted no está autorizado a realizar esta acción</h1>
    <p id="countdown">Será redirigido en 10 segundos...</p>

    <script>
        let seconds = 10;
        const countdownElement = document.getElementById('countdown');

        const countdownInterval = setInterval(function() {
            seconds--;
            countdownElement.textContent = `Será redirigido en ${seconds} segundos...`;

            if (seconds <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);

        setTimeout(function() {
            window.location.href = "{{ route('home') }}";
        }, 10000);
    </script>
</body>
</html>