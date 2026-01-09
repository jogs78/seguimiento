@extends('plantillas.app')
<style>
.actual {
  font-style: italic;
}

/* Estilo para el efecto hover */
.actual:hover {
  background-color: #e0f7fa; /* Celeste claro */
}

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




<div class="horizontal" style="margin-top:20px;"><p class="subtitulo">Lista de Periodos</p></div>
 <div style="margin-bottom: 40px;" class="centro">
  <table border="1" >
  <thead>
   <th class="thfondo">ID</th>
   <th class="thfondo">ACTUAL</th>
   <th class="thfondo">NOMBRE</th>
   <th class="thfondo">ACCIONES</th>
  </thead>
  
  <tbody >
  
   @foreach ($periodos as $periodo)     
   <tr @if ($periodo->id == $actual) class="actual"  @endif >
    <td>{{$periodo->id}}</td>
    <td>
      <form action="{{route('configuraciones.update', $configuracion->id )}}" method="post"  id="formulario_actual_{{$periodo->id}}">
        @method('PUT')
        @csrf
        <input type="hidden" name="valor" value="{{$periodo->id}}">
        <input type="submit" value="ASIGNAR ACTUAL" class="botonEditar" style="margin:5px;">
      </form>
    </td>
    <td>{{$periodo->nombre}}</td>
    <td style="padding:8px;">
     <a href="{{route("periodos.edit",$periodo->id)}}" class="botonEditar">EDITAR</a>
     <form action="{{route("periodos.destroy",$periodo->id)}}" method="post" onsubmit="return confirmarEliminacion()">
      @method('DELETE')
      @csrf
      <input type="submit" value="BORRAR" class="botonBorrar" style="margin-top:5px;">
    </form>

    </td>
   </tr>
   @endforeach  
  </tbody>
 </table>
</div>
 <div class="centro"><a href="{{route("periodos.create")}}" class="boton" >Agregar un periodo</a></div>
<script>
  function confirmarEliminacion() {
    return confirm("⚠️ Al eliminar este Periodo ya no se podrá restaurar.\n¿Seguro que deseas eliminar el Periodo?");
    }
</script>
@if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    {{-- Error general --}}
@if(session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session("error") }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endsection