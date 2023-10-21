<?php

require_once 'clases/respuestas.class.php';
require_once 'clases/clientes.class.php';

$_respuestas = new respuestas;
$_clientes = new clientes;

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case 'GET':
        if (isset($_GET['page'])){
            $pagina = $_GET['page'];
            $listaClientes = $_clientes->listaClientes($pagina);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($listaClientes);
            http_response_code(200);
        } else if (isset($_GET['id'])){
            $clienteid = $_GET['id'];
            $datosCliente = $_clientes->obtenerCliente($clienteid);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($datosCliente);
            http_response_code(200);
        } else if (isset($_GET['estado'])){
            $estado = $_GET['estado'];
            $datosCliente = $_clientes->estadoClientes($estado);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($datosCliente);
            http_response_code(200);
        }
        break;
    case 'POST':
        $postBody = file_get_contents("php://input"); // recibimos los datos enviados
        $datosArray = $_clientes->post($postBody); // enviamos los datos al manejador
        header('Content-Type: application/json; charset=utf-8'); // devolvemos una respuesta
        if(isset($datosArray['result']['error_id'])){
            $responseCode = $datosArray['result']['error_id'];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);
        break;
    case 'PUT':
        $postBody = file_get_contents("php://input"); // recibimos los datos enviados
        $datosArray = $_clientes->put($postBody); // enviamos los datos al manejador
        header('Content-Type: application/json; charset=utf-8'); // devolvemos una respuesta
        if(isset($datosArray['result']['error_id'])){
            $responseCode = $datosArray['result']['error_id'];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);
        break;
    case 'DELETE':
        $headers = getallheaders();
        if (isset($headers["token"]) && isset($headers["pacienteId"])){
            // recibimos los datos enviados por el header
            $send = [
                "token" => $headers["token"],
                "pacienteId" =>$headers["pacienteId"]
            ];
            $postBody = json_encode($send);
        } else {
            $postBody = file_get_contents("php://input"); // recibimos los datos enviados
        }
        $datosArray = $_clientes->delete($postBody); // enviamos los datos al manejador
        header('Content-Type: application/json; charset=utf-8'); // devolvemos una respuesta
        if(isset($datosArray['result']['error_id'])){
            $responseCode = $datosArray['result']['error_id'];
            http_response_code($responseCode);
        } else {
            http_response_code(200);
        }
        echo json_encode($datosArray);
        break;
    default:
        header('Content-Type: application/json; charset=utf-8');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
        break;
}

?>