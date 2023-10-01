<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Clientes</title>
    <link rel="stylesheet" href="assets/estilo.css" type="text/css">
</head>
    <body>
        <div class="container">
            <h1>Api de Clientes</h1>
            <div class="divbody">
                <h3>Auth - login</h3>
                <code>
                    POST /auth
                    <br>
                    {
                    <br>
                    "usuario" :"admin@gmail.com", -> REQUERIDO
                    <br>
                    "password": "123456" -> REQUERIDO
                    <br>
                    }
                </code>
            </div>
            <div class="divbody">
                <h3>Clientes</h3>
                <code>
                    GET /clientes?page=$numeroPagina
                    <br>
                    GET /clientes?id=$idCliente
                </code>
                <code>
                    POST /clientes
                    <br>
                    {
                    <br>
                    "dni" : "", -> REQUERIDO
                    <br>
                    "nombre" : "", -> REQUERIDO
                    <br>
                    "correo":"", -> REQUERIDO
                    <br>
                    "genero" : "",
                    <br>
                    "telefono" : "",
                    <br>
                    "fechaNacimiento" : "",
                    <br>
                    "token" : "" -> REQUERIDO
                    <br>
                    }
                </code>
                <code>
                    PUT /clientes
                    <br>
                    {
                    <br>
                    "nombre" : "",
                    <br>
                    "dni" : "",
                    <br>
                    "correo":"",
                    <br>
                    "genero" : "",
                    <br>
                    "telefono" : "",
                    <br>
                    "fechaNacimiento" : "",
                    <br>
                    "token" : "" , -> REQUERIDO
                    <br>
                    "pacienteId" : "" -> REQUERIDO
                    <br>
                    }
                </code>
                <code>
                    DELETE /clientes
                    <br>
                    {
                    <br>
                    "token" : "", -> REQUERIDO
                    <br>
                    "clienteId" : "" -> REQUERIDO
                    <br>
                    }
                </code>
            </div>
        </div>
    </body>
</html>