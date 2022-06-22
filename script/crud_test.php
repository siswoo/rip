<?php
@session_start();
include("conexion.php");
$condicion = $_POST["condicion"];
$fecha_inicio = date('Y-m-d');
//$responsable = $_SESSION['tkosales1_id'];

if($condicion=='tareaspdf1'){
	$texto1 = $_POST["texto1"];
	$clau = $_POST["clau"];
	$texto2 = '';
	
	$texto2 = preg_replace("/[\r\n|\n|\r]+/", " ", $texto1);
	
	if($clau==10){
		for ($i=10;$i<=100;$i++){
			$j = "/".$i;
			$texto2 = str_replace($j,",",$texto2);
		}
	}else if($clau==100){
		for ($i=100;$i<=1000;$i++){
			$j = "/".$i;
			$texto2 = str_replace($j,",",$texto2);
		}
	}

	$texto2 = str_replace(" ","",$texto2);
	$texto2 = substr($texto2,0,-1);

	$datos = [
		"estatus" 	=> 'ok',
		"texto2"		=> $texto2,
	];

	echo json_encode($datos);
}

?>