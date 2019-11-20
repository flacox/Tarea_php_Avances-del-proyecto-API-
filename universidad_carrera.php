<?php


function buscarCarrera(){
    
    $cn = getConexion();

    $stm = $cn->query("SELECT * FROM carrera");

    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row){

        $data[] = [
            "id"=> $row["id"],
            "nombre" => $row["nombre"]
        ];

    }

    header("Content-Type: application/json, true");
    $data = json_encode($data);
    echo $data;

}

function guardarCarrera(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);

    $cn = getConexion();
    $stm = $cn->prepare("INSERT INTO carrera (nombre) VALUES (:nombre)");
    $stm->bindParam(":nombre", $data["nombre"]);
    $data = $stm->execute();
    echo 'Carrera Guardada!';
}

function eliminarCarrera(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);
    
    $cn = getConexion();
	$stm = $cn->prepare("DELETE FROM carrera WHERE id= :id"); 
	$stm->bindParam(':id',  $data["id"]); 
    $data = $stm->execute();
    echo 'Carrera Eliminada!';
}

function actualizarCarrera($id){
    
    if ($id == null) {
        header("HTTP/1.1 400 Bad Request");
        $response = [ 
            "error" => true,
            "message" => "Campos id es requerido"
        ];
        
        echo json_encode($response);
       
        return;
    } 

    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);

    $errors = [];
    if (!$data["nombre"]) {
        $errors[] = "campo nombre es requerido";
    }


    if (count($errors)>0){
        header("HTTP/1.1 400 Bad Request");
        $response = [ 
            "error" => true,
            "message" => "Campo requerido",
            "errors" =>  $errors
        ];
        
        echo json_encode($response);
        return;
    }

    $cn = getConexion();
    $stm = $cn->prepare("UPDATE carrera SET nombre = :nombre WHERE id = :id");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":id", $id);

    try {
        $data = $stm->execute();

        $response = [ 
            "error" => false,
        ];
        
        echo json_encode($response);
    } catch(Exception $e){

        $response = [ 
            "error" => true,
            "message" => "Error desconocido"
        ];
        
        echo json_encode($response);
    }

}



require('conexion_universidad.php');

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case 'POST':
        guardarCarrera();
        break;

    case 'GET':
        buscarCarrera();
        break;

    case 'PUT':
        $id = $_GET["id"];
        editarCarrera(id);

        break;

    case 'DELETE':
        eliminarCarrera(id);
        break;
        
     default:
        echo 'To be implemeted';   
}
