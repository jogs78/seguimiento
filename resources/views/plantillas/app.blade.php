<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        .cuerpo{
            grid-template-columns: 20% 80% ;
            display: grid;
            height: 2000px;
        }        
        .menu {
            border: 3px solid rgb(19, 46, 68); border-radius: 10px;
        }
        .contenido {
             margin-left: 10px;
        }
        .cmenor{background-color: rgb(40, 95, 139);}
        .cmayor{background-color: rgb(19, 46, 68);}
        .linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 95%;} 
        .hderecho {display: flex; justify-content: right; }
        .horizontal {display: flex; justify-content: center; width: 100%;}
        .boton{background-color: rgb(25, 118, 210); padding: 15px; border-radius: 5px; color: white; text-decoration: none; cursor: pointer; }
        .boton:hover{background-color: rgb(74, 139, 204);}
        .parrafo{font-size: 25px; margin-top: 10px;}
        .opcion{ background-color: rgb(234, 245, 255); margin-left: 10px; margin-right:10px; padding: 5px;border: 1px solid rgb(19, 46, 68); border-radius: 5px;}
        .opcion:hover{background-color: rgb(255, 255, 255);}
        *{margin: 0; padding: 0;}
    </style>       
</head>
<body>
@php
    $types = [
        'App\Models\Estudiante' => 'Estudiante',
        'App\Models\Asesor' => 'Asesor Interno',
        'App\Models\Externo' => 'Asesor Externo',
        'App\Models\Coordinador' => 'Coordinador',
    ];
@endphp
    @auth
        <div style="width: 100%; margin-bottom:10px;">
            <div class="hderecho" style="width: 100%; height: 20px; margin-bottom:10px; ">
                <div class="cmenor" style="width: 20%;"></div>
                <div class="cmayor" style="width: 80%; "></div>
            </div>
            <div class="hderecho" style="width: 100%; ">
                <div style="width:30%;  margin-top: 15px;">
                    <!-- @yield('encabezado')-->
                    <a class="boton" style="margin-right: 10px; margin-left: 10px; " href="{{route('salida')}}">Salir</a>
                    <a class="boton" href="{{route('Cambiar_Contraseña')}}">Cambiar la Contraseña</a>
                </div>
                <div style="width:70%; margin-right: 10px;" class="hderecho">
                    <p style="margin-right: 10px; margin-bottom: 10px;" class="parrafo">Bienvenido:</p><p class="parrafo">{{Auth::user()->usa->nombre}} {{Auth::user()->usa->apellido_paterno}}  {{Auth::user()->usa->apellido_materno}} ({{ $types[Auth::user()->usa_type] ?? Auth::user()->usa_type }})</p>
                    <!--<div class="parrafo" style="text-align: right; margin-right: 10px;"><p>Bienvenido:</p>{{Auth::user()->usa->nombre}}</div>-->
                </div>
            </div>
                <div class="horizontal">
                    <div style="margin-top: 10px;" class="linea"></div>
                </div>
            </div>

        <div class="cuerpo">
            <div class="menu">
                @switch(Auth::user()->usa_type)
                    @case("App\Models\Coordinador")
                        <div>
                        
                            
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('coordinadores.tabla')}}">TABLA DE PROYECTOS</a></div>
                           <!-- <li class="opcion"><a  style="text-decoration: none;" href="{{route('coordinadores.asignarAsesor1')}}">ASIGANR ASESORES</a></li>-->
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('estudiantes.index')}}">LISTA DE ESTUDIANTES</a></div>
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('periodos.index')}}">GESTIONAR PERIODO</a></div>
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('asesores.index')}}">ASESORES INTERNOS</a></div><!--(APARTE DE TENER LA OPCION DE AGREGAR, TENDRA LAS OPCIONES DE EXPORTAR e IMPORTAR)-->
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('externos.index')}}">ASESORES EXTERNOS</a></div><!--(APARTE DE TENER LA OPCION DE AGREGAR, TENDRA LAS OPCIONES DE EXPORTAR e IMPORTAR)-->
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('configuraciones.index')}}">CONFIGURACIONES</a></div><!--FALTA ESTE CRUD-->
                            

                        
                        </div>
                    @break

                    @case("App\Models\Estudiante")
                    <div>
                            
                            <div style="margin-top: 25px;"><a style="text-decoration: none;" class="opcion" href="{{route('estudiantes.edit',Auth::user()->usa_id)}}">ACTULIALIZA TUS DATOS</a></div>
                            <div style="margin-top: 25px;"><a style="text-decoration: none;" class="opcion" href="{{route('proyectos.create')}}">PROYECTO</a></div>
                            <div style="margin-top: 25px;"><a style="text-decoration: none;" class="opcion" href="{{route('estudiante.impresiones.solicitud')}}">IMPRIMIR SOLICITUD</a></div>
                            <div style="margin-top: 25px;"><a style="text-decoration: none;" class="opcion" href="{{route('estudiante.impresiones.anteproyecto')}}">IMPRIMIR ANTEPROYECTO</a></div>
                            <!--<li><a style="text-decoration: none;" href="#">CAPTURAS LAS ACTIVIDADES DE TU PROYECTO</a></li>-->
                            <div style="margin-top: 25px;"><a style="text-decoration: none;" class="opcion" href="{{route('estudiante.promedio')}}">VERIFICA TUS SEGUIMIENTOS</a></div>
                    </div>
                    @break
                        
                    @case("App\Models\Asesor")
                    <div>
                            
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('asesor.listar-proyectos')}}">PROYECTOS ASIGNADOS</a></div>
                            <!-- <li class="opcion"><a  style="text-decoration: none;" href="#">CALIFICAR PROYECTO</a></li> -->
                        </div>
                    @break

                    @case("App\Models\Externo")
                    <div>
                            
                            <div style="margin-top: 25px;"><a  style="text-decoration: none;" class="opcion" href="{{route('externo.lista-de-proyectos')}}">PROYECTOS ASIGNADOS</a></div>
                            <!-- <li class="opcion"><a  style="text-decoration: none;" href="#">CALIFICAR PROYECTO</a></li> -->
                        </div>
                    @break

                    @default
                        
                @endswitch
                <!--
                    aqui las opciones son segun el tipo
                -->

            </div>
            <div class="contenido">
                @yield('contenido')
            </div>
        </div>
        
    @endauth
</body>
</html>