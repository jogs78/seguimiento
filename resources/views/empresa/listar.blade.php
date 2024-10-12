<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>lista de empresas</title>
</head>
<body>
    <h2>lista de empresas registradas</h2>

    <table border="1">
        <thead>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>GIRO</th>
            <th>RFC</th>
            <th>DIRECCION</th>
            <th>TELEFONO</th>
            <th>correo electronico</th>
            <th>TITULAR DE LA EMPRESA</th>
            <th>PUESTO DEL TITULAR</th>
            <th>ASESOR EXTERNO</th>
            <th>PUESTO DEL ASESOR</th>
            <th>INFORMACION ADICIONAL</th>
            <th>ACCIONES</th>
        </thead>
        <tbody>
        @foreach ($todos as $empresa)
        <tr>
            <td>{{$empresa->id}}</td>
            <td>{{$empresa->nombre}}</td>
            <td>{{$empresa->giro}}</td>
            <td>{{$empresa->rfc}}</td>
            <td>{{$empresa->direccion}}</td>
            <td>{{$empresa->telefono}}</td>
            <td>{{$empresa->correo}}</td>
            <td>{{$empresa->titular}}</td>
            <td>{{$empresa->puesto_titular}}</td>
            <td>{{$empresa->asesor_externo}}</td>
            <td>{{$empresa->puesto_asesor}}</td>
            <td>{{$empresa->informacion}}</td>
            <td>
                <a href="{{route("empresas.edit",$empresa->id)}}">Editar</a>
                <form action="{{route("empresas.destroy",$empresa->id)}}" method="post">
                @method('DELETE')
                @csrf
                <input type="submit" value="Borrar">

                </form>
            

            </td>
        </tr>

        @endforeach
        </tbody>
    </table>
    <a href="{{route("empresas.create")}}">agregar una empresa</a>

</body>
</html>