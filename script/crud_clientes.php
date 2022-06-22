<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
$responsable = $_SESSION['tkosales1_id'];
$hora = date("H:i:s");

if($condicion=='rechazar1'){
	$id = $_POST["id"];
	$aprobacion_id = $_POST["aprobacion_id"];
	$external = $_POST["external"];
	$nombre = $_POST["nombre"];
	$value = $_POST["value"];

	$sql1 = "UPDATE job1 SET custcol_item_status = 5, estatus = 1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$sql2 = "UPDATE aprobacion_clientes1 SET estatus = 3, fecha_modificacion = '$fecha_modificacion', hora_modificacion = '$hora' WHERE id = ".$aprobacion_id;
	$proceso2 = mysqli_query($conexion,$sql2);

	$sql3 = "DELETE FROM artes_subidas1 WHERE nombre = '".$nombre."'";
	//$proceso3 = mysqli_query($conexion,$sql3);

	$sql4 = "SELECT * FROM aprobacion_clientes1 WHERE id = ".$aprobacion_id;
	$proceso4 = mysqli_query($conexion,$sql4);
	while($row4=mysqli_fetch_array($proceso4)){
		$orden_id = $row4["id_ordenes"];
	}

	$sql5 = "UPDATE ordenes SET item_estatus = 2 WHERE id = ".$orden_id;
	$proceso5 = mysqli_query($conexion,$sql5);
	
	$datos = [
		"estatus"	=> "ok",
		"aprobacion_id" => $aprobacion_id,
		"msg"	=> "Se ha validado dicho estatus!",
	];
	echo json_encode($datos);
}

if($condicion=='aceptar1'){
	$id = $_POST["id"];
	$aprobacion_id = $_POST["aprobacion_id"];
	$external = $_POST["external"];
	$nombre = $_POST["nombre"];

	$sql1 = "UPDATE job1 SET custcol_item_status = 4, estatus = 1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$sql2 = "UPDATE aprobacion_clientes1 SET estatus = 2, fecha_modificacion = '$fecha_modificacion', hora_modificacion = '$hora' WHERE id = ".$aprobacion_id;
	$proceso2 = mysqli_query($conexion,$sql2);

	$sql3 = "DELETE FROM artes_subidas1 WHERE nombre = '".$nombre."'";
	//$proceso3 = mysqli_query($conexion,$sql3);

	$sql4 = "SELECT * FROM aprobacion_clientes1 WHERE id = ".$aprobacion_id;
	$proceso4 = mysqli_query($conexion,$sql4);
	while($row4=mysqli_fetch_array($proceso4)){
		$orden_id = $row4["id_ordenes"];
	}

	$sql5 = "UPDATE ordenes SET item_estatus = 5 WHERE id = ".$orden_id;
	$proceso5 = mysqli_query($conexion,$sql5);

	$datos = [
		"estatus"	=> "ok",
		"aprobacion_id" => $aprobacion_id,
		"msg"	=> "Se ha validado dicho estatus!",
	];
	echo json_encode($datos);
}


?>