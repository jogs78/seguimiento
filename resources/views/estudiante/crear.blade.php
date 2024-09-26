<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h2>Registro</h2>

    <form action="{{route("estudiantes.store")}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        <label for='nombre'>Nombre/s</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombre' value="{{old('nombre')}}"><br>
        
        <label for='apellido_paterno'>Apellido Paterno</label>
        {{$errors->first("apellido_paterno")}}
        <input type='text' name='apellido_paterno' id='apellido_paterno' value="{{old('apellido_paterno')}}"><br>

        <label for='apellido_materno'>Apellido Materno</label>
        {{$errors->first("apellido_materno")}}
        <input type='text' name='apellido_materno' id='apellido_materno' value="{{old('apellido_materno')}}"><br>

        <label for='correo_electronico'>Correo Electronico</label>
        @foreach ($errors->get('correo_electronico') as $texto) 
            <li>{{$texto}}</li>
        @endforeach
        <input type='text' name='correo_electronico' id='correo_electronico' value="{{old('correo_electronico')}}"><br>

        <label for='numero_de_control'>Numero de control</label>
        @foreach ($errors->get('numero_de_control') as $texto) 
            <li>{{$texto}}</li>
        @endforeach
        <input type='text' name='numero_de_control' id='numero_de_control' value="{{old('numero_de_control')}}"><br>

        <label for='nombre_del_proyecto'>Nombre del Proyecto</label>
        {{$errors->first("nombre_del_proyecto")}}
        <input type='text' name='nombre_del_proyecto' id='nombre_del_proyecto' value="{{old('nombre_del_proyecto')}}"><br>

        <label for='telefono'>Telefono</label>
        @foreach ($errors->get('telefono') as $texto) 
            <li>{{$texto}}</li>
        @endforeach
        <input type='text' name='telefono' id='telefono' value="{{old('telefono')}}"><br>

        <label for='carrera_id'>Carrera</label>
        <select name="carrera_id" id="carrera_id">
            @foreach ($carreras as $carrera)
                <option value="{{$carrera->id}}">{{$carrera->nombre}}</option>
            @endforeach
        </select>
        <br>

        <label for='direccion'>Direccion</label>
        {{$errors->first("direccion")}}
        <input type='text' name='direccion' id='direccion' value="{{old('direccion')}}"><br>

        <label for='institucion_seguiridad_social'>institucion_seguiridad_social</label>
        <select name="institucion_seguiridad_social" id="institucion_seguridad_social" size="3">
            <option value="IMSS">IMSS</option>
            <option value="ISSSTE">ISSSTE</option>
            <option value="OTROS">OTROS</option>
        </select>
        <br>


        <label for='numero_de_seguridad_social'>Numero de seguridad social</label>
        {{$errors->first("numero_de_seguridad_social")}}
        <input type='text' name='numero_de_seguridad_social' id='numero_de_seguridad_social' value="{{old('numero_de_seguridad_social')}}"><br>

        <input type='submit'>
    </form>

</body>
</html>