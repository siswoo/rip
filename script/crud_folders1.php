<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
//$responsable = $_SESSION['tkosales1_id'];

if($condicion=='table1'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];
	$sede = $_POST["sede"];

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (nombre LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and formato = "'.$sede.'"';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT * FROM folders1 WHERE id != 0 ".$filtrado." ".$sede." ";
	$sql2 = "SELECT * FROM folders1 WHERE id != 0 ".$filtrado." ".$sede." ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset."";

	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-12">
			<input type="hidden" name="contador1" id="contador1" value="'.$conteo1.'">
	        <table class="table table-bordered">
	            <thead>
	            <tr>
	                <th class="text-center">ID</th>
	                <th class="text-center">Name</th>
	                <th class="text-center">Options</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$id = $row2["id"];
			$nombre = $row2["nombre"];

			$html .= '
		                <tr id="tr_'.$row2["id"].'">
		                    <td style="text-align:center;" id="indice_'.$row2["id"].'">'.$row2["id"].'</td>
		                    <td style="text-align:center;">'.$row2["nombre"].'</td>
		                    <td style="text-align:center;">
		                    	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_modificar1" onclick="consultar1('.$id.');">Modificar</button>
		                    	<button type="button" class="btn btn-danger" onclick="eliminar1('.$id.');">Eliminar</button>
		                    </td>
		                </tr>
			';
		}
	}else{
		$html .= '<tr><td colspan="10" class="text-center" style="font-weight:bold;font-size:20px;">Without results</td></tr>';
	}

	$html .= '
	            </tbody>
	        </table>
	    <form>
	        <nav>
	            <div class="row">
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Show '.$consultasporpagina.' de '.$conteo1.' Available data</p>
	                </div>
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>page '.$pagina.' de '.$paginas.' </p>
	                </div> 
	                <div class="col-xs-12 col-sm-4">
			            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
							<ul class="pagination" style="font-size: 30px;">
	';
	
	if ($pagina > 1) {
		$html .= '
								<li class="page-item">
									<a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
										<span aria-hidden="true">Anterior</span>
									</a>
								</li>
		';
	}

	$diferenciapagina = 3;
	
	/*********MENOS********/
	if($pagina==2){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	}else if($pagina==3){
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
	';
	}else if($pagina>=4){
		$html .= '
		                		<li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-3).');" href="#"">
			                            '.($pagina-3).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-2).');" href="#"">
			                            '.($pagina-2).'
			                        </a>
			                    </li>
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina-1).');" href="#"">
			                            '.($pagina-1).'
			                        </a>
			                    </li>
		';
	} 

	/*********MAS********/
	$opcionmas = $pagina+3;
	if($paginas==0){
		$opcionmas = $paginas;
	}else if($paginas>=1 and $paginas<=4){
		$opcionmas = $paginas;
	}
	
	for ($x=$pagina;$x<=$opcionmas;$x++) {
		$html .= '
			                    <li class="page-item 
		';

		if ($x == $pagina){ 
			$html .= '"active"';
		}

		$html .= '">';

		$html .= '
			                        <a class="page-link" onclick="paginacion1('.($x).');" href="#"">'.$x.'</a>
			                    </li>
		';
	}

	if ($pagina < $paginas) {
		$html .= '
			                    <li class="page-item">
			                        <a class="page-link" onclick="paginacion1('.($pagina+1).');" href="#"">
			                            <span aria-hidden="true">Siguiente</span>
			                        </a>
			                    </li>
		';
	}

	$html .= '

						</ul>
					</nav>
				</div>
	        </nav>
	    </div>
	';

	$datos = [
		"estatus"	=> "ok",
		"html"	=> $html,
		"sql2"	=> $sql2,
	];
	echo json_encode($datos);
}

if($condicion=='nuevo1'){
	$nombre = $_POST['nombre'];

	$sql1 = "SELECT * FROM folders1 WHERE nombre = '$nombre'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1>=1){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Ya existe un registro con ese nombre!",
		];
		echo json_encode($datos);
		exit;
	}

	$sql2 = "INSERT INTO folders1 (nombre,estatus,responsable,fecha_inicio) VALUES ('$nombre',1,1,'$fecha_inicio')";
	$proceso2 = mysqli_query($conexion,$sql2);
	$ruta = "../".$nombre;
	@!file_exists(mkdir($ruta, 0777, true));
	//@move_uploaded_file($sourcePath, $ruta."/".$imagen_nombre);

	$datos = [
		"estatus"	=> "ok",
		"msg"	=> "Se ha creado la carpeta exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=='eliminar1'){
	$id = $_POST['id'];

	$sql1 = "SELECT * FROM folders1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1==0){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "No existe dicho registro",
		];
		echo json_encode($datos);
		exit;
	}

	$sql2 = "SELECT * FROM folders1 WHERE id = ".$id;
	$proceso2 = mysqli_query($conexion,$sql2);
	while($row2=mysqli_fetch_array($proceso2)){
		$nombre = $row2["nombre"];
	}

	$ruta = "../".$nombre;

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

	rmDir_rf($ruta);

	$sql2 = "DELETE FROM folders1 WHERE id = ".$id;
	$proceso2 = mysqli_query($conexion,$sql2);

	//unlink($ruta);
	//@move_uploaded_file($sourcePath, $ruta."/".$imagen_nombre);

	$datos = [
		"estatus"	=> "ok",
		"msg"	=> "Se ha eliminado la carpeta exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=='consultar1'){
	$id = $_POST['id'];

	$sql1 = "SELECT * FROM folders1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1==0){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "No existe dicho registro",
		];
		echo json_encode($datos);
		exit;
	}else{
		while($row1=mysqli_fetch_array($proceso1)){
			$nombre = $row1["nombre"];
		}
		$datos = [
			"estatus"	=> "ok",
			"nombre"	=> $nombre,
		];
		echo json_encode($datos);
	}
}

if($condicion=='modificar1'){
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];

	$sql1 = "SELECT * FROM folders1 WHERE nombre = '$nombre'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);

	if($contador1>=1){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Ya existe un registro con dicho nombre",
		];
		echo json_encode($datos);
		exit;
	}else{
		$sql2 = "SELECT * FROM folders1 WHERE id = ".$id;
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2=mysqli_fetch_array($proceso2)){
			$nombre_old = $row2["nombre"];	
		}
		rename("../".$nombre_old,"../".$nombre);

		$sql2 = "UPDATE folders1 SET nombre = '$nombre' WHERE id = ".$id;
		$proceso2 = mysqli_query($conexion,$sql2);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha modificado exitosamente!",
		];
		echo json_encode($datos);
	}
}
?>