<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php'; 

class clientes extends conexion{

    private $table = 'clientes';
    private $clienteid = '';
    private $dni = '';
    private $nombre = '';
    private $genero = '';
    private $telefono = '';
    private $fechaNacimiento = '0000-00-00';
    private $correo = '';
    private $token = ''; // 94e375cba1fda1ff8c67c641c31f1431
    private $imagen = "";

    public function listaClientes($pagina = 1){
        $inicio = 0;
        $cantidad = 50;
        if ($pagina > 1) {
            $inicio = ($cantidad * ($pagina - 1)) + 1;
            $cantidad = $cantidad * $pagina;
        }

        $query = "SELECT Id, nombre, dni, telefono, correo FROM " . $this->table . 
                " LIMIT $inicio, $cantidad;";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

    public function obtenerCliente($id){
        $query = "SELECT * FROM " . $this->table . " WHERE Id = '$id';";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos['token'])){
                return $_respuestas->error_401();
            } else {
                    $this->token = $datos['token'];
                    $arrayToken = $this->buscarToken();
            if($arrayToken){
                // validar los campos requeridos para insertar
                if (!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])){
                    return $_respuestas->error_400();
                } else {
                        $this->nombre = $datos['nombre'];
                        $this->dni = $datos['dni'];
                        $this->correo = $datos['correo'];
                        if (isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                        if (isset($datos['genero'])) { $this->genero = $datos['genero']; }
                        if (isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
                        // if (isset($datos['imagen'])){
                        //     $resp = $this->procesarImagen($datos['imagen']);
                        //     $this->imagen = $resp;
                        // }
                    $resp = $this->insertarPaciente();
                    if ($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "pacienteId" => $resp
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }

    }

    private function procesarImagen($img){
        $path = dirname(__DIR__) . "\public\img\\";
        $partes = explode(';base64,',$img);
        $extencion = explode('/', mime_content_type($img))[1];
        $imagen_base64 = base64_decode($partes[1]);
        $file = $path . uniqid() . "." . $extencion;
        file_put_contents($file, $imagen_base64);
        $nuevadireccion = str_replace('\\', '/', $file);
        return $nuevadireccion;
    }

    private function insertarPaciente(){
        $query = "INSERT INTO " . $this->table . " (dni,nombre,telefono,genero,fechaNacimiento,correo)
        values
        ('" . $this->dni . "','" . $this->nombre . "','"  . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "')"; 
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos['token'])){
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                // validar el unico campo requerido
                if (!isset($datos['Id'])){
                    return $_respuestas->error_400();
                } else {
                    $this->clienteid = $datos['Id'];
                    if (isset($datos['nombre'])) { $this->nombre = $datos['nombre']; }
                    if (isset($datos['dni'])) { $this->dni = $datos['dni']; }
                    if (isset($datos['correo'])) { $this->correo = $datos['correo']; }
                    if (isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                    if (isset($datos['genero'])) { $this->genero = $datos['genero']; }
                    if (isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
                    $resp = $this->modificarCliente();
                    if ($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "clienteId" => $this->clienteid
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
    }

    private function modificarCliente(){
        $query = "UPDATE " . $this->table . " SET nombre ='" . $this->nombre . "', dni = '" . $this->dni . "', telefono = '" . $this->telefono . 
                "', genero = '" . $this->genero . "', fechaNacimiento = '" . $this->fechaNacimiento . "', correo = '" . $this->correo .
                "' WHERE Id = '" . $this->clienteid . "'"; 
        $resp = parent::nonQuery($query);
        if ($resp >= 1){
            return $resp;
        } else {
            return 0;
        }
    }

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);

        if (!isset($datos['token'])){
            return $_respuestas->error_401();
        } else {
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if($arrayToken){
                // validar el unico campo requerido
                if (!isset($datos['clienteId'])){
                    return $_respuestas->error_400();
                } else {
                    $this->clienteid = $datos['clienteId'];
                    $resp = $this->eliminarCliente();
                    if ($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "clienteId" => $this->clienteid
                        );
                        return $respuesta;
                    } else {
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }
    }

    private function eliminarCliente(){
        $query = "DELETE FROM " . $this->table . " WHERE Id= '" . $this->clienteid . "'";
        $resp = parent::nonQuery($query);
        if ($resp >= 1){
            return $resp;
        } else {
            return 0;
        }
    }

    private function buscarToken(){
        $query = "SELECT  tokenId, usuarioId, estado from usuarios_token WHERE token = '" . $this->token . "' AND Estado = 1";
        $resp = parent::obtenerDatos($query);
        if ($resp){
            return $resp;
        } else {
            return 0;
        }
    }

}

?>