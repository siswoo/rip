<?php
include('script/conexion.php');
@$condicion = $_POST["condition"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
$hora_inicio = date('G:H:i');

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

	if($usuario_sql=='' or $password_sql=='' or $url=='' or $folder=='' or $trand_id==''){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "No tiene todos los datos requeridos!",
		];
		echo json_encode($datos);
		exit;
	}

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

	$sql2 = "SELECT * FROM usuarios WHERE usuario = '$usuario_sql' and password = '$password_sql'";
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);
	if($contador2==0){
		$datos = [
			"estatus"		=> "error",
			"msg"		=> "Credenciales Incorrectas!",
		];
		echo json_encode($datos);
		exit;
	}

	$url2 = explode(".",$url);
	$url_contador = count($url2)-1;
	$url_contador = strtolower($url_contador);
	if($url2[$url_contador]!="pdf"){
		$datos = [
			"estatus"		=> "error",
			"msg"		=> "El formato del URL no es PDF!",
		];
		echo json_encode($datos);
		exit;
	}

	$sql3 = "SELECT * FROM colas WHERE pdf = '$url'";
	$proceso3 = mysqli_query($conexion,$sql3);
	$contador3 = mysqli_num_rows($proceso3);
	if($contador3>=1){
		$datos = [
			"estatus"		=> "error",
			"msg"		=> "Ya existe dicho URL en Cola!",
		];
		echo json_encode($datos);
		exit;
	}

	//file_put_contents("okok1.pdf", file_get_contents($url));

	$sql4 = "INSERT INTO colas (pdf,estatus,fecha_inicio,hora_inicio) VALUES ('$url',1,'$fecha_inicio','$hora_inicio')";
	$proceso4 = mysqli_query($conexion,$sql4);

	function rmDir_rf($carpeta){
		foreach(glob($carpeta . "/*") as $archivos_carpeta){
			if (is_dir($archivos_carpeta)){
				rmDir_rf($archivos_carpeta);
			}else{
				unlink($archivos_carpeta);
	        }
		}
		rmdir($carpeta);
	}
	//rmDir_rf($folder);

	$datos = [
		"estatus"	=> "ok",
		"msg"		=> "Todo OK",
	];
		echo json_encode($datos);

	//verificar carpeta completed que este vacias luego las demas
	//verificar cual esta en cola
}else{
	$datos = [
		"estatus"	=> "error",
		"msg"		=> "No existe esa condition",
	];
	echo json_encode($datos);
}

?>