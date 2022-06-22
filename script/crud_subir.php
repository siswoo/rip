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
	$targetDir = "../img/artes_subidos1/";
    $allowTypes = array('jpg','png','jpeg','pdf');
    $calidad = 100;
    $errores = '';
    $title ='No se han logrado subir algunos archivos';
    $indice1 = 1;
    $indice2 = 0;
    $errores2 = 'Nombres incorrectos -> ';
    
    $images_arr = array();

    function convertirWebp($source, $destination, $extension, $quality) {
		if ($extension == 'jpeg' || $extension == 'jpg'){
			$image = imagecreatefromjpeg($source);
		}elseif ($extension == 'png'){
			$image = imagecreatefrompng($source);
		}
		imagepalettetotruecolor($image);
		imagealphablending($image, true);
		imagesavealpha($image, true);
		return imagewebp($image, $destination, $quality);
	}

	$archivos = '';
	$contador1 = count($_FILES['images']['name']);
	$contador1 = $contador1-1;

    for($key=0;$key<=$contador1;$key++){
    	$imagen_nombre = $_FILES['images']['name'][$key];
        $imagen_temporal = $_FILES['images']['tmp_name'][$key];
        $imagen_type = $_FILES['images']['type'][$key];
        $extension = explode("/", $imagen_type);
        @$extension = $extension[1];
		$imagen_nombre2 = explode(".", $imagen_nombre);

        $fileName = basename($_FILES['images']['name'][$key]);
        $targetFilePath = $targetDir . $fileName;

        $new_name = $imagen_nombre;
        $validacion1 = 0;
        $validacion2 = 0;
        $validacion3 = 0;

        /**********VALIDACION #*************/
        $validacion_nombre_numeral = substr($new_name, 0, 1);
        if($validacion_nombre_numeral=='#'){
        	$new_name = substr($new_name,1);
        }
        /***********************************/
        
        /**********VALIDACION NOMBRE*************/
        $validacion_nombre1 = substr($new_name, 0, 2);
        if($validacion_nombre1=="o-" or $validacion_nombre1=="O-"){
        	$validacion1 = 1;
        }

        @$validacion_nombre2 = explode("-",$new_name);
        @$validacion_nombre2 = explode("_",$validacion_nombre2[1]);
        @$validacion_nombre3 = $validacion_nombre2[0];

        if(strlen($validacion_nombre3)==6 or strlen($validacion_nombre3)==7){
        	if(is_numeric($validacion_nombre3)==true){
        		$validacion2 = 1;
        	}
        }

        @$validacion_nombre4 = $validacion_nombre2[1];
        @$validacion_nombre4 = explode(".",$validacion_nombre4);

        if(strlen($validacion_nombre4[0])>=1 and strlen($validacion_nombre4[0])<=7){
        	if(is_numeric($validacion_nombre4[0])==true){
        		$validacion3 = 1;
        	}
        }

        @$validacion_nombre_extra1 = $validacion_nombre2[2];
        @$validacion_nombre_extra1 = explode(".",$validacion_nombre_extra1);

        if(strlen($validacion_nombre_extra1[0])>=1){
        	if(is_numeric($validacion_nombre_extra1[0])==false){
        		$validacion1 = 0;
        	}
        }

        if($validacion1== 0 or $validacion2== 0 or $validacion3== 0){
        	$indice2 = $indice2+1;
        	$errores2 .= $imagen_nombre.' | ';
        }
        /****************************************/

        /**********VALIDACION TABLA ORDENES*************/
        $validacion_ordenes_global = explode("_",$imagen_nombre);
        $validacion_ordenes1 = $validacion_ordenes_global[0];
        $validacion_ordenes2 = explode(".",$validacion_ordenes_global[1]);
		$validacion_ordenes2 = $validacion_ordenes2[0];
		
		$sql6 = "SELECT * FROM usuarios WHERE id = ".$responsable;
		$proceso6 = mysqli_query($conexion,$sql6);
		while($row6=mysqli_fetch_array($proceso6)){
			$unicaid = $row6["unicaid"];
		}

        $sql2 = "SELECT * FROM ordenes WHERE trand_id = '$validacion_ordenes1' and job_id = '$validacion_ordenes2' and unicaid = '$unicaid'";
        $proceso2 = mysqli_query($conexion,$sql2);
        $contador2 = mysqli_num_rows($proceso2);
        /***********************************************/

        $nombre_simple = explode(".",$new_name);
        $nombre_simple = $nombre_simple[0];

		$sourcePath = $_FILES["images"]["tmp_name"][$key];
		$ruta = "../img/artes_subidos1/".$new_name;

		if($validacion1== 1 and $validacion2== 1 and $validacion3== 1 and $contador2>=1){
			while($row2=mysqli_fetch_array($proceso2)){
				$item_line = $row2["item_line"];
				$external_id = $row2["external_id"];
				$job_id = $row2["job_id"];
				$item = $row2["item"];
			}
			if($extension=='jpg' or $extension=='png' or $extension=='jpeg' or $extension=='PNG' or $extension=='JPG' or $extension=='JPEG' or $extension=='pdf' or $extension=='PDF'){
				$sql5 = "DELETE FROM previews WHERE nombre = '$nombre_simple' and external_id = '$external_id' and trand_id = '$validacion_ordenes1' and job_id = '$validacion_ordenes2' and formato = '$extension'";
				$proceso5 = mysqli_query($conexion,$sql5);
				$sql3 = "INSERT INTO previews (nombre,formato,item_line,external_id,trand_id,job_id,item,responsable) VALUES ('$nombre_simple','$extension','$item_line','$external_id','$validacion_ordenes1','$validacion_ordenes2','$item',$responsable)";
				$proceso3 = mysqli_query($conexion,$sql3);
				move_uploaded_file($sourcePath, $ruta);
			}else{
				if($indice1==1){
					$indice1 = $indice1+1;
				}else{
					$errores .= ' | ';
				}
				$errores .= $imagen_nombre;
			}
		}else{
			if($indice1==1){
				$indice1 = $indice1+1;
			}else{
				$errores .= ' | ';
			}
			$errores .= $imagen_nombre;
		}
    }

	$sql4 = "SELECT * FROM previews";
	$proceso4 = mysqli_query($conexion,$sql4);
	while($row4=mysqli_fetch_array($proceso4)){
		$previews_nombre = $row4["nombre"];
		$previews_formato = $row4["formato"];
		$previews_url = "../img/artes_subidos1/".$previews_nombre.".".$previews_formato;
		if($previews_formato=='jpg' || $previews_formato =='png' || $previews_formato == 'jpeg' || $previews_formato == 'JPG' || $previews_formato == 'PNG' || $previews_formato == 'JPEG') {
			$archivos .= "<img src='".$previews_url."' style='width:150px;'>"; 
		}else if($previews_formato=='pdf' || $previews_formato =='PDF'){
			$archivos .= '
				<embed src="'.$previews_url.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="200px" height="200px" />
			';
		}
	}

    if($indice2==0){
	    $datos = [
			"estatus" => 'ok',
			"indice1" => $indice1,
			"title" => $title,
			"msg" => $errores,
			"archivos" => $archivos,
		];
	}else{
		$datos = [
			"estatus" => 'error',
			"msg" => $errores2,
		];
	}
	echo json_encode($datos);
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

if($condicion=='eliminar1'){
	$id = $_POST["value"];
	$sql1 = "SELECT * FROM previews WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1==0){
		$datos = [
			"estatus" 	=> 'error',
			"msg"		=> 'Ese Preview ya no existe',
		];
		echo json_encode($datos);
		exit;
	}else{
		while($row1=mysqli_fetch_array($proceso1)){
			$nombre = $row1["nombre"];
			$formato = $row1["formato"];
			$url = "../img/artes_subidos1/".$nombre.".".$formato;
		}
	}
	unlink($url);
	$sql2 = "DELETE FROM previews WHERE id = ".$id;
	$proceso2 = mysqli_query($conexion,$sql2);

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
	$contador8 = 0;
	$html8 = 'Usted esta tratando de cargar un archivo previamente guardado -> ';

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

			$sql8 = "SELECT * FROM artes_subidas1 WHERE nombre = '".$previews_trand_id."_".$previews_job_id."'";
			$proceso8 = mysqli_query($conexion,$sql8);
			$contador8 = mysqli_num_rows($proceso8);
			if($contador8==0){
				$sql2 = "SELECT * FROM previews WHERE (formato = 'pdf' or formato!='pdf') and trand_id = '$previews_trand_id' and job_id = '$previews_job_id'";
				$proceso2 = mysqli_query($conexion,$sql2);
				$contador2 = mysqli_num_rows($proceso2);
				if($contador2>=2){
					while($row2=mysqli_fetch_array($proceso2)){
						$id = $row2["id"];
						$nombre = $row2["nombre"];
						$formato = $row2["formato"];
						$item_line = $row2["item_line"];
						//$url = "img/subidos1/".$nombre.".".$formato;
						@!file_exists(mkdir("../st/orders/".$previews_trand_id, 0777, true));
						@!file_exists(mkdir("../st/orders/".$previews_trand_id."/original", 0777, true));
						@!file_exists(mkdir("../st/orders/".$previews_trand_id."/original/arts", 0777, true));
						@!file_exists(mkdir("../st/orders/".$previews_trand_id."/original/previews", 0777, true));
						@!file_exists(mkdir("../st/orders/".$previews_trand_id."/final", 0777, true));
						@!file_exists(mkdir("../st/orders/".$previews_trand_id."/final/arts", 0777, true));
						@!file_exists(mkdir("../st/orders/".$previews_trand_id."/final/previews", 0777, true));

						$sql7 = "SELECT * FROM ordenes WHERE trand_id = '$previews_trand_id' and job_id = '$previews_job_id'";
						$proceso7 = mysqli_query($conexion,$sql7);
						while($row7=mysqli_fetch_array($proceso7)){
							$url1 = $row7["url1"];
							$url2 = $row7["url2"];
						}

						$extension1 = pathinfo($url1, PATHINFO_EXTENSION);
						$extension2 = pathinfo($url2, PATHINFO_EXTENSION);
						file_put_contents("../st/orders/".$previews_trand_id."/original/arts/".$previews_trand_id."_".$previews_job_id.".".$extension1, file_get_contents($url1));
						file_put_contents("../st/orders/".$previews_trand_id."/original/previews/".$previews_trand_id."_".$previews_job_id.".".$extension2, file_get_contents($url2));

						if($formato=='pdf' or $formato=='PDF'){
							$url = "st/orders/".$previews_trand_id."/final/arts/".$nombre.".".$formato;
						}else{
							$url = "st/orders/".$previews_trand_id."/final/previews/".$nombre.".".$formato;
						}
						$cantidad_nombre2 = strlen($row2["nombre"]);
						if($formato=='pdf' or $formato=='PDF'){
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
						if($formato=='pdf' or $formato=='PDF'){
							copy("../img/artes_subidos1/".$nombre.".".$formato, "../st/orders/".$previews_trand_id."/final/arts/".$nombre.".".$formato);	
						}else{
							copy("../img/artes_subidos1/".$nombre.".".$formato, "../st/orders/".$previews_trand_id."/final/previews/".$nombre.".".$formato);
						}
						unlink("../img/artes_subidos1/".$nombre.".".$formato);
					}
				}
			}else{
				$html8 .= ' | '.$previews_trand_id.'_'.$previews_job_id.' ';
			}
		}
	}

	if($contador8>=1){
		$datos = [
			"estatus" => 'warning',
			"msg" => $html8." contacte con el departamento de sistemas.",
		];
		echo json_encode($datos);
	}else if($contador1>=1){
		$datos = [
			"estatus" => 'ok',
			"msg" => "Se han subido los archivos correspondientes",
		];
		echo json_encode($datos);
	}else if($contador1==0){
		$datos = [
			"estatus" => 'error',
			"msg" => "No hay ninguna imagen que subir",
		];
		echo json_encode($datos);
	}
}

?>