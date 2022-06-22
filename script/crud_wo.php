<?php
@session_start();
include("conexion.php");
ini_set('upload_max_filesize', '80M');
ini_set('post_max_size', '80M');
ini_set('max_input_time', 1000);
ini_set('max_execution_time', 1000);
$condicion = $_POST["condicion"];
$fecha_inicio = date('Y-m-d');
$responsable = $_SESSION['tkosales1_id'];

if($condicion=='convertir1'){
	$wo_id = $_POST['wo_id'];
	$targetDir = "../st/workorders/";
    $errores = '';
    $title ='No se han logrado subir algunos archivos';
    $indice1 = 0;
    $indice2 = 0;
    $errores2 = ' | ';
	$archivos = '';
	$contador1 = count($_FILES['images']['name']);
	$vacio = $_FILES['images']["name"][0];
	
	if($vacio==''){
		$datos = [
			"estatus" => 'error',
			"msg" => 'Debe guardar al menos un archivo!',
		];
		echo json_encode($datos);
		exit;
	}

	if($contador1==0){
		$datos = [
			"estatus" => 'error',
			"msg" => 'Debe guardar al menos un archivo!',
		];
		echo json_encode($datos);
		exit;
	}
	$contador1 = $contador1-1;

    for($key=0;$key<=$contador1;$key++){
    	$imagen_nombre = $_FILES['images']['name'][$key];
        $imagen_temporal = $_FILES['images']['tmp_name'][$key];
        $imagen_type = $_FILES['images']['type'][$key];
        @$extension = explode(".", $imagen_nombre);
        @$extension = strtolower($extension[1]);
        $imagen_nombre2 = explode(".",$_FILES['images']['name'][$key]);
        $imagen_nombre2 = $imagen_nombre2[0].".".strtolower($imagen_nombre2[1]);

        $fileName = basename($_FILES['images']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;

        $new_name = $imagen_nombre;

        $nombre_simple = explode(".",$new_name);
        $nombre_simple = $nombre_simple[0];
		$sourcePath = $_FILES["images"]["tmp_name"][$key];

		$sql2 = "SELECT * FROM wo WHERE id = ".$wo_id;
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$trand_id = $row2["trand_id"];
		}
		
		$validacion1 = substr($imagen_nombre,0,3);
		$validacion1 = strtoupper($validacion1);
		if($validacion1=='FIC' and $extension=='png'){
			$imagen_nombre = $trand_id.".".$extension;
		}

		$sql3 = "SELECT * FROM galeria_wo1 WHERE imagen = '$imagen_nombre' and wo_id = ".$wo_id;
		$proceso3 = mysqli_query($conexion,$sql3);
		$contador3 = mysqli_num_rows($proceso3);
		if($contador3>=1){
			$indice1 = $indice1+1;
			$errores2 .= $imagen_nombre." | ";
		}else{

			if($contador3==0){
				$ruta = "../st/workorders/".$trand_id;
				@!file_exists(mkdir($ruta, 0777, true));

				$sql3 = "INSERT INTO galeria_wo1 (wo_id,imagen,responsable,fecha_inicio) VALUES ('$wo_id','$imagen_nombre',$responsable,'$fecha_inicio')";
				$proceso3 = mysqli_query($conexion,$sql3);
				@move_uploaded_file($sourcePath, $ruta."/".$imagen_nombre);
			}
		}

    }

    if($indice1>=1){
    	$datos = [
			"estatus" => 'error',
			"msg" => 'Arhivos duplicados -> '.$errores2,
		];
		echo json_encode($datos);	
    }else{
	    $datos = [
			"estatus" => 'ok',
		];
		echo json_encode($datos);
    }
    
}

if($condicion=='mostrar1'){
	$archivos = '';
	$folder_path = '../img/artes_subidos1/';
    $folder_path2 = 'img/artes_subidos1/';

	$sql1 = "SELECT * FROM previews WHERE responsable = ".$responsable;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1=mysqli_fetch_array($proceso1)){
		$previews_id = $row1["id"];
		$previews_nombre = $row1["nombre"];
		$previews_formato = $row1["formato"];
		$previews_url = $folder_path2.$previews_nombre.".".$previews_formato;
		if($previews_formato=='jpg' || $previews_formato =='png' || $previews_formato == 'jpeg' || $previews_formato == 'JPG' || $previews_formato == 'PNG' || $previews_formato == 'JPEG') {
			$archivos .= "
				<div class='col-4 mt-2 text-center' id='div_".$previews_id."'>
					<img src='".$previews_url."' style='width:150px;'>
					<p class='mt-3' style='font-size: 20px; font-weight: bold;'>".$previews_nombre."</p>
					<p>
						<a href='descargar1.php?archivo=".$previews_url."' target='_blank'>
							<button type='button' class='btn btn-primary mt-1'>Download</button>
						</a>
						<button type='button' class='btn btn-danger mt-1' value='".$previews_id."' onclick='eliminar1(value);'>Remove</button>
					</p>
				</div>
			";
		}else if($previews_formato=='pdf' || $previews_formato =='PDF'){
			$archivos .= "
				<div class='col-4 mt-2 text-center' id='div_".$previews_id."'>
					<embed src='".$previews_url."#toolbar=0&navpanes=0&scrollbar=0' type='application/pdf' width='200px' height='200px' />
					<p class='mt-3' style='font-size: 20px; font-weight: bold;'>".$previews_nombre."</p>
					<p>
						<a href='descargar1.php?archivo=".$previews_url."' target='_blank'>
							<button type='button' class='btn btn-primary mt-1'>Download</button>
						</a>
						<button type='button' class='btn btn-danger mt-1' value='".$previews_id."' onclick='eliminar1(value);'>Remove</button>
					</p>
				</div>
			";
		}
	}

	$datos = [
		"estatus" => 'ok',
		"archivos" => $archivos,
	];

	echo json_encode($datos);
}

if($condicion=='editar1'){
	$id = $_POST["id"];
	$wo_id = $_POST["wo_id"];
	$nombre = $_POST["nombre"];

	$sql1 = "SELECT * FROM galeria_wo1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	
	if($contador1==0){
		$datos = [
			"estatus" => 'error',
			"msg" => "El ID que intenta modificar no existe",
		];
		echo json_encode($datos);
		exit;
	}

	while($row1=mysqli_fetch_array($proceso1)){
		$imagen = $row1["imagen"];
	}
	$extension = explode(".",$imagen);
	$extension = $extension[1];

	$sql2 = "SELECT * FROM galeria_wo1 WHERE imagen = '".$nombre.".".$extension."' and wo_id = ".$wo_id;
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);
	if($contador2>=2){
		$datos = [
			"estatus" => 'error',
			"msg" => "Hay otro registro con el mismo nombre!",
		];
		echo json_encode($datos);
	}else{
		
		while($row2=mysqli_fetch_array($proceso2)){
			$imagen = $row2["imagen"];
		}

		$sql3 = "SELECT * FROM wo WHERE id = ".$wo_id;
		$proceso3 = mysqli_query($conexion,$sql3);
		while($row3=mysqli_fetch_array($proceso3)){
			$trand_id = $row3["trand_id"];
		}

		$sql4 = "UPDATE galeria_wo1 SET imagen = '".$nombre.".".$extension."' WHERE id = ".$id;
		$proceso4 = mysqli_query($conexion,$sql4);
		rename("../st/workorders/".$trand_id."/".$imagen, "../st/workorders/".$trand_id."/".$nombre.".".$extension);

		
		$datos = [
			"estatus" => 'ok',
			"msg" => "Se ha modificado exitosamente",
			"imagen" => $nombre.".".$extension,
		];
		echo json_encode($datos);
	}

}

if($condicion=='eliminar1'){
	$id = $_POST["id"];
	
	$sql1 = "SELECT * FROM galeria_wo1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1=mysqli_fetch_array($proceso1)){
		$wo_id = $row1["wo_id"];
		$imagen = $row1["imagen"];
	}

	$sql2 = "SELECT * FROM wo WHERE id = ".$wo_id;
	$proceso2 = mysqli_query($conexion,$sql2);
	while($row2=mysqli_fetch_array($proceso2)){
		$trand_id = $row2["trand_id"];
	}

	$sql2 = "DELETE FROM galeria_wo1 WHERE id = ".$id;
	$proceso2 = mysqli_query($conexion,$sql2);
	@unlink("../st/workorders/".$trand_id."/".$imagen);

	$datos = [
		"estatus" 	=> 'ok',
		"msg"		=> 'Se ha eliminado el registro exitosamente',
	];

	echo json_encode($datos);
}

if($condicion=='subir1'){
	$ubicacion_url = $_POST["ubicacion_url"];
	$ubicacion_url = explode("/",$ubicacion_url);
	$url_ruta = $ubicacion_url[1]."/img/subidos1/";
	$archivos = '';

	$sql1 = "SELECT * FROM previews WHERE formato = 'pdf' and responsable = ".$responsable;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1>=1){
		while($row1=mysqli_fetch_array($proceso1)){
			$previews_id = $row1["id"];
			$previews_nombre = $row1["nombre"];
			$previews_external_id = $row1["external_id"];
			$previews_trand_id = $row1["trand_id"];
			$previews_job_id = $row1["job_id"];
			$previews_item = $row1["item"];
			$previews_item_line = $row1["item_line"];
			$previews_formato = $row1["formato"];
			$extension = $previews_formato;
			$cantidad_nombre1 = strlen($row1["nombre"]);
			$validacion1 = 1;

			$sql2 = "SELECT * FROM previews WHERE (formato = 'pdf' or formato!='pdf') and trand_id = '$previews_trand_id' and job_id = '$previews_job_id'";
			$proceso2 = mysqli_query($conexion,$sql2);
			$contador2 = mysqli_num_rows($proceso2);
			if($contador2>=2){
				while($row2=mysqli_fetch_array($proceso2)){
					$id = $row2["id"];
					$nombre = $row2["nombre"];
					$formato = $row2["formato"];
					$item_line = $row2["item_line"];
					$url = "img/subidos1/".$nombre.".".$formato;
					$cantidad_nombre2 = strlen($row2["nombre"]);
					if($formato=='pdf'){
						$sql3 = "INSERT INTO pdfs(arte,formato,item_line,external_id,job_id,item,url,responsable,fecha_inicio) VALUES ('$previews_nombre','pdf','$previews_item_line','$previews_external_id','$previews_job_id','$previews_item','$url',$responsable,'$fecha_inicio')";
						$proceso3 = mysqli_query($conexion,$sql3);
					}else if($cantidad_nombre2>$cantidad_nombre1){
						$sql4 = "INSERT INTO extras1(arte,nombre,formato,item_line,external_id,job_id,item,url,fecha_inicio) VALUES ('$previews_nombre','$nombre','$formato','$item_line','$previews_external_id','$previews_job_id','$previews_item','$url','$fecha_inicio')";
							$proceso4 = mysqli_query($conexion,$sql4);
					}else{
						$sql5 = "INSERT INTO artes_subidas1(nombre,formato,item_line,external_id,job_id,item,url,estatus,responsable,fecha_inicio) VALUES ('$previews_nombre','$formato','$previews_item_line','$previews_external_id','$previews_job_id','$previews_item','$url',1,$responsable,'$fecha_inicio')";
						$proceso5 = mysqli_query($conexion,$sql5);
					}

					$sql6 = "DELETE FROM previews WHERE id = ".$id;
					$proceso6 = mysqli_query($conexion,$sql6);

					copy("../img/artes_subidos1/".$nombre.".".$formato, "../img/subidos1/".$nombre.".".$formato);
					unlink("../img/artes_subidos1/".$nombre.".".$formato);
				}
			}
		}

		$datos = [
			"estatus" => 'ok',
			"msg" => "Se han subido los archivos correspondientes",
		];
		echo json_encode($datos);
	}else{
		$datos = [
			"estatus" => 'error',
			"msg" => "No hay ninguna imagen que subir",
		];
		echo json_encode($datos);
	}
}

?>