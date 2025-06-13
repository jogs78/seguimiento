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
.botonEditar{background-color: rgb(25, 118, 210); color: white; cursor: pointer; text-decoration: none; padding: 3px;  border-radius: 3px; border: none;}
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

<div style="margin-top:20px;">
<div class="horizontal"><p class="subtitulo">Los datos tu proyecto son:</p></div>
    <div class="bodydiv">
        <div class="horizontal" style="margin-top:30px;">
            <p class="parrafo"> ID:</p> <p class="llenar"  style="margin-right:12px;">{{$proyecto->id}}</p>
        </div>
        <div class="horizontal" style="margin-top:15px;">
            <p class="parrafo">Nombre del proyecto:</p> <p class="llenar">{{$proyecto->nombre}} </p> 
        </div>
        <div class="horizontal" style="margin-top:15px;">
            <p class="parrafo">Objetivo General:</p> <p class="llenar">{{$proyecto->objetivo_general}}</p> 
        </div>
        <div class="horizontal" style="margin-top:15px;">
            <p class="parrafo">Empresa:</p> <p class="llenar">{{$proyecto->empresa->nombre}}</p> 
        </div>
        <div class="horizontal" style="margin-top:15px;">
            <p class="parrafo">Informacion:</p> <p class="llenar">{{$proyecto->informacion}}</p> 
        </div>
        <div class="horizontal" style="margin-top:15px;">
            <p class="parrafo">Justificacion:</p> <p class="llenar">{{$proyecto->justificacion}}</p> 
        </div>
        <div class="horizontal" style="margin-top:15px;">
            <p class="parrafo">Periodo:</p> <p class="llenar">{{$proyecto->periodo->nombre}}</p> 
        </div>
    </div>
<div class="horizontal"><p class="subtitulo"  style="margin-top:30px;">y sus Actividades:</p></div>
<div class="horizontal">
<table style="margin-top:30px; margin-bottom:40px; align-self: center;" >
 <thead>
  <th class="thfondo">Nombre</th>
  <th class="thfondo">Semanas</th>
  <th class="thfondo">Orden</th>
  <th class="thfondo">Acciones</th>
 </thead>
 <tbody>
  @forelse ($proyecto->actividades as $actividad)
  <tr>
   <th class="thcontenido">{{$actividad->nombre}}</th>
   <th class="thcontenido">{{$actividad->semanas}}</th>
   <th class="thcontenido">{{$actividad->orden}}</th>
   <th style="padding:8px;">
                <a href="{{route("proyectos.actividades.edit",[$proyecto->id,$actividad->id])}}" class="botonEditar">Editar</a>
                <form id="formEliminar{{ $actividad->id }}" action="{{ route('proyectos.actividades.destroy', [$proyecto->id, $actividad->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="botonBorrar" style="margin-top:5px;" onclick="confirmarEliminacion({{ $actividad->id }})">
                        Borrar
                    </button>
                </form>
            

    </th>
  </tr>
  @empty
  <tr>
   <th colspan="3">Sin actividades</th>
  </tr>
 @endforelse 
</tbody>    
</table>
</div>
    <div class="horizontal">
        <a href="{{route('proyectos.actividades.create', $proyecto->id)}}" class="boton">Agregar Una Actividad</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEliminar' + id).submit();
            }
        });
    }
</script>
@endsection

