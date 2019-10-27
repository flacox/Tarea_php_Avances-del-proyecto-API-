<?php


function buscarMaterias(){
    $cn = getConexion();

    $stm = $cn->query("SELECT * FROM materias");

    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row){

        $data[] = [
            "id"=> $row["id"],
            "nombre" => $row["nombre"],
            "creditos" => $row["creditos"],
        ];

    }

    header("Content-Type: application/json, true");
    $data = json_encode($data);
    echo $data;

}

function guardarMaterias(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);

    $cn = getConexion();
    $stm = $cn->prepare("INSERT INTO materias (nombre, creditos) VALUES (:nombre, :creditos)");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":creditos", $data["creditos"]);
    $data = $stm->execute();
    echo 'Materia Guardada!';
}

function eliminarMaterias(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);
    
    $cn = getConexion();
	$stm = $cn->prepare("DELETE FROM materias WHERE id= :id"); 
	$stm->bindParam(':id',  $data["id"]); 
    $data = $stm->execute();
    echo 'Materia Eliminada!';
}

require('conexion_universidad.php');

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case 'POST':
    guardarMaterias();
        break;

    case 'GET':
    buscarMaterias();
        break;

    case 'PUT':
        //
        break;

    case 'DELETE':
    eliminarMaterias();
        break;
        
     default:
        echo 'To be implemeted';   
}