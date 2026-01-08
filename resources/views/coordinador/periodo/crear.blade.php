@extends('plantillas.app')
<style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.horizontal {display: flex; justify-content: center; width: 100%;}
.centro{display: flex; justify-content: center;}
.titulo{text-align:center; font-size: 50px; font-weight: bold;}
.subtitulo{text-align:center; font-size: 45px; font-weight: bold;}
.parrafo{font-size: 20px;  font-weight: bold; margin-top: 20px;}
.boton{background-color: rgb(25, 118, 210); color: white; border: none; cursor: pointer; margin-top:10px;margin-bottom:10px; margin-left:5px;  margin-right:5px;}
.boton:hover{background-color: rgb(74, 139, 204);}
.llenar{margin-top:20px; font-size: 18px; margin-left:12px;}
table{border: 2px solid rgb(19, 46, 68); border-collapse: collapse; margin-top:20px; }
th{border: 1px solid rgb(40, 95, 139);padding: 8px; }
.thcontenido{font-weight: normal;}
.thfondo{background-color: rgb(204, 216, 228);}
.bodydiv{margin-left: 20px; margin-right: 20px;}
.caja{ border: 2px solid rgb(40, 95, 139); border-radius: 10px; padding-top: 20px;padding-bottom: 20px;padding-left: 10px; padding-right: 20px;}
     .sugerencias-box {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        z-index: 1000;
        width: 250px;
        max-height: 200px;
        overflow-y: auto;
        right: 0; /* Alineado a la derecha del input */
        top: 100%; /* Justo debajo del input */
    }

    .sugerencia-item {
        padding: 5px 10px;
        cursor: pointer;
    }

    .sugerencia-item:hover {
        background-color: #f0f0f0;
    }
</style>
@section('encabezado')
    
@endsection
@section('contenido')

<div class="horizontal" style="margin-top:20px;"><p class="subtitulo">Crear Periodo</p></div>

<div class="centro"; style="margin-top: 60px; ">
    <form action="{{route("periodos.store")}}" method="POST" enctype="application/x-www-form-urlencoded" >
        @csrf
        <div class="caja" style="margin-bottom:30px;">
            <label for='nombreperiodo' class="parrafo">Nombre del Periodo</label>
            {{$errors->first("nombre")}}
            <input type='text' name='nombre' id='nombreperiodo' value="{{old('nombre')}}" class="llenar"><br>

            <label for='fecha_inicio' class="parrafo">Fecha de Inicio del periodo</label>
            {{$errors->first("fecha_inicio")}}
            <input type='date' name='fecha_inicio' id='fechainicio' value="{{old('fecha_inicio')}}" class="llenar"><br>

            <label for='fechaconclucion' class="parrafo">Fecha de Conclusion del periodo</label>
            {{$errors->first("fecha_final")}}
            <input type='date' name='fecha_final' id='fechaconclusion' value="{{old('fecha_final')}}" class="llenar" style="margin-bottom: 20px;"><br>
        </div>

        <label for="" class="parrafo">Rango de Fechas del 1° Reporte</label> <br>
        <label for="" class="parrafo">Inicia</label>
        {{$errors->first("fecha_inicio_1er_reporte")}} 
        <input type='date' name='fecha_inicio_1er_reporte' id='fechainicio' value="{{old('fecha_inicio_1er_reporte')}}" class="llenar">
        <label for="" class="parrafo">Termina</label>
        {{$errors->first("fecha_final_1er_reporte")}}  
        <input type='date' name='fecha_final_1er_reporte' id='fechainicio' value="{{old('fecha_final_1er_reporte')}}" class="llenar" style="margin-bottom: 20px;"><br>

        <label for="" class="parrafo">Rango de Fechas del 2° Reporte</label><br>
        <label for="" class="parrafo">Inicia</label>
        {{$errors->first("fecha_inicio_2do_reporte")}} 
        <input type='date' name='fecha_inicio_2do_reporte' id='fechainicio' value="{{old('fecha_inicio_2do_reporte')}}" class="llenar">
        <label for="" class="parrafo">Termina</label>
        {{$errors->first("fecha_final_2do_reporte")}} 
        <input type='date' name='fecha_final_2do_reporte' id='fechainicio' value="{{old('fecha_final_2do_reporte')}}" class="llenar" style="margin-bottom: 20px;"> <br>

        <label for="" class="parrafo">Rango de Fechas del Reporte Final</label><br>
        <label for="" class="parrafo">Inicia</label> 
        {{$errors->first("fecha_inicio_reporte_final")}}
        <input type='date' name='fecha_inicio_reporte_final' id='fechainicio' value="{{old('fecha_inicio_reporte_final')}}" class="llenar">
        <label for="" class="parrafo">Termina</label> 
        {{$errors->first("fecha_final_reporte_final")}}
        <input type='date' name='fecha_final_reporte_final' id='fechainicio' value="{{old('fecha_final_reporte_final')}}" class="llenar"><br>

        <input type='submit' class="boton" style="margin-top: 30px;">

    </form>
</div>

    @endsection