<?php

$servername = "localhost";
$username = "root";
$password = "Sgr9cl4v3s";
$database = "apirest";
$res = [];

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// // OPCION 1
// $stmt = $conn->prepare("CALL sp_clientes()");
// $stmt->execute();

// $stmt->bind_result($Id, $nombre, $dni, $telefono, $correo);

// $i = 0;
// while ($stmt->fetch()) {
//     $res[$i]["Id"] = $Id;
//     $res[$i]["nombre"] = $nombre;
//     $res[$i]["dni"] = $dni;
//     $res[$i]["telefono"] = $telefono;
//     $res[$i]["correo"] = $correo;
//     $i++;
// }

// echo '<pre>';
// print_r($res);
// echo '</pre>';

/***************************************************************************************************/

// OPCION 2
$query_consult="CALL sp_clientes()";
$result=mysqli_query($conn,$query_consult);

if ($result) {
    $i = 0;
    while($row = mysqli_fetch_row($result)) {

        #region 'METODO 1: ESTO SE EJECUTA'
            // $res[$i] = $row;    // devuelve esto: 

                                // [0] => Array
                                // (
                                //     [0] => 1
                                //     [1] => Joseph Carlos Magallanes
                                //     [2] => 78787878
                                //     [3] => 51999999999
                                //     [4] => joseph.magallanes@gmail.com
                                // )
        #endregion
        
        #region 'METODO 2: ESTO SE EJECUTA'
        $res[$i]["Id"] = $row[0];
        $res[$i]["nombre"] = $row[1];
        $res[$i]["dni"] = $row[2];
        $res[$i]["telefono"] = $row[3];
        $res[$i]["correo"] = $row[4];   // devuelve esto: 

                                        // [0] => Array
                                        // (
                                        //     [Id] => 1
                                        //     [nombre] => Joseph Carlos Magallanes
                                        //     [dni] => 78787878
                                        //     [telefono] => 51999999999
                                        //     [correo] => joseph.magallanes@gmail.com
                                        // )

        #endregion

        $i++;
    }

    // $j = 0;
    // while($row = mysqli_fetch_assoc($result)) {
    //     $res[$j]["Id"] = $row["Id"];
    //     $res[$j]["nombre"] = $row["nombre"];
    //     $res[$j]["dni"] = $row["dni"];
    //     $res[$j]["telefono"] = $row["telefono"];
    //     $res[$j]["correo"] = $row["correo"];
    //     $j++;
    // }
} else {
    echo "Error: ". mysqli_error($conn);
}

echo '<pre>';
print_r($res);
echo '</pre>';

$conn->close();

// var_dump($res["mysqli_fetch_assoc"][0]["nombre"]);

?>