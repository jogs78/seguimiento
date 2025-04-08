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
 
 <div class="horizontal" style="margin-top:20px;"><p class="subtitulo">Tabla de proyectos</p></div>
 <table border="1">
  <thead>
   <th class="thfondo">Nombre proyecto</th>
   <th class="thfondo">Nombre asesor</th>
   <th class="thfondo">Nombre empresa</th>
   <th class="thfondo">Nombre estudiante(s)</th>
   <th class="thfondo">Seguimiento 1</th>
   <th class="thfondo">Seguimiento 2</th>
   <th class="thfondo">Seguimiento Final</th>
  </thead>
  <tbody>
 @foreach ($proyectos as $proyecto)
   <tr>
    <td style="padding:5px;">{{$proyecto->nombre}}</td>
    
    <td>
      <form action="{{route('coordinadores.asignarAsesor3',$proyecto->id)}}" method="post" style="display:flex " >
        @method('PUT')
        <select name="asesor_id" id="" style="display:flex ">
          <option value="" disabled selected>Elige un asesor...</option>
          @foreach ($asesores as $asesor)
          
            <option value="{{$asesor->id}}" 
              @if ($proyecto->asesor_id == $asesor->id)
                selected  
              @endif
              >{{$asesor->nombre}}</option>
          @endforeach
        </select>
        <input type="hidden" name="proyecto_id" value="{{$proyecto->id}}">
        @csrf
        @if( is_null($proyecto->asesor_id) )
          <input type="submit" value="ASIGNAR." class="boton">          
        @else
          <input type="submit" value="CAMBIAR." class="boton">          
        @endif
      </form>

    </td>

    <td style="padding:5px;">{{$proyecto->empresa->nombre}}</td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
          <li> {{ $estudiante->numero_control }} {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</li>
      @endforeach
    </td>


    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
        @if (is_null($estudiante->primer?->puntualidad_interno))
        <div class="centro"> <p>Sin calificaciones</p></div>
        <div class="centro"> <p> del asesor interno</p></div>
        @endif
        @if ( is_null($estudiante->primer?->puntualidad_externo) )
        <div class="centro"> <p>Sin calificaciones</p></div>
        <div class="centro"> <p> del asesor externo</p></div>
        @elseif(!is_null($estudiante->primer?->puntualidad_interno) && !is_null($estudiante->primer?->puntualidad_externo))
          <div class="centro" style="margin-bottom:10px;"><a  class="boton" href="{{route('estudiante.impresiones.seguimientos.primer',$estudiante->id)}}">Primer</a></div>
        @endif
      @endforeach
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
      @if (is_null($estudiante->segundo?->puntualidad_interno))
        <div class="centro"> <p>Sin calificaciones</p></div>
        <div class="centro"> <p> del asesor interno</p></div>
        @endif
        @if ( is_null($estudiante->segundo?->puntualidad_externo) )
        <div class="centro"> <p>Sin calificaciones</p></div>
        <div class="centro"> <p> del asesor externo</p></div>
        @elseif(!is_null($estudiante->segundo?->puntualidad_interno) && !is_null($estudiante->segundo?->puntualidad_externo))
          <div class="centro" style="margin-bottom:10px;"><a  class="boton" href="{{route('estudiante.impresiones.seguimientos.segundo',$estudiante->id)}}">Segundo</a></div>
        @endif
      @endforeach
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
      @if(is_null($estudiante->ultimo?->promedio_interno))
        <div class="centro"> <p>Sin calificaciones</p></div>
        <div class="centro"> <p> del asesor interno</p></div>
        @endif
        @if ( is_null($estudiante->ultimo?->promedio_externo))
        <div class="centro"> <p>Sin calificaciones</p></div>
        <div class="centro"> <p> del asesor externo</p></div>
        @elseif(!is_null($estudiante->ultimo?->promedio_interno) && !is_null($estudiante->ultimo?->promedio_externo))
          <div class="centro" style="margin-bottom:10px;"><a  class="boton" href="{{route('estudiante.impresiones.seguimientos.ultimo',$estudiante->id)}}">Ultimo</a></div>
        @endif
      @endforeach
    </td>
 


   </tr>
 @endforeach
</tbody>
</table>

@endsection