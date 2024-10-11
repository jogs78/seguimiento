<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar</title>
</head>
<body>
    <p>El asesor ya subio su calificacion</p>
    <a href="archivo.pdf" download="archivo.pdf">Descarga el PDF</a><p>imprimalo y que lo califique su empresa</p><br>
    <p>por ultimo escanea y subelo</p>
    <!--<form action="{{ route('estudiante.pdf') }}" method="POST" enctype="multipart/form-data">
        @csrf-->
        <label for="archivo">Subir archivo PDF:</label>
        <input type="file" id="archivo" name="archivo" accept="application/pdf" required>

        <button type="submit">Subir PDF</button>
    <!--</form>-->
</body>
</html>