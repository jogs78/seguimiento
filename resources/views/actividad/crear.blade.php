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
.boton{background-color: rgb(25, 118, 210); padding: 15px; border-radius: 5px; color: white; border: none; cursor: pointer; margin-top:30px;}
.boton:hover{background-color: rgb(74, 139, 204);}
.llenar{margin-top:16px; font-size: 18px; margin-left:12px;}
table{border: 2px solid rgb(19, 46, 68); border-collapse: collapse; }
th{border: 1px solid rgb(40, 95, 139);padding: 8px; }
.thcontenido{font-weight: normal;}
.thfondo{background-color: rgb(204, 216, 228);}
.bodydiv{margin-left: 20px; margin-right: 20px;}
.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}
</style>
@section('encabezado')
    
@endsection
@section('contenido')
<div style="margin-top:20px;">
<div class="horizontal" style="margin-bottom:20px;"><p class="subtitulo">Haz una descripcion detallada de las actividades especificas que tienes planeadas</p></div> <br>
<div class="centro">
    <form action="{{route("proyectos.actividades.store",$proyecto->id)}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        <div id="formularios-container">
          <!-- Aquí se agregan los formularios clonados -->
         </div>

        <div class="centro">
          <button type="button" class="boton" onclick="agregarFormulario()">Agregar otra actividad</button>
        </div>

        
        <div class="centro"><input type='submit' class="boton"></div> <!-- Crear un boton o enlace para tener mas actividades -->
    </form>
</div>
</div>

    <!-- Template oculto -->
      <template id="formulario-template">
        <div class="formulario-actividad" style="margin-bottom: 30px;">
          <label for='nombre' class="parrafo">Nombre de la actividad</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre[]' value="{{old('nombre')}}" class="llenar"><br>

        <div class="form-group">
        <label for='descripcion' class="parrafo">Describe como realizaras tal actividad </label>
        {{$errors->first("descripcion")}}
        <textarea name='descripcion[]'  value="{{old('descripcion')}}" style="margin-top:16px; font-size: 18px;  resize: none;" oninput="autoResize(this)"></textarea><br>
        </div>

        <label for='semanas' class="parrafo">En cuantas semanas reliazaras tal actividad</label>
        {{$errors->first("semanas")}}
        <input type='number' name='semanas[]' value="{{old('semanas')}}" style="font-size: 18px; margin-left:12px;"><br>

        <label for='orden' class="parrafo">Cual es el orden de esta actividad (primera, segunda...)</label>
        {{$errors->first("orden")}}
        <input type='number' name='orden[]' value="{{old('orden')}}" class="llenar"><br>

          <hr style="margin-top: 20px;">
        </div>
      </template>
    @endsection

<script>
function agregarFormulario() {
  const template = document.getElementById('formulario-template');
  const container = document.getElementById('formularios-container');
  const clone = template.content.cloneNode(true);
  container.appendChild(clone);

  // Autoajustar textareas que se agregan dinámicamente
  const textareas = container.querySelectorAll('textarea');
  textareas.forEach(autoResize);
}

function autoResize(textarea) {
  textarea.style.height = 'auto';
  textarea.style.height = textarea.scrollHeight + 'px';
}

document.addEventListener('DOMContentLoaded', () => {
  agregarFormulario(); // Inserta el primer formulario

  // Si ya hay un textarea con id='descripcion' por alguna razón previa
  const ta = document.getElementById('descripcion');
  if (ta) autoResize(ta);
});
</script>