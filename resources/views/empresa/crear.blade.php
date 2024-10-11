<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Empresa</title>
</head>
<body>
    <h2>Registra la empresa en la que haras tu residencia</h2>

    <form action="{{route("empresas.store")}}" method="POST" enctype="application/x-www-form-urlencoded">
        @csrf
        <label for='nombre'>Nombre</label>
        {{$errors->first("nombre")}}
        <input type='text' name='nombre' id='nombre' value="{{old('nombre')}}"><br>
        
        <label for='giro'>Escoge el Giro, Ramo o Sector</label> <br>
        {{$errors->first("giro")}}

        <input type='radio' name='giro' value="{{old('industrial')}}">
        <label for="industrial">Industrial</label>
        <input type='radio' name='giro' value="{{old('servicios')}}">
        <label for="servicios">Servicios</label> <br>
        <input type='radio' name='giro' value="{{old('publico')}}">
        <label for="publico">Publico</label>
        <input type='radio' name='giro' value="{{old('privado')}}">
        <label for="privado">Privado</label>
        <input type='radio' name='giro' value="{{old('otro')}}">
        <label for="otro">Otro</label><br>
        
        <label for='rfc'>RFC</label>
        {{$errors->first("rfc")}}
        <input type='text' name='rfc' id='rfc' value="{{old('rfc')}}"><br>

        <label for='telefono'>Telefono</label>
        {{$errors->first("telefono")}}
        <input type='tel' name='telefono' id='telefono' value="{{old('telefono')}}"><br>

        <label for='correo'>Correo Electronico</label>
        {{$errors->first("correo")}}
        <input type='email' name='correo' id='correo' value="{{old('correo')}}"><br>

        <label for='titular'>Nombre del Titular de la empresa</label>
        {{$errors->first("titular")}}
        <input type='text' name='titular' id='titular' value="{{old('titular')}}"><br>

        <label for='puesto_titular'>Puesto del Titular</label>
        {{$errors->first("puesto_titular")}}
        <input type='text' name='puesto_titular' id='puesto_titular' value="{{old('puesto_titular')}}"><br>
       
        <label for='asesor_externo'>Nombre del Asesor externo</label>
        {{$errors->first("asesor_externo")}}
        <input type='text' name='asesor_externo' id='asesor_externo' value="{{old('asesor_externo')}}"><br>

        <label for='puesto_asesor'>Puesto del Asesor</label>
        {{$errors->first("puesto_asesor")}}
        <input type='text' name='puesto_asesor' id='puesto_asesor' value="{{old('puesto_asesor')}}"><br>

        <label for='informacion'>Informacion adicional de la empresa: </label>
        {{$errors->first("informacion")}}
        <textarea name='informacion' id='informacion' value="{{old('informacion')}}"></textarea><br>
       
        <input type='submit'>
    </form>

</body>
</html>