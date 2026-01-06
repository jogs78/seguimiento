@extends('plantillas.app')
<style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.centro{display: flex; justify-content: center;}
.titulo{text-align:center; font-size: 50px; font-weight: bold;}
.subtitulo{text-align:center; font-size: 45px; font-weight: bold;}
.parrafo{font-size: 30px; margin-top:30px;}
.boton{background-color: rgb(25, 118, 210); padding: 15px; border-radius: 5px; color: white; border: none; cursor: pointer; margin-top:30px;}
.boton:hover{background-color: rgb(74, 139, 204);}
.llenar{height: 25px; font-size: 18px;  margin-top: 30px;}
.contenedor-formulario {
    display: flex;
    justify-content: center;   /* centra horizontal */
    align-items: center;       /* centra vertical */
}
*{margin: 0; padding: 0;}
</style>
@section('encabezado')
    
@endsection
@section('contenido')
@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="horizontal"><p class="subtitulo">Cambiar contraseña</p></div>
    <div class="contenedor-formulario">
        <form action="{{ route('usuario.cambiar-password') }}" method="POST">
            @csrf

            <label for="password_actual" class="parrafo">Contraseña actual</label>
            <input type="text" name="password_actual" id="password_actual" class="llenar" required>
            <br>

            <label for="password" class="parrafo">Contraseña nueva</label>
            <input type="text" name="password" id="password" class="llenar" required>
            <br>

            <label for="password_confirmation" class="parrafo">Confirmar contraseña</label>
            <input type="text" name="password_confirmation" id="password_confirmation" class="llenar" required>
            <br>

            <button type="submit" class="boton">Cambiar contraseña</button>
        </form>
    </div>
@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            confirmButtonText: 'Ok'
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
            confirmButtonText: 'Ok'
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
            title: 'Error al cambiar la contraseña',
            confirmButtonText: 'Ok'
        });
    </script>
@endif
@endsection
