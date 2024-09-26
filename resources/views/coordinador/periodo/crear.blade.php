<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creacion de periodo</title>
</head>
<body>
    <h2>Crear Periodos</h2>

    <form action="{{route("periodos.store")}}" method="POST" enctype="application/x-www-form-urlencoded" >
        @csrf
        <label for='nombreperiodo'>Nombre del Periodo</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombreperiodo' value="{{old('nombre')}}"><br>

        <label for='fecha_inicio'>Fecha de Inicio</label>
        {{$errors->first("fecha_inicio")}}
        <input type='date' name='fecha_inicio' id='fechainicio' value="{{old('fecha_inicio')}}"><br>

        <label for='fechaconclucion'>Fecha de Conclusion</label>
        {{$errors->first("fecha_final")}}
        <input type='date' name='fecha_final' id='fechaconclusion' value="{{old('fecha_final')}}"><br>

        <label for="">Rango de Fechas del 1° Reporte</label>
        <label for="">Del</label>
        {{$errors->first("fecha_inicio_1er_reporte")}} 
        <input type='date' name='fecha_inicio_1er_reporte' id='fechainicio' value="{{old('fecha_inicio_1er_reporte')}}"><br>
        <label for="">al</label>
        {{$errors->first("fecha_final_1er_reporte")}}  
        <input type='date' name='fecha_final_1er_reporte' id='fechainicio' value="{{old('fecha_final_1er_reporte')}}"><br>

        <label for="">Rango de Fechas del 2° Reporte</label>
        <label for="">Del</label>
        {{$errors->first("fecha_inicio_2do_reporte")}} 
        <input type='date' name='fecha_inicio_2do_reporte' id='fechainicio' value="{{old('fecha_inicio_2do_reporte')}}"><br>
        <label for="">al</label>
        {{$errors->first("fecha_final_2do_reporte")}} 
        <input type='date' name='fecha_final_2do_reporte' id='fechainicio' value="{{old('fecha_final_2do_reporte')}}"><br>

        <label for="">Rango de Fechas del Reporte Final</label>
        <label for="">Del</label> 
        {{$errors->first("fecha_inicio_reporte_final")}}
        <input type='date' name='fecha_inicio_reporte_final' id='fechainicio' value="{{old('fecha_inicio_reporte_final')}}"><br>
        <label for="">al</label> 
        {{$errors->first("fecha_final_reporte_final")}}
        <input type='date' name='fecha_final_reporte_final' id='fechainicio' value="{{old('fecha_final_reporte_final')}}"><br>

        <input type='submit'>

    </form>

</body>
</html>