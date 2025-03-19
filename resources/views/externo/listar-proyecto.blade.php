@extends('plantillas.app')
<style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.horizontal {display: flex; justify-content: center; width: 100%;}
.centro{display: flex; justify-content: center; align-items: center;}
.titulo{text-align:center; font-size: 50px; font-weight: bold;}
.subtitulo{text-align:center; font-size: 45px; font-weight: bold;}
.parrafo{font-size: 20px;  font-weight: bold;}
.boton{background-color: rgb(25, 118, 210); padding: 15px; border-radius: 5px; color: white; border: none; cursor: pointer;}
.boton:hover{background-color: rgb(74, 139, 204);}
.llenar{margin-top:16px; font-size: 18px; margin-left:12px;}
table{border: 2px solid rgb(19, 46, 68); border-collapse: collapse; }
th{border: 1px solid rgb(40, 95, 139);padding: 8px; }
.thcontenido{font-weight: normal;}
.thfondo{background-color: rgb(204, 216, 228);}
.bodydiv{margin-left: 20px; margin-right: 20px;}
</style>
@section('encabezado')
    
@endsection
@section('contenido')
<div style="margin-top:20px;">
<div class="horizontal"><p class="subtitulo">Lista de Proyectos Asignados</p></div>
  <table border="1" style="margin-top:30px;">
  <thead>
   <th class="thfondo">Nombre proyecto</th>
   <th class="thfondo">Nombre empresa</th>
   <th class="thfondo">Nombre Estudiante</th>
   <th class="thfondo">Primer Seguimiento</th>
   <th class="thfondo">Segundo Seguimiento</th>
   <th class="thfondo">Seguimiento Final</th>

  </thead>
  <tbody>
 @foreach ($proyectos as $proyecto)
   <tr>
    <td>
    {{$proyecto->nombre}}
    </td>
    
    <td>
    {{$proyecto->empresa->nombre}}
    <br>
    <!--{{$proyecto->periodo_id}}-->
    </td>

    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
          <li>{{$estudiante->nombre}}</li>
      @endforeach
    </td>
    <td>
    @foreach ($proyecto->estudiantes as $estudiante)
          @if (is_null($estudiante->primer?->puntualidad_externo))
          <a href="{{route('realizar-seguimientos',[$estudiante->id,'primer'])}}">Realizar</a>
          @else
              <div class="centro"> <p>Seguimiento</p></div>
              <div style="padding-bottom:10px;" class="centro"> <p>Realizado</p></div>
              @endif
{{--
              @if ( is_null($estudiante->primer->puntualidad_interno) )
                Sin calificaciones del asesor interno
              @else
                <div class="centro" style="margin-bottom:10px;"><a  class="boton" href="{{route('estudiante.impresiones.seguimientos.primer')}}">Primer</a></div>
              @endif
--}}

      @endforeach
    </td>
    <td>
    @foreach ($proyecto->estudiantes as $estudiante)
          @if (is_null($estudiante->segundo?->puntualidad_externo))
          <div class="centro"><a href="{{route('realizar-seguimientos',[$estudiante->id,'segundo'])}}">Realizar</a></div>
          @else
              <div class="centro"> <p>Seguimiento</p></div>
              <div style="padding-bottom:10px;" class="centro"> <p>Realizado</p></div>
              @if ( is_null($estudiante->segundo->puntualidad_interno) )
                <div class="centro"> <p>Sin calificaciones</p></div>
                <div class="centro"> <p> del asesor interno</p></div>
                @else
                <div class="centro" style="margin-bottom:10px;"><a  class="boton" href="{{route('estudiante.impresiones.seguimientos.segundo')}}">Segundo</a></div>
              @endif
            @endif
      @endforeach
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
          @if (is_null($estudiante->ultimo?->portada_externo))
          <a href="{{route('realizar-seguimientos',[$estudiante->id,'ultimo'])}}">Realizar</a>
          @else
              <div class="centro"> <p>Revisar</p></div>
              <div style="padding-bottom:10px;" class="centro"> <p>Seguimiento</p></div>
              @if ( is_null($estudiante->ultimo->promedio_interno) )
                Sin calificaciones del asesor interno
                @else
                <div class="centro" style="margin-bottom:10px;"><a  class="boton" href="{{route('estudiante.impresiones.seguimientos.ultimo')}}">Ultimo</a></div>
              @endif
            @endif
      @endforeach
    </td>
   </tr>
 @endforeach
</tbody>
</table>
</div>
@endsection