@component('mail::message')
# Código de Seguridad

Hola, estás intentando iniciar sesión en **House Fix**.
Tu código de verificación es:

# {{ $code }}

Este código expirará en 10 minutos.
Si no fuiste tú, por favor cambia tu contraseña inmediatamente.

Gracias,<br>
El equipo de House Fix
@endcomponent   