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
.caja{ border: 2px solid rgb(40, 95, 139); border-radius: 10px; padding-bottom: 20px;}
     .sugerencias-box {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        z-index: 1000;
        width: 250px;
        max-height: 200px;
        overflow-y: auto;
        right: 0; /* Alineado a la derecha del input */
        top: 100%; /* Justo debajo del input */
    }

    .sugerencia-item {
        padding: 5px 10px;
        cursor: pointer;
    }

    .sugerencia-item:hover {
        background-color: #f0f0f0;
    }
</style>
@section('encabezado')
    
@endsection
@section('contenido')
<!--@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif-->



<div style="margin-top:20px;">
<div class="horizontal"><p class="subtitulo">Proyecto</p></div><br>

    <div style="width: 99%;">
    <div class="horizontal caja" style="margin-bottom:30px;">
        <form action="{{ route('proyectos.unirse') }}" method="POST">
            @csrf
            <div class="centro" style="margin-bottom:24px">
                <label class="parrafo">Unirse a un Proyecto ya registrado</label>
            </div>
           <div>
               <label for="id" class="parrafo">ID del Proyecto</label>
               <input type="number" name="id" class="llenar" value="{{ old('id') }}" required>

               <div style="position: relative; display: inline-block;">
                    <label for="nombre" class="parrafo" style="margin-left:24px">Nombre del Proyecto</label>
                    <input type="text" name="nombre" id="nombreProyecto" class="llenar" autocomplete="off" required>
                    <div id="sugerencias" class="sugerencias-box"></div>
                </div>
            </div>

            <div class="centro">
                <button type="button" id="btnUnirse" class="boton">Unirse al Proyecto</button>
            </div>
        </form>
    </div>
    </div>

    <div class="centro">
    
    <form action="{{route("proyectos.store")}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        <div class="centro" style="margin-bottom:24px">
            <label class="parrafo">Registrar un proyecto</label>
        </div>

        <label for='nombre' class="parrafo">Nombre del proyecto</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombre' value="{{old('nombre')}}" class="llenar"><br>

        <label for='objetivo_general' class="parrafo">Objetivo General</label>
        {{$errors->first("objetivo_general")}}
        <input type='text' name='objetivo_general' id='objetivo_general' value="{{old('objetivo_general')}}" class="llenar"><br>

        <label for='lugar' class="parrafo">Lugar</label>
        {{$errors->first("lugar")}}
        <input type='text' name='lugar' id='lugar' value="{{old('lugar')}}" class="llenar"><br>

        <label for='informacion' class="parrafo">Informacion</label>
        {{$errors->first("informacion")}}
        <input type='text' name='informacion' id='informacion' value="{{old('informacion')}}" class="llenar">

        <div style="margin-top:15px; margin-bottom:8px;">
        <label for='justificacion' class="parrafo" style="display: inline-block; vertical-align: top;">Justificacion </label>
        {{$errors->first("justificacion")}}
        <textarea name='justificacion' id='justificacion' rows="4">{{old('justificacion')}}</textarea>
        <br>
        </div>

        <label for='asesor_id' class="parrafo">Propón a tu Asesor Interno</label>
        <select name="asesor_id" id="asesor_id">
            @foreach ($asesores as $asesor)
                <option value="{{$asesor->id}}">{{$asesor->nombre}} {{$asesor->apellido_paterno}} {{$asesor->apellido_materno}}</option>
            @endforeach
        </select>
        <br>
        <br>
        <label for='correo_ae' class="parrafo">Correo del Asesor Externo</label>
        <input type='text' name='correo_ae' id='correo_ae' value="{{old('correo_ae')}}" class="llenar"><br>
        <br>
        <label for='titulo_ae' class="parrafo">Titulo del Asesor Externo (abreviatura)</label>
        <input type='text' name='titulo_ae' id='titulo_ae' value="{{old('titulo_ae')}}" class="llenar"><br>
        <br>
        <label for='nombre_ae' class="parrafo">Nombre del Asesor Externo</label>
        <input type='text' name='nombre_ae' id='nombre_ae' value="{{old('nombre_ae')}}" class="llenar"><br>
        <br>
        <label for='apellido_paterno_ae' class="parrafo">Apellido paterno del Asesor Externo</label>
        <input type='text' name='apellido_paterno_ae' id='apellido_paterno_ae' value="{{old('apellido_paterno_ae')}}" class="llenar"><br>
        <br>
        <label for='apellido_materno_ae' class="parrafo">Apellido materno del Asesor Externo</label>
        <input type='text' name='apellido_materno_ae' id='apellido_materno_ae' value="{{old('apellido_materno_ae')}}" class="llenar"><br>
        <br>
        <label for='puesto_ae' class="parrafo">Puesto del Asesor Externo</label>
        <input type='text' name='puesto_ae' id='puesto_ae' value="{{old('puesto_ae')}}" class="llenar"><br>

        
        </select>
        <br>

        

        <label for="empresa_id" class="parrafo">Empresa</label>
        <select name="empresa_id" id="empresa_id" onchange="mostrarDatosEmpresa()">
            @foreach ($empresas as $empresa)
                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
            @endforeach
            <option value="-1">LA EMPRESA NO ESTA DADA DE ALTA</option>
        </select>
        <br><br>
        <div id="datos_empresa" style="display: none;">
            <!-- Contenido adicional para datos de la empresa -->
            
            <label for='nombre_e' class="parrafo">Nombre de la Empresa</label>
            {{$errors->first("nombre_e")}}
            <input type='text' name='nombre_e' id='nombre_e' value="{{old('nombre_e')}}" class="llenar"><br><br>
            
            <label class="parrafo">Escoge el Giro, Ramo o Sector</label> <br>
            {{$errors->first("giro")}}    
            <div style="margin-top:5px;">

            <input type='radio' name='giro' id='industrial' value='industrial' >
            <label for="industrial" class="giro">Industrial</label>

            <input type='radio' name='giro' id='servicios' value='servicios'style="margin-left:12px;">
            <label for="servicios" class="giro2">Servicios</label> <br>

            <input type='radio' name='giro' id='publico' value='publico' >
            <label for="publico" class="giro">Publico</label>

            <input type='radio' name='giro' id='privado' value='privado' style="margin-left:12px;" checked>
            <label for="privado" class="giro">Privado</label>

            <input type='radio' name='giro' id='otro' value='otro' style="margin-left:12px;">
            <label for="otro" class="giro5">Otro</label><br>
            </div>
            
            <label for='rfc'  class="parrafo">RFC</label>
            {{$errors->first("rfc")}}
            <input type='text' name='rfc' id='rfc' value="{{old('rfc')}}" placeholder="_______" class="llenar"><br><br>
    
            <label class="parrafo">Direccion</label><br>
            <label for="direccion">Calle:</label>
            {{$errors->first("direccion")}}
            <input type='text' name='direccion' id='direccion' value="{{old('direccion')}}" class="llenar">
            <label for="numero">Numero:</label>
            {{$errors->first("numero")}}
            <input type='text' name='numero' id='numero' value="{{old('numero')}}" class="llenar" placeholder="Ej: 123 o S/N">
            <label for="codigo_postal">Codigo Postal:</label>
            {{$errors->first("codigo_postal")}}
            <input type='text' name='codigo_postal' id='codigo_postal' value="{{old('codigo_postal')}}" class="llenar"><br>

            <label for="estado">Estado:</label>
            {{$errors->first("estado")}}
            <input type='text' name='estado' id='estado' value="{{old('estado')}}" class="llenar">
            <label for="ciudad">Ciudad:</label>
            {{$errors->first("ciudad")}}
            <input type='text' name='ciudad' id='ciudad' value="{{old('ciudad')}}" class="llenar"><br><br>

            

            <label for='telefono'  class="parrafo">Telefono</label>
            {{$errors->first("telefono")}}
            <input type='tel' name='telefono' id='telefono' value="{{old('telefono')}}" class="llenar"><br>
    
            <label for='correo'  class="parrafo">Correo Electronico</label>
            {{$errors->first("correo")}}
            <input type='email' name='correo' id='correo' value="{{old('correo')}}" class="llenar"><br>
    
            <label for='titular'  class="parrafo">Nombre Completo del Titular de la empresa</label>
            {{$errors->first("titular")}}
            <input type='text' name='titular' id='titular' value="{{old('titular')}}" class="llenar"><br>
    
            <label for='puesto_titular'  class="parrafo">Puesto del Titular</label>
            {{$errors->first("puesto_titular")}}
            <input type='text' name='puesto_titular' id='puesto_titular' value="{{old('puesto_titular')}}" class="llenar"><br>
    
            <label for='informacion_e'  class="parrafo">Informacion adicional de la empresa: </label>
            {{$errors->first("informacion")}}
            <input type='text' name='informacion_e' id='informacion_e' value="{{old('informacion_e')}}" class="llenar"><br>


        </div>
        <script>
            function mostrarDatosEmpresa() {
                const selectElement = document.getElementById('empresa_id');
                if (selectElement.value === '-1') {
                    selectElement.style.display = 'none';
                    document.getElementById('datos_empresa').style.display = 'block';
                }
            }
        </script>
        <br><br>

        <label class="parrafo" >Periodo:</label>
        <input type="hidden" name="periodo_id" value="{{$periodo->id}}" class="llenar">
        {{$periodo->nombre}}
        <br>
        
       
        <div class="centro"><input type='submit' class="boton"></div>
    </form>
    </div>
</div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('nombreProyecto');
        const contenedor = document.getElementById('sugerencias');
    
        input.addEventListener('input', function () {
            const query = this.value;
        
            if (query.length < 2) {
                contenedor.innerHTML = '';
                return;
            }
        
            fetch(`/buscar-proyectos?q=${query}`)
                .then(res => res.json())
                .then(data => {
                    contenedor.innerHTML = '';
                
                    data.forEach(nombre => {
                        const div = document.createElement('div');
                        div.classList.add('sugerencia-item');
                        div.textContent = nombre;
                    
                        div.addEventListener('click', function () {
                            input.value = nombre;
                            contenedor.innerHTML = '';
                        });
                    
                        contenedor.appendChild(div);
                    });
                });
        });
    
        // Ocultar sugerencias al perder foco
        input.addEventListener('blur', () => {
            setTimeout(() => contenedor.innerHTML = '', 100); // espera para permitir click
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnUnirse');
    const form = btn.closest('form');

    btn.addEventListener('click', function (e) {
        e.preventDefault(); // Prevenir envío automático

        const id = form.querySelector('input[name="id"]').value.trim();
        const nombre = form.querySelector('input[name="nombre"]').value.trim();

        if (!id || !nombre) {
            Swal.fire({
                icon: 'error',
                title: 'Error al buscar este proyecto',
            });
            return;
        }

        Swal.fire({
            title: '¿Deseas unirte a este proyecto?',
            html: `Proyecto: <strong>${nombre}</strong><br>ID: <strong>${id}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Enviar formulario si el usuario acepta
            }
        });
    });
});
</script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif

    @if($errors->has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ $errors->first('error') }}',
            confirmButtonText: 'Aceptar'
        });
    @endif
</script>
@endsection