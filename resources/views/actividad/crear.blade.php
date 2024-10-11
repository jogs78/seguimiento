<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades del proyecto</title>
</head>
<body>
    <p>Haz una descripcion detallada de las actividades especificas que tienes planeadas</p><br>
    <form action="{{route("actividades.store")}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        <label for='nombre'>Nombre de la actividad</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombre' value="{{old('nombre')}}"><br>
        
        <label for='descripcion'>Describe como realizaras tal actividad </label>
        {{$errors->first("descripcion")}}
        <textarea name='descripcion' id='descripcion' value="{{old('dexcripcion')}}"></textarea><br>

        <label for='semanas'>En cuantas semanas reliazaras tal actividad</label>
        {{$errors->first("semanas")}}
        <input type='number' name='semanas' id='semanas' value="{{old('semanas')}}"><br>

        <label for='orden'>Cual es el orden de esta actividad</label><br>
        <p>Ejemplo:</p>
        <li>1.Crear un cuestionario</li> 
        <li>2.Entrevistar a alguien con el cuestionario</li> <br>
        {{$errors->first("orden")}}
        <input type='number' name='orden' id='orden' value="{{old('orden')}}"><br>

        
        <input type='submit'> <!-- Crear un boton o enlace para tener mas actividades -->
    </form>


</body>
</html>