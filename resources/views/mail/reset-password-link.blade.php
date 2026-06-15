<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header_seguimiento { background: linear-gradient(135deg, #050E3C, #002455); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #dee2e6; }
        .btn { display: inline-block; background: #002455; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffecb5; padding: 10px; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6c757d; }
    </style>
</head>
<body>
    <div class="header_seguimiento">
        <h2>Sistema de Seguimiento de Residencias</h2>
    </div>
    
    <div class="content">
        <h3>Hola, {{ $nombre }}</h3>
        
        <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
        
        <div class="warning">
            ⚠️ Este enlace expirará en <strong>{{ $expires_minutes }} minutos</strong>.
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $reset_url }}" class="btn">Restablecer Contraseña</a>
        </div>
        
        <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
        
        <hr>
        <small>Si el botón no funciona, copia y pega este enlace en tu navegador:</small><br>
        <small style="word-break: break-all;">{{ $reset_url }}</small>
    </div>
    
    <div class="footer">
        <p>Sistema de Seguimiento de Residencias Profesionales</p>
    </div>
</body>
</html>