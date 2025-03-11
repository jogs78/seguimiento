<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Document</title>
    <style>
        *{font-size: 12px;}
        p {
            line-height: 0.5; /* Puedes ajustar este valor según lo que necesites */
        }
    </style>
</head>
<body>
    <table style="justify-content: center; width: 100%;">
        <thead>
            <th>
                <img src="img/logo.png" style="width: 40px; height: 35px;"/>
            </th>
            <th>
                <p>FORMATO DE EVALUACIÓN Y SEGUIMIENTO DE RESIDENCIA PROFESIONAL</p>
                <p>Guía de llenado de parciales</p>
            </th>
        </thead>
    </table>
    <table>
        <tr>
            <th>Nombre del Residente:</th> <th class="dato">{{$estudiante->nombre}}</th><th class="dato">{{$estudiante->apellido_paterno}}</th><th class="dato"> {{$estudiante->apellido_materno}}</th>
            <th style="padding-left: 50px;">N° de Control:</th> <th class="dato">{{$estudiante->numero_de_control}}</th>
        </tr>
    </table>
    <table>
        <tr>
            <th style=" width: 22%;">Nombre del Proyecto: </th> <th class="dato">{{$estudiante->proyecto->nombre}}</th>
        </tr>
    </table>
    <table>
        <tr>
            <th>Programa Educativo:  </th> <th class="dato">{{$estudiante->carrera->nombre}}</th>
        </tr>
    </table>
    <table>
        <tr>
            <th>Periodo de realización de la Residencia Profesional: </th> <th class="dato">{{$estudiante->proyecto->periodo->nombre}}</th>
        </tr>
    </table>
    <table>
        <tr>
            <th>Calificación Parcial (Promedio de ambas evaluaciones):</th> <th class="dato">{{ ($ultimo->promedio_interno + $ultimo->promedio_externo  )/2 }}</th>
        </tr>
    </table>

    <p>Evaluación por el Asesor Externo:</p>
    <div class="cuadro">
        <table class="tabla">
            <tr>
                <th style="width: 100%;" class="cuadro">En qué medida el residente cumple con lo siguiente:</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro">Criterios a Evaluar</th>
                <th style="width: 15%;" class="cuadro">Valor</th>
                <th style="width: 15%;" class="cuadro">Evaluación</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Portada</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->portada_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Agradecimientos</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->agradecimientos_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Resumen</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->resumen_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Índice</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->indice_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Introducción</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->introduccion_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Problemas a resolver(priorizándolos)</th>
                <th style="width: 15%;" class="cuadro dato">5</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->problemas_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Objetivos</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->objetivos_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Justificación</th>
                <th style="width: 15%;" class="cuadro dato">3</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->justificacion_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Marco Teórico(fundamentos teóricos)</th>
                <th style="width: 15%;" class="cuadro dato">10</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->marco_teorico_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Procedimiento, descripción de las actividades realizadas</th>
                <th style="width: 15%;" class="cuadro dato">5</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->procedimiento_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Resultados: planos, gráficas, prototipos, manuales, programas, análisis estadísticos, modelos matemáticos, simulaciones, normativas, regulaciones y restricciones, entre otros. Solo para proyectos que lo requieran, estudios de mercado, estudio técnico y estudio económico. **</th>
                <th style="width: 15%;" class="cuadro dato">45</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->resultados_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Conclusiones, recomendaciones, y experiencia profesional adquirida.</th>
                <th style="width: 15%;" class="cuadro dato">15</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->conclusiones_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Competencias desarrolladas y/o aplicadas.</th>
                <th style="width: 15%;" class="cuadro dato">3</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->competencias_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Fuentes de información</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->fuentes_externo}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%; text-align: right;" class="cuadro">Calificación total</th>
                <th style="width: 15%;" class="cuadro dato">100</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->promedio_externo}}</th>
            </tr>
        </table>
        
    </div>
    <p>Observaciones: </p><p>{{$ultimo->comentarios_externo}}</p>
    <table class="tabla">
        <tr>
            <th style="width: 33.33%; height: 30px;  vertical-align: bottom;" class="cuadro dato">{{$estudiante->proyecto->externo->nombre}} {{$estudiante->proyecto->externo->apellido_paterno}} {{$estudiante->proyecto->externo->apellido_materno}} <br> Nombre y firma asesor externo</th>
            <th style="width: 33.33%; height: 30px;  vertical-align: bottom;" class="cuadro dato">Sello de la empresa, organismo o dependencia</th>
            <th style="width: 33.33%; height: 30px;  vertical-align: bottom;" class="cuadro dato">{{ \Carbon\Carbon::parse($ultimo->califico_externo)->format('Y-m-d') }} <br> Fecha de Evaluación</th> 
        </tr>
    </table>
    <p>Evaluación por el Asesor Interno:</p>
    <div class="cuadro">
        <table class="tabla">
            <tr>
                <th style="width: 100%;" class="cuadro">En qué medida el residente cumple con lo siguiente:</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro">Criterios a Evaluar</th>
                <th style="width: 15%;" class="cuadro">Valor</th>
                <th style="width: 15%;" class="cuadro">Evaluación</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Portada</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->portada_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Agradecimientos</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->agradecimientos_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Resumen</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->resumen_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Índice</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->indice_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Introducción</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->introduccion_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Problemas a resolver(priorizándolos)</th>
                <th style="width: 15%;" class="cuadro dato">5</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->problemas_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Objetivos</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->objetivos_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Justificación</th>
                <th style="width: 15%;" class="cuadro dato">3</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->justificacion_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Marco Teórico(fundamentos teóricos)</th>
                <th style="width: 15%;" class="cuadro dato">10</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->marco_teorico_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Procedimiento, descripción de las actividades realizadas</th>
                <th style="width: 15%;" class="cuadro dato">5</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->procedimiento_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Resultados: planos, gráficas, prototipos, manuales, programas, análisis estadísticos, modelos matemáticos, simulaciones, normativas, regulaciones y restricciones, entre otros. Solo para proyectos que lo requieran, estudios de mercado, estudio técnico y estudio económico. **</th>
                <th style="width: 15%;" class="cuadro dato">45</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->resultados_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Conclusiones, recomendaciones, y experiencia profesional adquirida.</th>
                <th style="width: 15%;" class="cuadro dato">15</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->conclusiones_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Competencias desarrolladas y/o aplicadas.</th>
                <th style="width: 15%;" class="cuadro dato">3</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->competencias_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%;" class="cuadro dato">Fuentes de información</th>
                <th style="width: 15%;" class="cuadro dato">2</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->fuentes_interno}}</th>
            </tr>
        </table>
        <table class="tabla">
            <tr>
                <th style="width: 70%; text-align: right;" class="cuadro">Calificación total</th>
                <th style="width: 15%;" class="cuadro dato">100</th>
                <th style="width: 15%;" class="cuadro dato">{{$ultimo->promedio_interno}}</th>
            </tr>
        </table>
        
    </div>
    <p>Observaciones: </p><p>{{$ultimo->comentarios_interno}}</p>
    <table class="tabla">
        <tr>
            <th style="width: 33.33%; height: 30px;  vertical-align: bottom;" class="cuadro dato">{{$estudiante->proyecto->asesor->nombre}} {{$estudiante->proyecto->asesor->apellido_paterno}} {{$estudiante->proyecto->asesor->apellido_materno}} <br> Nombre y firma asesor interno</th>
            <th style="width: 33.33%; height: 30px;  vertical-align: bottom;" class="cuadro dato">Sello de la Institución</th>
            <th style="width: 33.33%; height: 30px;  vertical-align: bottom;" class="cuadro dato"> {{ \Carbon\Carbon::parse($ultimo->califico_interno)->format('Y-m-d') }} <br> Fecha de Evaluación</th>  
        </tr>
    </table>
    <p>**Considerar los criterios a evaluar que correspondan a cada programa educativo y de acuerdo a la naturaleza del proyecto</p>
    <table class="tabla">
        <tr>
            <th style="width: 50px;">Nota:</th><th class="dato" style="text-align: left; ">Este formato tiene un valor de 80 puntos de la calificación final.</th>
        </tr>
    </table>
    <p style="margin-left:60px;">Original. - Departamento Académico (entregar 2 Evaluaciónes)</p>
    <p style="margin-left:60px;">Copia. - División de estudios profesionales (entregarla la última evaluación)</p>
    <p style="margin-left:60px;">Copia. - Estudiante</p>
</body>
</html>