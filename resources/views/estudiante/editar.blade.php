@extends('plantillas.app')
<style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.centro{display: flex; justify-content: center;}
.titulo{text-align:center; font-size: 50px; font-weight: bold;}
.subtitulo{text-align:center; font-size: 45px; font-weight: bold;}
.parrafo{font-size: 30px; margin-top:30px;}
.boton{background-color: rgb(25, 118, 210); padding: 15px; border-radius: 5px; color: white; border: none; cursor: pointer; margin-top:30px;}
.boton:hover{background-color: rgb(74, 139, 204);}
.llenar{height: 25px; font-size: 18px;  margin-top: 30px;}
*{margin: 0; padding: 0;}
</style>
@section('encabezado')
@endsection
@section('contenido')
<div style="margin-top:20px;">
                <div class="horizontal"><p class="subtitulo">Actualizar Estudiante</p></div>
        <div class="centro"; style="margin-top: 60px; ">
        <form action="{{route("estudiantes.update",$estudiante->id)}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        @method('PUT')
        <label for='nombre' class="parrafo">Nombre/s</label>
        <input type='text' name='nombre' id='nombre' value="{{$estudiante->nombre}}" class="llenar"><br>

        <label for='apellido_paterno' class="parrafo">Apellido paterno</label>
        <input type='text' name='apellido_paterno' id='apellido_paterno'value="{{$estudiante->apellido_paterno}}" class="llenar"><br>

        <label for='apellido_materno' class="parrafo">Apellido materno</label>
        <input type='text' name='apellido_materno' id='apellido_materno'value="{{$estudiante->apellido_materno}}" class="llenar"><br>

        <label for='correo_electronico' class="parrafo">Correo Electronico</label>
        <input type='email' name='correo_electronico' id='correo_electronico'value="{{$estudiante->correo_electronico}}" class="llenar"><br>

        <input class="boton" type='submit' value="Actualizar">
    </form>
    </div>
</div>
    @endsection