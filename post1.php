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
}else if($condicion=='order_create'){
	@$usuario_sql = $_POST["name"];
	@$password_sql = md5($_POST["password"]);
	@$external_id = $_POST["external_id"];
	@$trand_id = $_POST["trand_id"];
	@$customer_name = $_POST["customer_name"];
	@$item = $_POST["item"];
	$errores1 = 'Items con errores = ';
	$validacion1 = 1;
	
	if($usuario_sql=='' or $password_sql=='' or $external_id=='' or $trand_id=='' or $customer_name=='' or $item==''){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "No tiene todos los datos requeridos!",
		];
		echo json_encode($datos);
		exit;
	}

	$array = json_decode($item);

	$sql4 = "SELECT * FROM usuarios WHERE usuario = '$usuario_sql' and password = '$password_sql'";
	$proceso4 = mysqli_query($conexion,$sql4);
	$contador4 = mysqli_num_rows($proceso4);
	if($contador4==0){
		$datos = [
			"estatus"		=> "error",
			"msg"		=> "Credenciales Incorrectas!",
		];
		echo json_encode($datos);
		exit;
	}

	foreach($array as $obj){
		$validacion1_1 = 1;
		$item_line = $obj->item_line;
		$item = $obj->item;
		$job_id = $obj->job_id;
		$item_estatus = $obj->item_status;
		$unicaid = $obj->user_unicaid;
		$descripcion = $obj->description;
		$quantity = $obj->quantity;
		$rate = $obj->rate;
		$url1 = $obj->url1;
		$url2 = $obj->url2;
		$fecha_item = $obj->date;
		$fecha_item = explode("/",$fecha_item);
		$fecha_item = $fecha_item[2]."/".$fecha_item[1]."/".$fecha_item[0];

		$sql3 = "SELECT * FROM usuarios WHERE unicaid = '$unicaid'";
		$proceso3 = mysqli_query($conexion,$sql3);
		$contador3 = mysqli_num_rows($proceso3);

		if($contador3==0){
			$errores1 .= ' | Error el unicaiD no existe en el Job_id = '.$job_id;
			$validacion1 = 0;
			$validacion1_1 = 0;
		}

		if(is_numeric($item_line)==false){
			$errores1 .= ' | Error en Item Line del Job_id = '.$job_id;
			$validacion1 = 0;
			$validacion1_1 = 0;
		}else if(is_numeric($item_estatus)==false){
			$errores1 .= ' | Error en item status del Job_id = '.$job_id;
			$validacion1 = 0;
			$validacion1_1 = 0;
		}

		if($validacion1_1==1){
			$sql1 = "SELECT * FROM usuarios WHERE unicaid = '$unicaid'";
			$proceso1 = mysqli_query($conexion,$sql1);
			$contador1 = mysqli_num_rows($proceso1);
			if($contador1>=1){
				while($row1=mysqli_fetch_array($proceso1)){
					$responsable = $row1["id"];
					$sql2 = "INSERT INTO ordenes (external_id,trand_id,job_id,item_line,item,item_estatus,unicaid,url1,url2,descripcion,quantity,rate,fecha_item,responsable,fecha_inicio) VALUES ('$external_id','$trand_id','$job_id',$item_line,'$item',$item_estatus,'$unicaid','$url1','$url2','$descripcion',$quantity,$rate,'$fecha_item',$responsable,'$fecha_inicio')";
					$proceso2 = mysqli_query($conexion,$sql2);
				}
			}else{
				$errores1 .= ' | Error UnicaID no existente en Job_id = '.$job_id;
				$validacion1_1 = 0;
			}
		}
	}

	if($validacion1==1){
		$datos = [
			"estatus"		=> "ok",
			"msg"		=> "Todo guardado exitosamente!",
		];
		echo json_encode($datos);
	}else{
		$datos = [
			"estatus"		=> "error",
			"msg"			=> "Se presentaron algunos problemas",
			"validacion1"	=> $validacion1,
			"errores1"		=> $errores1,
		];
		echo json_encode($datos);
	}

}else if($condicion=='designer_create'){
	@$usuario_sql = $_POST["name"];
	@$password_sql = md5($_POST["password"]);
	@$unicaid = $_POST["id"];
	@$nombre = $_POST["name2"];
	@$usuario = $_POST["username"];
	@$correo_personal = $_POST["email_personal"];
	/**************************************/
	$password = rand(10000000, 99999999);
	$password = md5($password);
	//$password = md5($_POST["password"]);
	/**************************************/
	@$estatus = $_POST["status"];
	@$id_roles = $_POST["id_roles"];
	$responsable = 1;

	if($usuario_sql=='' or $password_sql=='' or $unicaid=='' or $nombre=='' or $usuario=='' or $correo_personal=='' or $estatus=='' or $id_roles==''){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Faltan datos requeridos!",
		];
		echo json_encode($datos);
		exit;
	}

	if(is_numeric($estatus)==false){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Status debe ser un numero",
		];
		echo json_encode($datos);
		exit;
	}else if ($estatus>=2){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Status No debe ser mayor a 1",
		];
		echo json_encode($datos);
		exit;
	}

	$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario_sql' and password = '$password_sql'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	
	if($contador1==0){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Acceso Denegado!",
		];
		echo json_encode($datos);
		exit;
	}

	$sql3 = "INSERT INTO usuarios(id_roles,unicaid,nombre,usuario,correo_personal,password,estatus,responsable,fecha_modificacion,fecha_inicio) VALUES ($id_roles,'$unicaid','$nombre','$usuario','$correo_personal','$password',$estatus,$responsable,'$fecha_modificacion','$fecha_inicio')";
	//$proceso3 = mysqli_query($conexion,$sql3);
	$proceso3 = true;
	if($proceso3==true){

		$mail = new PHPMailer(true);
		try {
		    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		    $mail->isSMTP();
		    $mail->CharSet = "UTF-8";
		    $mail->Host       = 'smtp.gmail.com';
		    $mail->SMTPAuth   = true;
		    $mail->Username   = 'info@tkosales.com';
		    $mail->Password   = '@Tkosales170018903!';
		    $mail->SMTPSecure = 'ssl';
		    $mail->Port       = 465;

		    $mail->setFrom('info@tkosales.com','TKosales Info');
		    $mail->addAddress($correo_personal);

		    $html = "
		        <p>Dear ".$nombre." You have assigned as a art designer at https://prepress.tkosales.com/ please login with the following credentials: </p>
		        <p>User: ".$usuario."</p>
		        <p>password: ".$password."</p>
		        <p>Best regards, TKO IT Department.</p>
		    ";

		    $mail->isHTML(true);
		    $mail->Subject = 'New User Registration';
		    $mail->Body    = $html;
		    $mail->AltBody = 'password generated';
		    $mail->send();
	    }catch(Exception $e){
	    	echo 'El mensaje no se ha podido enviar, error: ', $mail->ErrorInfo;
	    }

		$datos = [
			"estatus"	=> "ok",
			"msg"		=> "Se ha creado el usuario exitosamente!",
		];
	}else{
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "No se ha creado el usuario",
		];
	}
	exit;
	echo json_encode($datos);

}else if($condicion=='designer_update'){
	@$usuario = $_POST["name"];
	@$password = md5($_POST["password"]);
	@$unicaid = $_POST["id"];
	@$nombre = $_POST["name"];
	@$usuario = $_POST["username"];
	@$correo_personal = $_POST["email_personal"];
	//@$password = md5($_POST["password"]);
	@$estatus = $_POST["status"];
	@$id_roles = $_POST["id_roles"];
	$contador1 = 0;

	if(!$unicaid){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "No se ha colocado el ID",
		];
		echo json_encode($datos);
		exit;
	}

	if(is_numeric($estatus)==false){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Status debe ser un numero",
		];
		echo json_encode($datos);
		exit;
	}else if ($estatus>=2){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Status No debe ser mayor a 1",
		];
		echo json_encode($datos);
		exit;
	}

	$sql3 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and password = '$password'";
	$proceso3 = mysqli_query($conexion,$sql3);
	$contador3 = mysqli_num_rows($proceso3);
	
	if($contador3==0){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Acceso Denegado!",
		];
		echo json_encode($datos);
		exit;
	}

	if($nombre!=''){
		$sql2 = "UPDATE usuarios SET nombre = '$nombre' WHERE unicaid = '$unicaid'";
		$proceso2 = mysqli_query($conexion,$sql2);
	}
	if($usuario!=''){
		$sql1 = "SELECT * FROM usuarios WHERE usuario = '$usuario' and unicaid != '$unicaid'";
		$proceso1 = mysqli_query($conexion,$sql1);
		$contador1 = mysqli_num_rows($proceso1);
		if($contador1>=1){
			$datos = [
				"estatus"	=> "error",
				"msg"		=> "Username -> Ya le pertenece a otro usuario",
			];
			echo json_encode($datos);
			exit;
		}else{
			$sql2 = "UPDATE usuarios SET usuario = '$usuario' WHERE unicaid = '$unicaid'";
			$proceso2 = mysqli_query($conexion,$sql2);
		}
	}
	if($correo_personal!=''){
		$sql2 = "UPDATE usuarios SET correo_personal = '$correo_personal' WHERE unicaid = '$unicaid'";
		$proceso2 = mysqli_query($conexion,$sql2);
	}
	if($estatus!=''){
		$sql2 = "UPDATE usuarios SET estatus = $estatus WHERE unicaid = '$unicaid'";
		$proceso2 = mysqli_query($conexion,$sql2);
	}
	if($id_roles!=''){
		$sql2 = "UPDATE usuarios SET id_roles = $id_roles WHERE unicaid = '$unicaid'";
		$proceso2 = mysqli_query($conexion,$sql2);
	}

	if($proceso2==true){
		$datos = [
			"estatus"	=> "ok",
			"msg"		=> "Se ha modificado el usuario exitosamente!",
		];
	}else{
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "No se ha modificado el usuario",
		];
	}
	echo json_encode($datos);

}else if($condicion=='test1'){
	$texto = $_POST["texto"];
	$codificado = '{"estatus":"ok","variable1":"hola Juan nosconectamos (Y)","variable2":[{"item_line": 0, "item": "AS-ST-GS 12X12", "job_id": "123564", "item_status": "ok", "date": "05\/12\/2022"},{"item_line": 0, "item": "AS-ST-GS 12X12", "job_id": "123564", "item_status": "ok", "date": "05\/12\/2022"}]}';
	$datos = json_decode($texto);
	echo json_encode($datos);
}else if($condicion=='create_wo'){
	$name = $_POST["name"];
	$password = md5($_POST["password"]);
	$trand_id = $_POST["trand_id"];

	/***********VALIDACION 1**************/
	$validacion1 = substr($trand_id,0,3);
	$validacion1 = strtoupper($validacion1);
	if($validacion1!='FIC'){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Debe iniciar con FIC!",
		];
		echo json_encode($datos);
		exit;
	}
	/*************************************/

	$sql1 = "SELECT * FROM usuarios WHERE usuario = '$name' and password =  '$password'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1==0){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "Credenciales incorrectas!",
		];
		echo json_encode($datos);
		exit;
	}

	while($row1=mysqli_fetch_array($proceso1)){
		$responsable = $row1["id"];
	}

	$sql2 = "SELECT * FROM wo WHERE trand_id = '$trand_id'";
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);

	if($contador2>=1){
		$datos = [
			"estatus"	=> "error",
			"msg"		=> "ya hay una estructura con ese trand_id",
		];
		echo json_encode($datos);
		exit;
	}

	$sql3 = "INSERT INTO wo (trand_id,estatus,responsable,fecha_inicio) VALUES ('$trand_id',1,$responsable,'$fecha_inicio')";
	$proceso3 = mysqli_query($conexion,$sql3);
	$proceso3_id=mysqli_insert_id($conexion);

	$datos = [
		"estatus"	=> "ok",
		"id_generado" => $proceso3_id,
	];
	echo json_encode($datos);

}else{
	$datos = [
		"estatus"	=> "error",
		"msg"		=> "No existe esa condition",
	];
	echo json_encode($datos);
}

?>