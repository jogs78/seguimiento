<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @routes
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body>
        <!--
             <LoginComponent logo-url="{{ asset('images/logo_tecnm_tuxtla.png') }}"></LoginComponent>
        -->
      
        @inertia
    </body>
        
    
</html>