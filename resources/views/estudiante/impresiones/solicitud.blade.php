<!DOCTYPE html>
<html lang="en">
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
            <img src="img/logo.png" style="width: 90px; height: 80px;"/>
            </th>
            <th>
            <h3>INSTITUTO TECNOLÓGICO DE TUXTLA GUTIÉRREZ</h3>
            <h3>DIVISIÓN DE ESTUDIOS PROFESIONALES</h3>
            <h3>SOLICITUD DE RESIDENCIA PROFESIONAL</h3>
            </th>
        </thead>
        </table>
        

    <table class="marriba" style="width: 100%;">
            <tr>
                <th style="width: 20%;">Lugar: </th>
                <td style="width: 30%;" class="subrayado">Tuxtla Gutiérrez, Chiapas</th><!--Cambiar??-->
                <th style="width: 20%;">Fecha: </th>
                <td style="width: 30%;"  >________________________</td>
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
            <p class="bold cuadro marginr">OPCION ELEGIDA: </p>
            <p class="marginr">Banco de Proyecto <input type="checkbox" {{ $estudiante->proyecto->origen === 'Banco de Proyectos' ? 'checked' : '' }}></p>
            <p class="marginr">Propuesta propia <input type="checkbox" {{ $estudiante->proyecto->origen === 'Propuesta propia' ? 'checked' : '' }}></p>
            <p class="marginr">Trabajador <input type="checkbox" {{ $estudiante->proyecto->origen === 'Trabajador' ? 'checked' : '' }}></p> 
        </div>
        <div class="horizontal marriba">
            <p class="bold cuadro marginr">PERIODO PROYECTADO: </p>
            <p class="marginr">{{$estudiante->proyecto->periodo->nombre}}</p>
            <p>Número de Residentes: </p>
            <p>1</p><!--Cambiar-->
        </div>
        <div>
            <p class="bold marriba">Datos de la Empresa: </p>
            <div class="cuadro">
            <table class="tabla">
                <tr>
                    <th style="width: 10%; border: 1px solid black;">Nombre: </th>
                    <th style="width: 90%; border: 1px solid black;" >{{$estudiante->proyecto->empresa->nombre}}</th>
                </tr>
            </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro" style="width: 20%;">Giro, Ramo o Sector: </th>
                        <th class="cuadro" style="width: 50%;">{{$estudiante->proyecto->empresa->giro}} </th>
                        <th class="cuadro" style="width: 40%;">R.F.C. </th>
                        <th class="cuadro" style="width: 30%;">{{$estudiante->proyecto->empresa->rfc}} </th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro" style="width: 15%;">Dirección: </th>
                        <th class="cuadro nsemanas">Calle: </th>
                        <th class="cuadro" style="width: 35%;">{{$estudiante->proyecto->empresa->direccion}} </th>
                        <th class="cuadro nsemanas">Número: </th>
                        <th class="cuadro" style="width: 30%;">S/N </th> <!-- Cambiar -->
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro" style="width: 20%;">Código Postal: </th>
                        <th class="cuadro" style="width: 30%;">29050</th> <!-- Cambiar -->
                        <th class="cuadro" style="width: 20%;">Ciudad/Estado: </th>
                        <th class="cuadro" style="width: 30%;">Tuxtla Gutiérrez, Chiapas  </th> <!-- Cambiar -->
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro nsemanas">Teléfono / Fax </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->proyecto->empresa->telefono}} </th>
                    <th class="cuadro nsemanas">Email </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->proyecto->empresa->correo}} </th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro nsemanas">Nombre del Titular de la empresa: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->proyecto->empresa->titular}}</th>
                    <th class="cuadro nsemanas">Puesto: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->proyecto->empresa->puesto_titular}} </th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro nsemanas">Nombre del Asesor Externo:  </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->proyecto->externo->titulo}} {{$estudiante->proyecto->externo->nombre}} {{$estudiante->proyecto->externo->apellido_paterno}} {{$estudiante->proyecto->externo->apellido_materno}}</th>
                    <th class="cuadro nsemanas">Puesto: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->proyecto->externo->puesto}}</th>
                    </tr>
                </table>
            </div>
            
        </div>
        <div>
        <p class="bold marriba">Datos del Residente: </p>
                <div class="cuadro">
                <table class="tabla">
                    <tr>
                    <th class="cuadro nsemanas">Nombre: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->nombre}} {{$estudiante->apellido_paterno}} {{$estudiante->apellido_materno}}</th>
                    <th class="cuadro nsemanas">Telefono: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->telefono}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro nsemanas">Carrera: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->carrera->nombre}} </th>
                    <th class="cuadro nsemanas">No. de Control: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->numero_de_control}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                        <th class="cuadro nsemanas">Direccion:  </th>
                        <th class="cuadro" style="width: 90%;">{{$estudiante->direccion}}</th>
                    </tr>
                </table>
                <table class="tabla">
                    <tr>
                    <th class="cuadro nsemanas">Email: </th>
                    <th class="cuadro" style="width: 40%;">{{$estudiante->correo_electronico}}</th>
                    </tr>
                </table>
                    <table class="tabla">
                        <tr>
                        <th class="cuadro nsemanas">Para Seguridad <br>Social Acudir: </th>
                            <th class="cuadro" style="width: 25%;">IMSS <br><input style="padding-top: 20px;" type="checkbox" {{ $estudiante->institucion_seguridad_social === 'IMSS' ? 'checked' : '' }}></th>
                            <th class="cuadro" style="width: 25%;">ISSSTE <br><input style="padding-top: 20px;" type="checkbox" {{ $estudiante->institucion_seguridad_social === 'ISSSTE' ? 'checked' : '' }}></th>
                            <th class="cuadro" style="width: 25%;"> OTROS <br><input style="padding-top: 20px;" type="checkbox" {{ $estudiante->institucion_seguridad_social === 'OTROS' ? 'checked' : '' }}></th>
                            <th class="cuadro" style="width: 25%;">No. :  <p>{{$estudiante->numero_de_seguridad_social}}</p> </th>
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