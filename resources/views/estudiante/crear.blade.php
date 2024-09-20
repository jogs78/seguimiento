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
        <input type='text' name='nombre' id='nombre'><br>
        
        <label for='apellido_paterno'>Apellido Paterno</label>
        <input type='text' name='apellido_paterno' id='apellido_paterno'><br>

        <label for='apellido_materno'>Apellido Materno</label>
        <input type='text' name='apellido_materno' id='apellido_materno'><br>

        <label for='correo_electronico'>Correo Electronico</label>
        <input type='text' name='correo_electronico' id='correo_electronico'><br>

        <label for='numero_de_control'>Numero de control</label>
        <input type='text' name='numero_de_control' id='numero_de_control'><br>

        <label for='nombreproyecto'>Nombre del Proyecto</label>
        <input type='text' name='nombre_del_proyecto' id='nombre_del_proyecto'><br>

        <label for='telefono'>Telefono</label>
        <input type='text' name='telefono' id='telefono'><br>

        <label for='carrera_id'>Carrera</label>
        <select name="carrera_id" id="carrera_id">
            @foreach ($carreras as $carrera)
                <option value="{{$carrera->id}}">{{$carrera->nombre}}</option>
            @endforeach
        </select>
        <br>

        <label for='direccion'>Direccion</label>
        <input type='text' name='direccion' id='direccion'><br>

        <label for='institucion_seguiridad_social'>institucion_seguiridad_social</label>

        <select name="institucion_seguiridad_social" id="institucion_seguridad_social" size="3">
            <option value="IMSS">IMSS</option>
            <option value="ISSSTE">ISSSTE</option>
            <option value="OTROS">OTROS</option>
        </select>
        <br>


        <label for='numero_de_seguridad_social'>Numero de seguridad social</label>
        <input type='text' name='numero_de_seguridad_social' id='numero_de_seguridad_social'><br>

        <input type='submit'>
    </form>

</body>
</html>