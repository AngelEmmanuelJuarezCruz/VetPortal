<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #333;
            line-height: 1.6;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .password-box {
            background-color: #f0f0f0;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .password-box label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 8px;
        }
        .password-box .password {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            word-break: break-all;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
            font-size: 14px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        .clinic-name {
            font-weight: bold;
            color: #667eea;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>VetPortal</h1>
            <p>Recuperación de Contraseña</p>
        </div>

        <div class="content">
            <div class="greeting">
                ¡Hola <strong>{{ $user->name }}</strong>!
            </div>

            <p>Recibimos una solicitud para recuperar tu contraseña en VetPortal.</p>

            <p>A continuación se encuentra tu contraseña temporal para acceder a tu clínica veterinaria <span class="clinic-name">{{ $tenant->nombre }}</span>:</p>

            <div class="password-box">
                <label>Contraseña Temporal:</label>
                <div class="password">{{ $temporaryPassword }}</div>
            </div>

            <div class="warning">
                <strong>⚠️ Importante:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Esta contraseña es solo para este acceso</li>
                    <li>Te recomendamos cambiar tu contraseña después de iniciar sesión</li>
                    <li>No compartas tu contraseña con nadie</li>
                    <li>Si no solicitaste esta recuperación, ignora este correo</li>
                </ul>
            </div>

            <p style="margin-top: 30px;">
                <a href="{{ url('/login') }}" class="button">Ir al Inicio de Sesión</a>
            </p>

            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                <strong>Tu correo de acceso:</strong> {{ $user->email }}
            </p>
        </div>

        <div class="footer">
            <p>VetPortal - Sistema de Gestión para Clínicas Veterinarias</p>
            <p>© {{ date('Y') }} VetPortal. Todos los derechos reservados.</p>
            <p style="font-size: 11px; margin-top: 10px;">
                Si tienes problemas para acceder, contacta con nuestro equipo de soporte.
            </p>
        </div>
    </div>
</body>
</html>
