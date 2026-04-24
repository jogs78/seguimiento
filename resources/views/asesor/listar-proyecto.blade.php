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
.caja{ border: 2px solid rgb(40, 95, 139); border-radius: 10px; text-align: center; }
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
    {{$proyecto->nombre}} <br> <br>
    <p>Enviar un correo al coordinador: </p>
    

@php
    $estudiante = optional($proyecto->estudiantes)->first();

    $coordinador = optional(
        optional(
            optional($estudiante)->carrera
        )->coordinador
    )->id;
@endphp

@if($coordinador)
    <form action="{{ route('correo.create', ['type' => 'coordinador', 'id' => $coordinador]) }}" method="GET">
        <div style="text-align: center;">
            <button type="submit">
                <img src="{{ asset('images/mail.png') }}" width="50" height="32">
            </button>
        </div>
    </form>
@else
    <p>No hay coordinador asignado para este proyecto.</p>
@endif
    
    <td>
    {{ $proyecto->empresa->nombre }}

    @php
        $externoId = optional($proyecto->externo)->id;
    @endphp

    @if($externoId)
        <form action="{{ route('correo.create', ['type' => 'externo', 'id' => $externoId]) }}" method="GET">
            <div style="text-align: center; padding-top: 8px">
                <button type="submit" title="Redactar un correo al asesor externo">
                    <img src="{{ asset('images/mail.png') }}" width="50" height="32">
                </button>
            </div>
        </form>
    @else
        <p>No hay asesor externo asignado.</p>
    @endif

    <br>
</td>

    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
        <div class="caja" style="padding-bottom: 25%; padding-top: 25%;">
          {{$estudiante->nombre}} {{$estudiante->apellido_paterno}} {{$estudiante->apellido_materno}}
          <form action="{{ route('correo.create', ['type' => 'estudiante', 'id' => $estudiante->id]) }}" method="GET">
              <div style="text-align: center;">
              <button type="submit" title="Redactar un correo al estudiante">
                 <img src="{{ asset('images/mail.png') }}"  width="50" height="32">
             </button>
             </div>
          </form>
        </div>
      @endforeach
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
      <div class="caja" style="padding-bottom: 4%; padding-top: 4%;"> 
          @if (is_null($estudiante->primer?->puntualidad_interno))
        <div style="text-align: center;">
          <a href="{{route('realizar-seguimientos',[$estudiante->id,'primer'])}}" >Dar Seguimiento a {{$estudiante->nombre}}</a>
        </div>
        @endif
         @if(!is_null($estudiante->primer?->puntualidad_interno) && !is_null($estudiante->primer?->puntualidad_externo))
         <div class="centro" style="margin-bottom:10px;">
            <a href="{{route('estudiante.impresiones.seguimientos.primer',$estudiante->id)}}" title="Descargar 1° seguimiento">
              <img src="{{ asset('images/UnoSegui.png') }}" width="40" height="65">
            </a>
          </div>
          @elseif(!is_null($estudiante->primer?->puntualidad_interno))
            <img src="{{ asset('images/IntSi.png') }}" width="65" height="70" title="Asesor Interno ya califico">
          @elseif(!is_null($estudiante->primer?->puntualidad_externo))
            <img src="{{ asset('images/ExtSi.png') }}" width="65" height="70" title="Asesor Externo ya califico">
          @endif
        @if ( is_null($estudiante->primer?->puntualidad_externo) )
        <div class="centro">
          <img src="{{ asset('images/ExtNo.png') }}" width="65" height="70" title="Asesor Externo no ha calificado">
        </div>
        @endif
      </div>
      @endforeach
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
      <div class="caja" style="padding-bottom: 4%; padding-top: 4%;"> 
      @if (is_null($estudiante->segundo?->puntualidad_interno))
        <div style="text-align: center;">
          <a href="{{route('realizar-seguimientos',[$estudiante->id,'segundo'])}}" >Dar Seguimiento a {{$estudiante->nombre}}</a>
        </div>
        @endif
      @if(!is_null($estudiante->segundo?->puntualidad_interno) && !is_null($estudiante->segundo?->puntualidad_externo))
          <div class="centro" style="margin-bottom:10px;">
            <a   href="{{route('estudiante.impresiones.seguimientos.segundo',$estudiante->id)}}" title="Descargar 2° seguimiento">
              <img src="{{ asset('images/DosSegui.png') }}" width="40" height="65">
            </a>
          </div>
        @elseif(!is_null($estudiante->segundo?->puntualidad_interno))
        <div class="centro">
          <img src="{{ asset('images/IntSi.png') }}" width="65" height="70" title="Asesor Interno ya califico">
        </div>
        @elseif(!is_null($estudiante->segundo?->puntualidad_externo))
        <div class="centro">
          <img src="{{ asset('images/ExtSi.png') }}" width="65" height="70" title="Asesor Externo ya califico">
        </div>
        @endif
      
        @if ( is_null($estudiante->segundo?->puntualidad_externo) )
       <div class="centro">
          <img src="{{ asset('images/ExtNo.png') }}" width="65" height="70" title="Asesor Externo no ha calificado">
        </div>
        @endif
      </div>
      @endforeach
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
      <div class="caja" style="padding-bottom: 4%; padding-top: 4%;"> 
      @if(is_null($estudiante->ultimo?->promedio_interno))
        <div style="text-align: center;">
          <a href="{{route('realizar-seguimientos',[$estudiante->id,'ultimo'])}}" >Dar Seguimiento a {{$estudiante->nombre}}</a>
        </div>
        @endif
      @if(!is_null($estudiante->ultimo?->promedio_interno) && !is_null($estudiante->ultimo?->promedio_externo))
          <div class="centro" style="margin-bottom:10px;">
            <a  href="{{route('estudiante.impresiones.seguimientos.ultimo',$estudiante->id)}}" title="Descargar 3° seguimiento">
              <img src="{{ asset('images/TresSegui.png') }}" width="40" height="65">
            </a>
          </div>
          @elseif(!is_null($estudiante->ultimo?->promedio_interno))
        <div class="centro">
          <img src="{{ asset('images/IntSi.png') }}" width="65" height="70" title="Asesor Interno ya califico">
        </div>
        @elseif(!is_null($estudiante->ultimo?->promedio_externo))
        <div class="centro">
          <img src="{{ asset('images/ExtSi.png') }}" width="65" height="70" title="Asesor Externo ya califico">
        </div>
        @endif
        @if ( is_null($estudiante->ultimo?->promedio_externo))
        <div class="centro">
          <img src="{{ asset('images/ExtNo.png') }}" width="65" height="70" title="Asesor Externo no ha calificado">
        </div>
        @endif
      </div>
      @endforeach
    </td>
   </tr>
 @endforeach
</tbody>
</table>
</div>
@endsection