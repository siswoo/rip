<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

include('script/conexion.php');
@$condicion = $_POST["condition"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');

if($condicion==''){
	$datos = [
		"estatus"	=> "error",
		"msg"		=> "No ha colocado condition",
	];
	echo json_encode($datos);
}else if($condicion=='create_tiff'){
	@$usuario_sql = $_POST["user"];
	@$password_sql = md5($_POST["password"]);
	@$url = $_POST["url"];
	@$folder = $_POST["folder"];
	@$trand_id = $_POST["trand_id"];

	$sql1 = "SELECT * FROM folders1 WHERE nombre = '$folder'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1==0){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Verificar Folder por favor!",
		];
		echo json_encode($datos);
		exit;
	}
	
	if($usuario_sql=='' or $password_sql=='' or $url==''){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "No tiene todos los datos requeridos!",
		];
		echo json_encode($datos);
		exit;
	}

	$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario_sql' and password = '$password_sql'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1==0){
		$datos = [
			"estatus"		=> "error",
			"msg"		=> "Credenciales Incorrectas!",
		];
		echo json_encode($datos);
		exit;
	}
}else{
	$datos = [
		"estatus"	=> "error",
		"msg"		=> "No existe esa condition",
	];
	echo json_encode($datos);
}

?>