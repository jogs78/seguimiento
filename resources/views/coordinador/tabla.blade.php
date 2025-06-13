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
<div style="margin-right: 35px;">
<input type="text" id="busqueda" placeholder="Buscar por nombre de proyecto /empresa /estudiante" autocomplete="off" style="width: 100%; padding: 10px; " >
</div>
<div id="sugerencias" style="background: white; border: 1px solid #ccc; display: none; position: absolute; z-index: 999;"></div>

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
          <input type="submit" value="ASIGNAR." class="boton" title="Asignar un asesor">          
        @else
          <input type="submit" value="CAMBIAR." class="boton" title="Cambiar asesor">          
        @endif
      </form>
      @if ($proyecto->asesor)
        <form   action="{{ route('correo.create', ['type' => 'asesor', 'id' => $proyecto->asesor->id]) }}" method="GET">
             <div style="text-align: right; margin-right: 12%">
              <button type="submit" title="Redactar un correo al asesor interno">
                 <img src="{{ asset('images/mail.png') }}" width="50" height="32">
             </button>
             </div>
        </form>
    @else
        <p style="margin-top:16px;">Sin asesor Interno</p>
    @endif
    </td>

    <td style="padding:5px;">{{$proyecto->empresa->nombre}}
    @if ($proyecto->externo)
        <form action="{{ route('correo.create', ['type' => 'externo', 'id' => $proyecto->externo->id]) }}" method="GET">
            <div style="text-align: center; padding-top: 8px">
              <button type="submit" title="Redactar un correo al asesor externo">
                 <img src="{{ asset('images/mail.png') }}"  width="50" height="32">
             </button>
             </div>
        </form>
    @else
        <p style="margin-top:16px;">Sin asesor Externo</p>
    @endif
    </td>
    <td>
      @foreach ($proyecto->estudiantes as $estudiante)
          <div style="padding:5px;"> {{ $estudiante->numero_control }} {{ $estudiante->nombre }} {{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }}</div>
          <form action="{{ route('correo.create', ['type' => 'estudiante', 'id' => $estudiante->id]) }}" method="GET">
              <div style="text-align: center;">
              <button type="submit" title="Redactar un correo al estudiante">
                 <img src="{{ asset('images/mail.png') }}"  width="50" height="32">
             </button>
             </div>
          </form>
      @endforeach
    </td>


    <td style="text-align: center; vertical-align: middle;">
      @foreach ($proyecto->estudiantes as $estudiante)
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
        @if (is_null($estudiante->primer?->puntualidad_interno))
        <img src="{{ asset('images/IntNo.png') }}" width="65" height="70" title="Asesor Interno no ha calificado">
        @endif
        @if ( is_null($estudiante->primer?->puntualidad_externo) )
         <img src="{{ asset('images/ExtNo.png') }}" width="65" height="70" title="Asesor Externo no ha calificado">
        @endif
        
      @endforeach
    </td>
    <td style="text-align: center; vertical-align: middle;">
      @foreach ($proyecto->estudiantes as $estudiante)
       @if(!is_null($estudiante->segundo?->puntualidad_interno) && !is_null($estudiante->segundo?->puntualidad_externo))
          <div class="centro" style="margin-bottom:10px;">
            <a  href="{{route('estudiante.impresiones.seguimientos.segundo',$estudiante->id)}}" title="Descargar 2° seguimiento">
              <img src="{{ asset('images/DosSegui.png') }}" width="40" height="65">
            </a>
          </div>
          @elseif(!is_null($estudiante->segundo?->puntualidad_interno))
        <img src="{{ asset('images/IntSi.png') }}" width="65" height="70" title="Asesor Interno ya califico">
        @elseif(!is_null($estudiante->segundo?->puntualidad_externo))
        <img src="{{ asset('images/ExtSi.png') }}" width="65" height="70" title="Asesor Externo ya califico">
        @endif
      @if (is_null($estudiante->segundo?->puntualidad_interno))
        <img src="{{ asset('images/IntNo.png') }}" width="65" height="70" title="Asesor Interno no ha calificado">
        @endif
        @if ( is_null($estudiante->segundo?->puntualidad_externo) )
       <img src="{{ asset('images/ExtNo.png') }}" width="65" height="70" title="Asesor Externo no ha calificado">
        @endif
       
      @endforeach
    </td>
    <td style="text-align: center; vertical-align: middle;">
      @foreach ($proyecto->estudiantes as $estudiante)
      @if(!is_null($estudiante->ultimo?->promedio_interno) && !is_null($estudiante->ultimo?->promedio_externo))
          <div class="centro" style="margin-bottom:10px;">
            <a href="{{route('estudiante.impresiones.seguimientos.ultimo',$estudiante->id)}}" title="Descargar 3° seguimiento">
              <img src="{{ asset('images/TresSegui.png') }}" width="40" height="65">
            </a>
          </div>
          @elseif(!is_null($estudiante->ultimo?->promedio_interno))
        <img src="{{ asset('images/IntSi.png') }}" width="65" height="70"  title="Asesor Interno ya califico">
        @elseif(!is_null($estudiante->ultimo?->promedio_externo))
        <img src="{{ asset('images/ExtSi.png') }}" width="65" height="70" title="Asesor Externo ya califico">
        @endif
      @if(is_null($estudiante->ultimo?->promedio_interno))
        <img src="{{ asset('images/IntNo.png') }}" width="65" height="70" title="Asesor Interno no ha calificado">
        @endif
        @if ( is_null($estudiante->ultimo?->promedio_externo))
        <img src="{{ asset('images/ExtNo.png') }}" width="65" height="70" title="Asesor Externo no ha calificado">
        @endif
        
      @endforeach
    </td>
 


   </tr>
 @endforeach
</tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputBusqueda = document.getElementById('busqueda');
    const sugerenciasDiv = document.getElementById('sugerencias');

    inputBusqueda.addEventListener('input', function () {
        const query = inputBusqueda.value;

        if (query.length < 2) {
            sugerenciasDiv.style.display = 'none';
            return;
        }

        fetch(`/proyectos/buscar?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                sugerenciasDiv.innerHTML = '';
                if (data.length > 0) {
                    sugerenciasDiv.style.display = 'block';
                    data.forEach(proyecto => {
                        const div = document.createElement('div');
                        div.textContent = proyecto.nombre;
                        div.style.padding = '5px';
                        div.style.cursor = 'pointer';

                        div.addEventListener('click', function () {
                            inputBusqueda.value = proyecto.nombre;
                            sugerenciasDiv.style.display = 'none';
                            filtrarTabla(proyecto.nombre);
                        });

                        sugerenciasDiv.appendChild(div);
                    });
                } else {
                    sugerenciasDiv.style.display = 'none';
                }
            });
    });

    function filtrarTabla(valor) {
        const filas = document.querySelectorAll('table tbody tr');
        filas.forEach(fila => {
            const texto = fila.textContent.toLowerCase();
            if (texto.includes(valor.toLowerCase())) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    inputBusqueda.addEventListener('keyup', function () {
        filtrarTabla(this.value);
    });
});
</script>

@endsection