<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Asesor</title>
</head>
<body>

    <p>Asignacion de Asesores a un proyecto</p>

    <table border='1'>
        <thead>
            <th>Nombre del Proyecto</th>
            <th>Residente/s</th>
            <th>Empresa</th>
        </thead>
        <tbody>

        </tbody>
    </table>

    <!-- ¿Es conveniente esta forma? -->
    <label for='escoger_asesor'>Escoga a un asesor de la lista: </label>
    <select name="escoger_asesor" id="escoger_asesor" size="3"> 
            <option value="">Alberto Mendez Talavera</option>
            <option value="">Juan Gomez Perez</option>
            <option value="">Carlos Ruiz Cruz</option>
    </select> <br>

    <input type="submit">

</body>
</html>