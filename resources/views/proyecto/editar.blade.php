<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de proyectos</title>
</head>
<body>
    <h2>actualiza los datos de la empresa</h2>

    <form action="{{route("proyectos.update",$proyecto->id)}}" method="POST" enctype="application/x-www-form-urlencoded" >
        @csrf
        @method('PUT')
        <label for='nombre'>Nombre</label>
        <input type='text' name='nombre' id='nombre' value="{{$proyecto->nombre}}"><br>

        <label for='objetivo_general'>Objetivo General: </label> <br>
        <input type='text' name='objetivo_general' id='objetivo_general'value="{{$proyecto->objetivo_general}}"><br>

        <label for='lugar'>Lugar</label>
        <input type='text' name='lugar' id='lugar'value="{{$proyecto->lugar}}"><br>

        <label for='informacion'>Informacion</label>
        <input type='text' name='informacion' id='informacion'value="{{$proyecto->informacion}}"><br>

        <label for='justificacion'>Justificacion</label>
        <input type='tel' name='justificacion' id='justificacion'value="{{$proyecto->justificacion}}"><br>

        <input type='submit'>
    </form>

</body>
</html>