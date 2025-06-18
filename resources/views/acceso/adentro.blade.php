@extends('plantillas.app')
<style>
    .centro{display: flex; justify-content: center;}
</style>
@section('encabezado')
    <a href="{{route('Cambiar_Contraseña')}}">Cambiar la Contraseña</a>
@endsection
@section('contenido')
    <h1 class="centro">Sistema de seguimiento</h1> <br><br>
   <div style="text-align: center;">
        <img src="{{ asset('storage/img/logo.png') }}" alt="Logo"
         style="width: 25%; height: auto;">
    </div>
@endsection