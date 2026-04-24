<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 2px solid #000000;
            border-radius: 8px;
            overflow: hidden;
        }
        
        
        /* Cuerpo del mensaje con fondo azul oscuro */
        .message-body {
            background-color: #0d3b66;
            padding: 25px;
            border-bottom: 2px solid #000000;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 15px;
            color: #ffffff;
        }
        .greeting strong {
            color: #FF3838;
        }
        .message-content {
            background-color: #1a3a6e;
            padding: 20px;
            border-radius: 6px;
            color: #ffffff;
            white-space: pre-wrap;
            font-family: inherit;
            border: 1px solid #cccccc;
        }
        
        /* Nota importante tipo tabla con bordes negros */
        .info-card {
            background-color: #ffffff;
            margin: 0;
            border: 2px solid #000000;
            border-top: none;
        }
        .info-title {
            background-color: #f0f0f0;
            padding: 12px 20px;
            border-bottom: 2px solid #000000;
            font-weight: bold;
            font-size: 12px;
        }
        .info-title i {
            margin-right: 8px;
        }
        .info-content {
            padding: 15px 20px;
            font-size: 10px;
        }
        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
            color: #0d3b66;
        }
        .info-value {
            display: inline-block;
            color: #333;
        }
        
        /* Footer con logos y disclaimer , centrar*/
        .footer {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            border-top: 2px solid #000000;
        }
        
        .footer-text {
            font-size: 13px;
            font-weight: bold;
            color: #0d3b66;
            margin-bottom: 15px;
        }
        .disclaimer {
            background-color: #f8f9fa;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 10px;
            color: #666;
            text-align: center;
            line-height: 1.4;
        }
        
        hr {
            margin: 0;
            border: none;
            border-top: 2px solid #000000;
        }
        
        @media (max-width: 600px) {
            .container {
                width: 100%;
            }
            .info-label {
                display: block;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        

        <!-- Cuerpo del mensaje con fondo azul -->
        <div class="message-body">
            <div class="greeting">
                Estimado(a) <strong>{{ $destinatario_nombre }}</strong>,
            </div>
            <div class="message-content">
                {!! nl2br(e($content)) !!}
            </div>
        </div>

        <!-- Nota importante estilo tabla -->
        <div class="info-card">
            <div class="info-title">
                <i>ℹ️</i> Nota importante
            </div>
            <div class="info-content">
                Este mensaje ha sido generado automáticamente desde el Sistema de Seguimiento de Residencias Profesionales.
                Enviado por <span class="info-value">{{ $remitente_tipo }}: {{ $remitente_nombre }}</span>.
                Por favor, no respondas a este correo directamente. Si necesitas contactar al remitente, utiliza los medios
                oficiales del TecNM.
                
            </div>
        </div>

        <!-- Footer con logos y confidencialidad -->
        <div class="footer">
            <table align="center" style="margin: 0 auto;">
                <tr>
                    <td style="text-align: center; padding: 0 10px;">
                        <img src="{{ $message->embed(public_path('images/logo_tecnm_tuxtla.png')) }}" alt="TecNM Tuxtla" style="max-height:50px;">
                    </td>
                    <td style="text-align: center; padding: 0 10px;">
                        <span style="font-size: 20px; color: #0d3b66;">|</span>
                    </td>
                    <td style="text-align: center; padding: 0 10px;">
                        <img src="{{ $message->embed(public_path('images/logo_tecnm_nacional.png')) }}" alt="TecNM Nacional" style="max-height:45px;">
                    </td>
                </tr>
            </table>
            <div class="footer-text">
                Tecnológico Nacional de México - Sistema de Seguimiento de Residencias Profesionales
            </div>
            <div class="disclaimer">
                <strong> CONFIDENCIAL</strong><br>
                La información contenida en este correo electrónico es confidencial y está dirigida únicamente al destinatario o destinatarios indicados.
                Si has recibido este mensaje por error, te agradecemos notificarlo al remitente y eliminarlo de tu sistema.
                Queda prohibida su divulgación, copia o distribución sin la autorización correspondiente.
            </div>
        </div>
    </div>
</body>
</html>