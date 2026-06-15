<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Document</title>
</head>
<body>
        <table style="justify-content: center; width: 100%;">
        <thead>
            <th>
            <img src="img/logo.png" style="width: 80px; height: 70px;"/>
            </th>
            <th>
            <h3 style="font-size: 17px;">INSTITUTO TECNOLÓGICO DE TUXTLA GUTIÉRREZ</h3>
            <h3 style="font-size: 17px;">DIVISIÓN DE ESTUDIOS PROFESIONALES</h3>
            <h3 style="font-size: 17px;">SOLICITUD DE RESIDENCIA PROFESIONAL</h3>
            </th>
        </thead>
        </table>
        

    <table class="marriba" style="width: 100%;">
            <tr>
                <th style="width: 20%;">Lugar: </th>
                <td style="width: 30%;" class="subrayado">Tuxtla Gutiérrez, Chiapas</th>
                <th style="width: 20%;">Fecha: </th>
                <td style="width: 30%;" class="subrayado" >{{ \Carbon\Carbon::now('America/Mexico_City')->format('d/m/Y') }}</td>
            </tr>
    </table>
    <table class="marriba" style="width: 100%;">
        <tr>
            <th style="width: 50%;">{{$jefe}}</th>
            <th style="width: 50%;">AT’N: C. {{$estudiante->carrera->coordinador->nombre}} {{$estudiante->carrera->coordinador->apellido_paterno}} {{$estudiante->carrera->coordinador->apellido_materno}}</th>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td class="centro" style="width: 50%;">Jefe de la Div. de Estudios Profesionales</td>
            <td class="centro" style="width: 50%;">Coord. de la Carrera de Ing. En {{$estudiante->carrera->nombre}}</td>
    </table>

        <table class="marriba" style="width: 100%;">
            <tr>
            <th class="cuadro" style="width: 25%;">NOMBRE DEL PROYECTO: </th>
            <th style="width: 5%;"></th>
            <td class="cuadro" style="width: 70%;">{{$estudiante->proyecto->nombre}}</td>
            </tr>
        </table>
        <div class="horizontal marriba">
            <p class="bold">OPCION ELEGIDA: 
            
                    <span style=" font-weight: normal; margin-left:10px;">Banco de Proyecto <input type="checkbox" {{ $estudiante->proyecto->origen === 'Banco de Proyectos' ? 'checked' : '' }}></span>
                    <span style=" font-weight: normal; margin-left:10px;">Propuesta propia <input type="checkbox" {{ $estudiante->proyecto->origen === 'Propuesta propia' ? 'checked' : '' }}></span>
                    <span style=" font-weight: normal; margin-left:10px;">Trabajador <input type="checkbox" {{ $estudiante->proyecto->origen === 'Trabajador' ? 'checked' : '' }}></span>
            </p>    
        </div>
        <div class="horizontal marriba">
            <p class="bold ">PERIODO PROYECTADO: 
                <span style=" font-weight: normal;"> {{$estudiante->proyecto->periodo->nombre}}</span>  
                <span style="margin-left:20px;">Número de Residentes: </span>
                <span style=" font-weight: normal;"> {{ $cantidadEstudiantes }}</span>
            </p> 
        </div>
        <div>
            <p class="bold marriba">Datos de la Empresa: </p>
            <div class="cuadro">
            <table class="tabla">
                <tr>
                    <th class="sin-bold" style="width: 10%; border: 1px solid black;">Nombre: </th>
                    <th class="sin-bold" style="width: 90%; border: 1px solid black;" >{{$estudiante->proyecto->empresa->nombre}}</th>
                </tr>
            </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro sin-bold" style="width: 20%;">Giro, Ramo o Sector: </th>
                        <th class="cuadro sin-bold" style="width: 50%;">{{$estudiante->proyecto->empresa->giro}} </th>
                        <th class="cuadro sin-bold" style="width: 40%;">R.F.C. </th>
                        <th class="cuadro sin-bold" style="width: 30%;">{{$estudiante->proyecto->empresa->rfc}} </th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro sin-bold" style="width: 15%;">Dirección: </th>
                        <th class="cuadro sin-bold nsemanas">Calle: </th>
                        <th class="cuadro sin-bold" style="width: 35%;">{{$estudiante->proyecto->empresa->direccion}} </th>
                        <th class="cuadro sin-bold nsemanas">Número: </th>
                        <th class="cuadro sin-bold" style="width: 30%;">{{$estudiante->proyecto->empresa->numero}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro sin-bold" style="width: 20%;">Código Postal: </th>
                        <th class="cuadro sin-bold" style="width: 30%;">{{$estudiante->proyecto->empresa->codigo_postal}}</th>
                        <th class="cuadro sin-bold" style="width: 20%;">Ciudad/Estado: </th>
                        <th class="cuadro sin-bold" style="width: 30%;">{{$estudiante->proyecto->empresa->ciudad}}, {{$estudiante->proyecto->empresa->estado}}  </th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro sin-bold nsemanas">Teléfono / Fax </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->proyecto->empresa->telefono}} </th>
                    <th class="cuadro sin-bold nsemanas">Email </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->proyecto->empresa->correo}} </th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro sin-bold nsemanas">Nombre del Titular de la empresa: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->proyecto->empresa->titular}}</th>
                    <th class="cuadro sin-bold nsemanas">Puesto: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->proyecto->empresa->puesto_titular}} </th>
                    </tr>
                </table>
                <table class="tabla">
                    //si el proyecto tiene asesor externo, se muestra la información del asesor, de lo contrario se deja en blanco
                    <tr>
                    <th class="cuadro sin-bold nsemanas">Nombre del Asesor Externo:  </th>
                    @if($estudiante->proyecto->externo){
                        <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->proyecto->externo->titulo}} {{$estudiante->proyecto->externo->nombre}} {{$estudiante->proyecto->externo->apellido_paterno}} {{$estudiante->proyecto->externo->apellido_materno}}</th>
                        <th class="cuadro sin-bold nsemanas">Puesto: </th>
                        <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->proyecto->externo->puesto}}</th> }    
                    @else{
                        <th class="cuadro sin-bold" style="width: 40%;"></th>
                        <th class="cuadro sin-bold nsemanas">Puesto: </th>
                        <th class="cuadro sin-bold" style="width: 40%;"></th>}
                    @endif
                     </tr>
                </table>
            </div>
            
        </div>
        <div>
        <p class="bold marriba">Datos del Residente: </p>
                <div class="cuadro">
                <table class="tabla">
                    <tr>
                    <th class="cuadro sin-bold nsemanas">Nombre: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->nombre}} {{$estudiante->apellido_paterno}} {{$estudiante->apellido_materno}}</th>
                    <th class="cuadro sin-bold nsemanas">Telefono: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->telefono}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro sin-bold nsemanas">Carrera: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->carrera->nombre}} </th>
                    <th class="cuadro sin-bold nsemanas">No. de Control: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->numero_de_control}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro sin-bold nsemanas">Direccion:  </th>
                        <th class="cuadro sin-bold" style="width: 90%;">{{$estudiante->direccion}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro sin-bold nsemanas">Email: </th>
                    <th class="cuadro sin-bold" style="width: 40%;">{{$estudiante->correo_electronico}}</th>
                    </tr>
                </table>
                    <table class="tabla">
                        <tr>
                        <th class="cuadro sin-bold">Para Seguridad <br>Social Acudir: </th>
                            <th class="cuadro sin-bold" style="width: 20%;">IMSS <br><input style="padding-top: 20px;" type="checkbox" {{ $estudiante->institucion_seguridad_social === 'IMSS' ? 'checked' : '' }}></th>
                            <th class="cuadro sin-bold" style="width: 20%;">ISSSTE <br><input style="padding-top: 20px;" type="checkbox" {{ $estudiante->institucion_seguridad_social === 'ISSSTE' ? 'checked' : '' }}></th>
                            <th class="cuadro sin-bold" style="width: 20%;"> OTROS <br><input style="padding-top: 20px;" type="checkbox" {{ $estudiante->institucion_seguridad_social === 'OTROS' ? 'checked' : '' }}></th>
                            <th class="cuadro sin-bold" style="width: 20%;">No. :  <p>{{$estudiante->numero_de_seguridad_social}}</p> </th>
                        </tr>
                    </table> 
                </div>
        </div>

        <div class="centrar">
            <p>______________________</p>
            <p>Firma del alumno</p>
        </div>

   
</body>
</html>