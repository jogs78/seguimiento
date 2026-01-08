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
.botonBuscar{background-color: rgb(25, 118, 210); color: white; border: none; cursor: pointer; padding-top:10px;padding-bottom:10px; padding-left:5px;  padding-right:5px; border-radius: 5px;}
.botonBuscar:hover{background-color: rgb(74, 139, 204);}
.botonEditar{background-color: rgb(25, 118, 210); color: white; cursor: pointer; text-decoration: none; padding: 3px;  border-radius: 3px; border: none;}
.botonEditar:hover{background-color: rgb(74, 139, 204);}
.botonBorrar{background-color: rgb(210, 25, 25); color: white; cursor: pointer; text-decoration: none; padding: 3px;  border-radius: 3px; border: none;}
.botonBorrar:hover{background-color: rgb(204, 74, 74);}
.llenar{ font-size: 18px;}
table{border: 2px solid rgb(19, 46, 68); border-collapse: collapse; margin-top:20px; }
th{border: 1px solid rgb(40, 95, 139);padding: 8px; }
.thcontenido{font-weight: normal;}
.thfondo{background-color: rgb(204, 216, 228);}
.bodydiv{margin-left: 20px; margin-right: 20px;}
</style>
@section('encabezado')
    
@endsection
@section('contenido')
<div style="display: flex; justify-content: flex-end;margin-right: 35px;">
    <form action="{{ route('externos.index') }}" method="GET" id="formBuscar" style="position: relative;">
        <input class="llenar" type="text" id="buscar" name="buscar" placeholder="Buscar por nombre" autocomplete="off" value="{{ request('buscar') }}">
        <button class="botonBuscar" type="submit">Buscar</button>
        <div id="sugerencias" style="background:white; border:1px solid #ccc; width:220px;"></div>
    </form>
</div>
<div class="horizontal" style="margin-top:20px;"><p class="subtitulo">Asesores Externos Registrados</p></div>
    <!--@php
    $todos = $todos ?? [];
    @endphp-->
    <div style="margin-bottom: 40px;" class="centro">
    <table border="1">
        <thead>
            <th class="thfondo">ID</th>
            <th class="thfondo">NOMBRE</th>
            <th class="thfondo">APELLIDOS</th>
            <th class="thfondo">ACCIONES</th>
            <th class="thfondo">USUARIO</th>
        </thead>
        <tbody>
        @foreach ($todos as $externo)
        <tr>
            <td style="padding:5px;">{{$externo->id}}</td>
            <td style="padding:5px;">{{$externo->titulo}} {{$externo->nombre}}</td>
            <td style="padding:5px;">{{$externo->apellido_paterno}} {{$externo->apellido_materno}}</td>
            <td style="padding:8px;">
                <a href="{{route("externos.edit",$externo->id)}}" class="botonEditar">Editar</a>
                @if (empty($externo->proyecto->id))
                <form action="{{route("externos.destroy",$externo->id)}}" method="post" onsubmit="return confirmarEliminacion()">
                @method('DELETE')
                @csrf
                <input type="submit" value="Borrar" class="botonBorrar" style="margin-top:5px;">

                </form>
                @else
                Asesor asigando al proyecto: <br> {{$externo->proyecto->nombre}}
                 @endif
            </td>

            </td>
            <td style="padding:5px;">
                @if ($externo->usuario->nombre_usuario == 'Sin cuenta')
                    <form action="{{route('externos.crearcuenta',$externo->id)}}" method="post">
                        @method('PUT')
                        @csrf
                        <input type="submit" value="Crear Cuenta" class="botonEditar">
                    </form>
                @else
                    {{$externo->usuario->nombre_usuario}}                
                @endif
            </td>
        </tr>

        @endforeach
        </tbody>
    </table>
    </div>
    <div class="horizontal">

    <a href="{{route('imprimir-externos.excel')}}" class="boton">Descargar lista</a>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("buscar");
        const sugerenciasDiv = document.getElementById("sugerencias");

        input.addEventListener("input", function() {
            const valor = this.value;

            if (valor.length < 2) {
                sugerenciasDiv.innerHTML = '';
                return;
            }

            fetch(`/externos/buscar-externo?term=${encodeURIComponent(valor)}`)
                .then(res => res.json())
                .then(data => {
                    sugerenciasDiv.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement("div");
                        div.textContent = item.value;
                        div.style.padding = "5px";
                        div.style.cursor = "pointer";
                        div.addEventListener("click", function() {
                            input.value = item.value;
                            sugerenciasDiv.innerHTML = '';
                        });
                        sugerenciasDiv.appendChild(div);
                    });
                });
        });

        // Ocultar sugerencias al hacer clic fuera
        document.addEventListener("click", function(e) {
            if (!sugerenciasDiv.contains(e.target) && e.target !== input) {
                sugerenciasDiv.innerHTML = '';
            }
        });
    });
    function confirmarEliminacion() {
    return confirm("⚠️ Al eliminar este Asesor Externo ya no se podrá restaurar.\n¿Seguro que deseas eliminar al Asesor Externo?");
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