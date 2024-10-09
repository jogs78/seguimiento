<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto</title>
</head>
<body>
    <h2>Proyecto</h2>

    <form action="{{route("proyectos.store")}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        <label for='nombre'>Nombre/s</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombre' value="{{old('nombre')}}"><br>

        <label for='objetivo'>Objetivo General</label>
        <!--{{$errors->first("nombre")}}-->
        <input type='text' name='objetivo_general' id='objetivo_general' value="{{old('objetivo_general')}}"><br>

        <label for='lugar'>Lugar</label>
        <!--{{$errors->first("nombre")}}-->
        <input type='text' name='lugar' id='lugar' value="{{old('lugar')}}"><br>

        <label for='informacion'>Informacion</label>
        <!--{{$errors->first("nombre")}}-->
        <input type='text' name='informacion' id='informacion' value="{{old('informacion')}}"><br>

        <label for='justificacion'>Justificacion</label>
        <!--{{$errors->first("nombre")}}-->
        <input type='text' name='justificacion' id='justificacion' value="{{old('justificacion')}}"><br>


        <label for='asesor_id'>Asesor</label>
        <select name="asesor_id" id="asesor_id">
            @foreach ($asesores as $asesor)
                <option value="{{$asesor->id}}">{{$asesor->nombre}}</option>
            @endforeach
        </select>
        <br>

        <label for='empresa_id'>Empresa</label>
        <select name="empresa_id" id="empresa_id">
            @foreach ($empresas as $empresa)
                <option value="{{$empresa->id}}">{{$empresa->nombre}}</option>
            @endforeach
        </select>
        <br>

        <label for='periodo_id'>Periodo</label>
        <select name="periodo_id" id="Periodo_id">
            @foreach ($periodos as $periodo)
                <option value="{{$periodo->id}}">{{$periodo->nombre}}</option>
            @endforeach
        </select>
        <br>
        
       
        <input type='submit'>
    </form>

</body>
</html>