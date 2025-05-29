@extends('plantillas.app')
<style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.horizontal {display: flex; justify-content: center; width: 100%;}
.centro{display: flex; justify-content: center; margin-bottom: 1rem;}
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
    width: 99%;
}
</style>

@section('encabezado')
    
@endsection

@section('contenido')
@if ($usuario->usa)
    <h1 class="centro">Enviar Correo a {{ $usuario->usa->nombre }} {{ $usuario->usa->apellido_paterno }} {{ $usuario->usa->apellido_materno }} </h1>
@else
    <h1 class="centro">Enviar Correo a Usuario desconocido</h1>
@endif

<div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('correo.send') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $usuario->nombre_usuario }}">

        <div class="form-group">
            <label class="parrafo">Correo destino</label>
            <input type="email"  value="{{ $usuario->nombre_usuario }}" disabled>
        </div>

        <div class="form-group">
            <label class="parrafo">Asunto</label>
            <input type="text" name="subject" required>
        </div>

        <div class="form-group">
            <label class="parrafo">Contenido</label>
            <textarea name="content" rows="5" required></textarea>
        </div>

        <button type="submit" class="boton">Enviar Correo</button>
    </form>
</div>
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = "{{ route('home') }}";
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

{{-- Errores de validación --}}
@if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let errores = {!! json_encode($errors->all()) !!};

        Swal.fire({
            icon: 'error',
            title: 'Errores en el formulario',
            html: '<ul style="text-align: left;">' + errores.map(e => `<li>${e}</li>`).join('') + '</ul>',
            confirmButtonText: 'Corregir'
        });
    </script>
@endif
@endsection


