<?php


function buscarEstudiantes(){
    
    $cn = getConexion();

    $stm = $cn->query("SELECT e.*, c.nombre AS carrera FROM estudiantes e INNER JOIN carrera c ON e.carrera_id = c.id");

    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row){

        $data[] = [
            "id"=> $row["id"],
            "nombre" => $row["nombre"],
            "matricula" => $row["matricula"],
            "edad" => $row["edad"],
            "carrera_id" => $row["carrera_id"],
            "carrera" => $row["carrera"]
        ];

    }

    header("Content-Type: application/json, true");
    $data = json_encode($data);
    echo $data;

}

function guardarEstudiantes(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);

    $cn = getConexion();
    $stm = $cn->prepare("INSERT INTO estudiantes (nombre, matricula, edad, carrera_id) VALUES (:nombre, :matricula, :edad, :carrera_id)");
    $stm->bindParam(":nombre", $data["nombre"]);
    $stm->bindParam(":matricula", $data["matricula"]);
    $stm->bindParam(":edad", $data["edad"]);
    $stm->bindParam(":carrera_id", $data["carrera_id"]);
    $data = $stm->execute();
    //echo 'Estudiante Guardado!';
}

function eliminarEstudiante(){
    $postdata = file_get_contents("php://input");
    $data = json_decode($postdata, true);
    
    $cn = getConexion();
	$stm = $cn->prepare("DELETE FROM estudiantes WHERE id= :id"); 
	$stm->bindParam(':id',  $data["id"]); 
    $data = $stm->execute();
    echo 'estudiante Eliminado!';
}

require('conexion_universidad.php');

$method = $_SERVER["REQUEST_METHOD"];

switch ($method){
    case 'POST':
    guardarEstudiantes();
        break;

    case 'GET':
    buscarEstudiantes();
        break;

    case 'PUT':
     actualizarEstudiante(id);
        break;

    case 'DELETE':
    eliminarEstudiante(id);
        break;
        
     default:
        echo 'To be implemeted';   
}