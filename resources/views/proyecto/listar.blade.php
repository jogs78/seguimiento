<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>lista de proyectos</title>
</head>
<body>
    <h2>lista de proyectos registrados</h2>

    <table border="1">
        <thead>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>OBJETIVO GENERAL</th>
            <th>LUGAR</th>
            <th>INFORMACION</th>
            <th>JUSTIFICACION</th>
            <th>ASESOR ID</th>
            <th>EMPRESA ID</th>
            <th>PERIODO ID</th>
        </thead>
        <tbody>
        @foreach ($todos as $proyecto)
        <tr>
            <td>{{$proyecto->id}}</td>
            <td>{{$proyecto->nombre}}</td>
            <td>{{$proyecto->objetivo_general}}</td>
            <td>{{$proyecto->lugar}}</td>
            <td>{{$proyecto->informacion}}</td>
            <td>{{$proyecto->justificacion}}</td>
            <td>{{$proyecto->asesor_id}}</td>
            <td>{{$proyecto->empresa_id}}</td>
            <td>{{$proyecto->periodo_id}}</td>
            <td>
                <a href="{{route("proyectos.edit",$proyecto->id)}}">Editar</a>
                <form action="{{route("proyectos.destroy",$proyecto->id)}}" method="post">
                @method('DELETE')
                @csrf
                <input type="submit" value="Borrar">

                </form>
            

            </td>
        </tr>

        @endforeach
        </tbody>
    </table>
    <a href="{{route("proyectos.create")}}">agregar un proyecto</a>

</body>
</html>