<?php
@session_start();
include("conexion.php");
$condicion = $_POST["condicion"];
$fecha_inicio = date('Y-m-d');

if($condicion=='patch1'){
	
	$value = $_POST["value"];
	$datos = [
		"estatus" => 'ok',
	];
	echo json_encode($datos);
}

?>