<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <link rel="stylesheet" href="estilos.css">
 <title>Document</title>
 <style>
.cmenor{background-color: rgb(40, 95, 139);}
.cmayor{background-color: rgb(19, 46, 68);}
.linea{background-color: rgb(10, 105, 163); height: 4px; border-radius: 2px; width: 90%;} 
.hderecho {display: flex; justify-content: right; }
.horizontal {display: flex; justify-content: center; width: 100%;}
.centro{display: flex; justify-content: center;}
.titulo{text-align:center; font-size: 50px; font-weight: bold;}
.parrafo{font-size: 30px; margin-top:30px;}
.boton{background-color: rgb(25, 118, 210); width: 120px; height: 30px; border-radius: 5px; color: white; border: none; cursor: pointer;}
.llenar{height: 25px; font-size: 18px;}
*{margin: 0; padding: 0;}
</style>

</head>
<body>

<div class="hderecho" style="width: 100%; height: 20px; ">
    <div class="cmenor" style="width: 20%;"></div>
    <div class="cmayor" style="width: 80%; "></div>
</div>

<div class="horizontal">
    <p class="titulo">Inicio de Sesión</p>
</div>
<div class="horizontal">
    <div class="linea"></div>
</div>

<div class="centro"; style="margin-top: 7%; ">
 <form action="{{route('adentro')}}" method="POST">
  @csrf
    <div>
        <label for='nombre' class="parrafo" >Correo electronico: </label>
        <div>
            @if(session('errorsesion'))
            <div class="alert alert-danger">
                {{ session('errorsesion') }}
            </div>
            @endif
            <span class="error-message">{{ $errors->first("nombre") }}</span>
        </div>
        <input class="llenar" type='text' name='nombre' id='nombre' value="{{old('nombre')}}" placeholder="Ingresa tu correo"><br>
    </div>
    <div style="margin-top: 20px;">
        <label for='contra'  class="parrafo">Contraseña: </label>
        <div>
            @if(session('errorcontra'))
            <div class="alert alert-danger">
                {{ session('errorcontra') }}
            </div>
            @endif
            <span class="error-message">{{ $errors->first("contra") }}</span>
        </div>
        <input  class="llenar" type="password"  name='contra' id='contra' value="{{old('contra')}}" placeholder="Ingresa tu Contraseña"><br>
    </div>
  <div class="horizontal" style="margin-top: 60px; "><input class="boton" type='submit' value="Entrar"></div>
 </form>
 </div>
</body>
</html>