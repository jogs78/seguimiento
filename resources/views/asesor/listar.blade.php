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
.parrafo{font-size: 20px;  font-weight: bold;}
.boton{background-color: rgb(25, 118, 210); color: white; border: none; cursor: pointer; margin-top:10px;margin-bottom:10px; margin-left:5px;  margin-right:5px;}
.boton:hover{background-color: rgb(74, 139, 204);}
.botonEditar{background-color: rgb(25, 118, 210); color: white; cursor: pointer; text-decoration: none; padding: 3px;  border-radius: 3px;border: none;}
.botonEditar:hover{background-color: rgb(74, 139, 204);}
.botonBorrar{background-color: rgb(210, 25, 25); color: white; cursor: pointer; text-decoration: none; padding: 3px;  border-radius: 3px; border: none;}
.botonBorrar:hover{background-color: rgb(204, 74, 74);}
.llenar{margin-top:16px; font-size: 18px; margin-left:12px;}
table{border: 2px solid rgb(19, 46, 68); border-collapse: collapse; margin-top:20px; }
th{border: 1px solid rgb(40, 95, 139);padding: 8px; }
.thcontenido{font-weight: normal;}
.thfondo{background-color: rgb(204, 216, 228);}
.bodydiv{margin-left: 20px; margin-right: 20px;}
</style>
@section('encabezado')
    
@endsection
@section('contenido')
<div class="horizontal" style="margin-top:20px;"><p class="subtitulo">Asesores Internos Registrados</p></div>
    <div style="margin-bottom: 40px;" class="centro">
    <table border="1">
        <thead>
            <th class="thfondo">ID</th>
            <th class="thfondo">NOMBRE</th>
            <th class="thfondo">APELLIDOS</th>
            <th class="thfondo">ACCIONES</th>
        </thead>
        <tbody>
        @foreach ($todos as $asesor)
        <tr>
            <td style="padding:5px;">{{$asesor->id}}</td>
            <td style="padding:5px;">{{$asesor->nombre}}</td>
            <td style="padding:5px;">{{$asesor->apellido_paterno}} {{$asesor->apellido_materno}}</td>
            <td style="padding:8px;">
                <a href="{{route("asesores.edit",$asesor->id)}}" class="botonEditar">Editar</a>
                @if (empty($asesor->proyecto?->nombre))
                <form action="{{route("asesores.destroy",$asesor->id)}}" method="post">
                @method('DELETE')
                @csrf
                <input type="submit" value="Borrar" class="botonBorrar" style="margin-top:5px;">

                </form>
                @else
                Asesor asigando al proyecto: <br> {{$asesor->proyecto->nombre}}
                 @endif

            </td>
        </tr>

        @endforeach
        </tbody>
    </table>
    </div>

    <div class="horizontal">
    <div class="centro"><a href="{{route("asesores.create")}}" class="boton" >Agregar un Asesor</a></div>

    <a href="{{route('generar-asesores.excel')}}" class="boton">Descargar lista</a>
    </div>


    @endsection