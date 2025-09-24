@extends('plantillas.app')
<style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.horizontal {display: flex; justify-content: center; width: 100%;}
.centro{display: flex; justify-content: center;}
.subtitulo{text-align:center; font-size: 45px; font-weight: bold;}
.parrafo{font-size: 30px; margin-top:30px;}
.boton{background-color: rgb(25, 118, 210); padding: 15px; border-radius: 5px; color: white; text-decoration: none; cursor: pointer; }
.boton:hover{background-color: rgb(74, 139, 204);}
.llenar{height: 25px; font-size: 18px;}
.tercio { border: 2px solid rgb(19, 46, 68); width: 30%; padding: 20px; border-radius: 10px; margin: 15px; display: flex; justify-content: center; align-items: center;}
table {
  border-collapse: separate;
  border-spacing: 0;
  width: 60%;
  margin: 10px auto; 
  border: 2px solid rgb(40, 95, 139);
  border-radius: 12px;
  overflow: hidden;
}

    thead th {
      background-color: rgb(40, 95, 139);
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 18px;
    }

    tbody td {
      border-top: 2px solid rgb(40, 95, 139);
      border-right: 2px solid rgb(40, 95, 139);
      padding: 12px;
      text-align: center;
      font-size: 16px;
    }

    tbody td:last-child {
      border-right: none;
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    /* Bordes redondeados en esquinas */
    thead th:first-child {
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
    }

    tbody tr:last-child td:first-child {
      border-bottom-left-radius: 8px;
    }

    tbody tr:last-child td:last-child {
      border-bottom-right-radius: 8px;
    }
*{margin: 0; padding: 0;}
</style>
@section('encabezado')
    
@endsection
@section('contenido')

<div style="margin-top:20px; height: 20%;">
    <div class="horizontal"><p class="subtitulo">Verifica aqui tus seguimientos</p></div>
    <div class="horizontal" style="margin-top:40px;">
        <div class="tercio">
            @if(is_null($primer))
                No hay primer seguimiento
            @elseif ( is_null($primer->promedio_interno) )
                Sin calificaciones del asesor interno<br>
            @elseif ( is_null($primer->promedio_externo) )
                Sin calificaciones del asesor externo<br>
            @else
                <a  class="boton" style="margin-left: 0px;" href="{{route('estudiante.impresiones.seguimientos.primer')}}" style="margin-right:80px;">Primer Seguimiento</a>
            @endif
        </div>
        <div class="tercio">
            @if(is_null($segundo))
                No hay segundo seguimiento
            @elseif ( is_null($segundo->promedio_interno) )
                Sin calificaciones del asesor interno<br>
            @elseif ( is_null($segundo->promedio_externo) )
                Sin calificaciones del asesor externo<br>
            @else
                <a  class="boton" style="margin-right: 0px;" href="{{route('estudiante.impresiones.seguimientos.segundo')}}">Segundo Seguimiento</a>
            @endif
        </div>
        <div class="tercio" >
            @if(is_null($ultimo))
                No hay seguimiento final
            @elseif ( is_null($ultimo->promedio_interno) )
                Sin calificaciones del asesor interno<br>
            @elseif ( is_null($ultimo->promedio_externo) )
                Sin calificaciones del asesor externo<br>
            @else
                <a  class="boton" style="margin-right: 0px;" href="{{route('estudiante.impresiones.seguimientos.ultimo')}}" style="margin-left:80px;">Ultimo Seguimiento</a>
            @endif
        </div>
    </div>

    <table>
    <thead>
      <tr>
        <th colspan="3">Redactar un correo para tu:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
            Coordinador
    @php
    $coordinador = optional($proyecto->periodo->coordinador)->id;
    @endphp

@if($coordinador)
    <form action="{{ route('correo.create', ['type' => 'coordinador', 'id' => $coordinador->id]) }}" method="GET">
        <div style="text-align: center;">
            <button type="submit" title="Redactar un correo al coordinador">
                <img src="{{ asset('images/mail.png') }}" width="50" height="32">
            </button>
        </div>
    </form>
@else
    <p>No hay coordinador asignado para este proyecto.</p>
@endif
        </td>
        <td>
            Asesor Interno
            <form action="{{ route('correo.create', ['type' => 'asesor', 'id' => $proyecto->asesor->id]) }}" method="GET">
            <div style="text-align: center; padding-top: 8px">
              <button type="submit" title="Redactar un correo al asesor externo">
                 <img src="{{ asset('images/mail.png') }}"  width="50" height="32">
             </button>
             </div>
        </form>
        </td>
        <td>
            Asesor Externo
            <form action="{{ route('correo.create', ['type' => 'externo', 'id' => $proyecto->externo->id]) }}" method="GET">
            <div style="text-align: center; padding-top: 8px">
              <button type="submit" title="Redactar un correo al asesor externo">
                 <img src="{{ asset('images/mail.png') }}"  width="50" height="32">
             </button>
             </div>
        </form>
        </td>
      </tr>
    </tbody>
  </table>
</div>

@endsection
