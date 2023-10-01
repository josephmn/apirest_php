<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

class auth extends conexion
{

    public function login($json)
    {
        $_respuestas = new respuestas;
        $datos = json_decode($json, true);
        if (!isset($datos['usuario']) || !isset($datos['password'])) {
            // error en los campos
            return $_respuestas->error_400();
        } else {
            // esta bien
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = parent::encriptar($password);
            $datos = $this->obtenerDatosUsuario($usuario);
            if ($datos){
                // verificar si la contraseña es igual
                if ($password == $datos[0]['password']){
                    if ($datos[0]['estado'] == '1'){ // Activo
                        // crear el token
                        $verificar = $this->insertarToken($datos[0]['usuarioId']);
                        if ($verificar){
                            // si se guardo
                            $result = $_respuestas->response;
                            $result['result'] = array(
                                'token' => $verificar
                            );
                            return $result;
                        } else {
                            // error al guardar
                            return $_respuestas->error_500("Error interno, No hemos podido guardar");
                        }
                    } else {
                        // el usuario esta inactivo
                        return $_respuestas->error_200("El usuario esta inactivo");
                    }
                } else {
                    // la contraseña no es igual
                    return $_respuestas->error_200("El password es invalido");
                }

            } else {
                // no existe el usuario
                return $_respuestas->error_200("El usuario: $usuario no existe");
            }
        }
    }

    private function obtenerDatosUsuario($correo){
        $query = "SELECT usuarioId, password, estado FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if (isset($datos[0]["usuarioId"])){
            return $datos;
        } else {
            return 0;
        }
    }

    private function insertarToken($usuarioid){
        date_default_timezone_set("America/Lima");
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i:s");
        $estado = 1;
        $query = "INSERT INTO usuarios_token (usuarioId, token, estado, fecha)VALUES('$usuarioid','$token',$estado,'$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }
}

?>