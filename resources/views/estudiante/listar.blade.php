<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>lista de alumnos registrados</h2>

    <table border="1">
        <thead>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>APELLIDO</th>
        </thead>
        <tbody>
        @foreach ($todos as $estudiante)
        <tr>
            <td>{{$estudiante->id}}</td>
            <td>{{$estudiante->nombre}}</td>
            <td>
                <a href="{{route("estudiantes.edit",$estudiante->id)}}">Editar</a>
                <form action="{{route("estudiantes.destroy",$estudiante->id)}}" method="post">
                @method('DELETE')
                @csrf
                <input type="submit" value="Borrar">

                </form>
            

            </td>
        </tr>

        @endforeach
        </tbody>
    </table>
    <a href="{{route("estudiantes.create")}}">agregar un estudiante</a>

</body>
</html>