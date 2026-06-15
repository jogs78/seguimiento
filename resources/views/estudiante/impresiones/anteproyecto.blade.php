<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Document</title>
</head>
<body>
        <table style="justify-content: center; width: 100%; margin-top: 0;">
        <thead>
            <th>
            <img src="img/logo.png" style="width: 90px; height: 80px;"/>
            </th>
            <th>
            <h3>INSTITUTO TECNOLÓGICO DE TUXTLA GUTIÉRREZ</h3>
            <h4>ANTEPROYECTO DE RESIDENCIA PROFESIONAL</h4>
            </th>
        </thead>
        </table>
        <p class="centrar" style="margin-top: 0px; padding-top: 0px;">____________________________________________________________________________________________</p>
        
        <table style="width: 100%;">
            <tr>
                <th style="width: 30%;"></th>
                <th style="text-align: right;  font-weight: bold; width: 50%;">NUM. DEL REGISTRO DEL PROYECTO:</th>
                <th style="text-align: right; width: 20%;">
                    {{ $estudiante->proyecto->num_registro ? $estudiante->proyecto->num_registro : 'PROYECTO NUEVO' }}
                </th>
            </tr>
        </table>
        <p class="inciso">a) Nombre del proyecto: </p>
        <p>{{$estudiante->proyecto->nombre}}</p>

        <p class="inciso">b) Objetivo del proyecto: </p>
        <p>{{$estudiante->proyecto->objetivo_general}}</p>
        
        <p class="inciso">c) Justificación: </p>
        <p>{{$estudiante->proyecto->justificacion}}</p>

        <p class="inciso">d) Cronograma preliminar de actividades: </p>



        <div class="cuadro">
            <table class="tabla" border="1">
                <thead>
                    <tr>
                        <th style="width: 25%;">Actividad</th>
                        @for ($i = 1; $i <= 16; $i++)
                            <th style="width: 4.6875%;">{{ $i }}</th>  
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Crear un array para marcar qué semanas están ocupadas
                        $semanasOcupadas = [];
                        
                        foreach ($estudiante->proyecto->actividades as $actividad) {
                            foreach ($actividad->cronogramas as $cronograma) {
                                $inicio = $cronograma->semana_inicio;
                                $fin = min($cronograma->semana_fin, 16);
                                
                                for ($i = $inicio; $i <= $fin; $i++) {
                                    $semanasOcupadas[$actividad->id][$i] = true;
                                }
                            }
                        }
                    @endphp
                    
                    @foreach ($estudiante->proyecto->actividades as $actividad)
                        <tr>
                            <td style="text-align: left; padding: 8px;">{{ $actividad->name ?? $actividad->nombre }}</td>
                            
                            @for ($i = 1; $i <= 16; $i++)
                                @php
                                    $tieneActividad = isset($semanasOcupadas[$actividad->id][$i]);
                                @endphp
                                <td style="background-color: {{ $tieneActividad ? '#d4edda' : 'white' }}; text-align: center;">
                                    @if ($tieneActividad)
                                        X
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                    
                    @if ($estudiante->proyecto->actividades->isEmpty())
                        <tr>
                            <td colspan="17" style="text-align: center;">Sin actividades</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <p class="inciso">e) Descripción detallada de las actividades</p>
        <p class="bold" style="margin-left: 10px;">Definir requisitos:</p>
        <ul>
            @foreach ($estudiante->proyecto->actividades as $actividad)
            <li style="margin-left: 20px; margin-right: 20px;">{{$actividad->nombre}}: Durante {{$actividad->semanas}} semanas se realizara: {{$actividad->descripcion}}</li>
            @endforeach
        </ul>

        <table class="tabla" style="margin-top: 40px;">
            <tr>
                <th style="width: 50%;"></th>
                <th style="width: 50%;" class="bold">Vto. Bno.</th>
            </tr>
        </table>
        <table class="tabla" style="margin-top: 60px;">
            <tr>
                <th style="width: 50%;">{{$estudiante->nombre}} {{$estudiante->apellido_paterno}} {{$estudiante->apellido_materno}}</th>
                <th style="width: 50%;">{{$estudiante->proyecto->asesor->nombre}} {{$estudiante->proyecto->asesor->apellido_paterno}} {{$estudiante->proyecto->asesor->apellido_materno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 50%;">_____________________________</th>
                <th style="width: 50%;">_____________________________</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 50%;">Nombre y firma del Residente</th>
                <th style="width: 50%;">Nombre del Docente Asesor </th>
            </tr>
        </table>
        <table class="tabla" style="margin-top: 40px;">
            <tr>
                <th style="width: 50%;"></th>
                <th style="width: 50%;">Fecha: </th>
            </tr>
        </table>

</body>
</html>