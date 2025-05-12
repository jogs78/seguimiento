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
<div class="horizontal"><p class="subtitulo">Actualize una Actividad</p></div><br>
    <div class="centro">
    <form action="{{route("proyectos.actividades.update",[$proyecto->id, $actividad->id])}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        @method('PUT')
        <label for='nombre' class="parrafo">Nombre de la actividad</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombre' value="{{$actividad->nombre}}" class="llenar"><br>
        
        <div class="form-group">
        <label for="descripcion" class="parrafo">Describe cómo realizarás tal actividad</label>
        {{$errors->first("descripcion")}}
        <textarea name="descripcion" id="descripcion" style="margin-top:16px; font-size: 18px;  resize: none;" oninput="autoResize(this)">{{$actividad->descripcion}}</textarea>
        </div>

        <label for='semanas' class="parrafo">En cuantas semanas reliazaras tal actividad</label>
        {{$errors->first("semanas")}}
        <input type='number' name='semanas' id='semanas' value="{{$actividad->semanas}}" class="llenar"><br>

        <label for='orden' class="parrafo">Cual es el orden de esta actividad</label>
        {{$errors->first("orden")}}
        <input type='number' name='orden' id='orden' value="{{$actividad->orden}}" class="llenar"><br>

        <div class="centro"><input type='submit' class="boton"></div>
    </form>
    </div>
</div>
@endsection

<script>
  function autoResize(textarea) {
    textarea.style.height = 'auto'; // Reinicia el alto
    textarea.style.height = textarea.scrollHeight + 'px'; // Ajusta al contenido
  }

  // Llama la función al cargar si hay contenido inicial
  window.addEventListener('DOMContentLoaded', () => {
    const ta = document.getElementById('descripcion');
    if (ta) autoResize(ta);
  });
</script>