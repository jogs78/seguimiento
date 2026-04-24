<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @routes
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @inertiaHead

         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <body>
        <!--
             <LoginComponent logo-url="{{ asset('images/logo_tecnm_tuxtla.png') }}"></LoginComponent>
        -->
      
        @inertia
    </body>
        
    
</html>