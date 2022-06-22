<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
@$responsable = $_SESSION['tkosales1_id'];

if($condicion=='login1'){
	$usuario = $_POST['usuario'];
	$password = md5($_POST['password']);
	$action = "";

	$sql1 = "SELECT * FROM usuarios WHERE estatus = 1 and (usuario = '$usuario' and password = '$password')";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	
	if($contador1>=1){
		while($row1=mysqli_fetch_array($proceso1)){
			$id = $row1["id"];
		}
		$_SESSION['tkosales1_id'] = $id;
		$action = "index2.php";
		
		$datos = [
			"estatus"	=> "ok",
			"sql1"	=> $sql1,
			"action"	=> $action,
		];
	}else{
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Credenciales incorrectas!",
			"sql1"	=> $sql1,
		];
	}

	echo json_encode($datos);
}

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
		$filtrado = ' and (usuario LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and estatus = "'.$sede.'"';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT * FROM usuarios WHERE id != 0 ".$filtrado." ".$sede." ";
	$sql2 = "SELECT * FROM usuarios WHERE id != 0 ".$filtrado." ".$sede." ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset."";

	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-12">
			<input type="hidden" name="contador1" id="contador1" value="'.$conteo1.'">
			<form action="#" method="POST" id="formulario1">
			<input type="hidden" name="condicion" id="condicion" value="guardar2">
	        <table class="table table-bordered">
	            <thead>
	            <tr>
	                <th class="text-center">ID</th>
	                <th class="text-center">Username</th>
	                <th class="text-center">Status</th>
	                <th class="text-center">Create</th>
	                <th class="text-center">Options</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$id = $row2["id"];
			$usuario = $row2["usuario"];
			$estatus = $row2["estatus"];
			$fecha_inicio = $row2["fecha_inicio"];

			$html .= '
		                <tr id="tr_'.$row2["id"].'">
		                    <td style="text-align:center;" id="indice_'.$row2["id"].'">'.$row2["id"].'</td>
		                    <td style="text-align:center;"  id="nombre_'.$row2["id"].'">'.$row2["usuario"].'</td>
		                    <td style="text-align:center;" id="estatus_'.$row2["id"].'">
		    ';
		    
			if($row2["estatus"]==1){
		 		$html .= 		'Active';
			}else if($row2["estatus"]==2){
				$html .= 		'Inactive';
			}

			$html .= '
		                    </td>
		                    <td style="text-align:center;" id="fecha_inicio_'.$row2["id"].'">'.$row2["fecha_inicio"].'</td>
		                    <td style="text-align:center;">Sin Opciones</td>
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

if($condicion=='guardar1'){
	$nombre = $_POST["nombre"];
	$usuario = $_POST["user"];
	$clave = md5($_POST["clave"]);
	$correo = $_POST["correo"];
	$unicaid = $_POST["unicaid"];
	$rol1 = $_POST["rol1"];
	$rol2 = $_POST["rol2"];
	$rol3 = $_POST["rol3"];

	if($rol2==''){
		$rol2 = 0;
	}

	if($rol3==''){
		$rol3 = 0;
	}

	$sql1 = "SELECT * FROM usuarios WHERE usuario = '".$usuario."' or correo_personal = '".$correo."' or unicaid = '".$unicaid."'";
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1>=1){
		$datos = [
			"estatus" 	=> 'error',
			"msg"		=> 'Correo y/o usuario existentes',
		];
		echo json_encode($datos);
		exit;
	}else{
		$sql2 = "INSERT INTO usuarios (id_roles,id_roles2,id_roles3,unicaid,nombre,usuario,password,correo_personal,estatus,responsable,fecha_inicio) VALUES ($rol1,$rol2,$rol3,'$unicaid','$nombre','$usuario','$clave','$correo',1,$responsable,'$fecha_inicio')";
		$proceso2 = mysqli_query($conexion,$sql2);
		$datos = [
			"estatus" 	=> 'ok',
			"msg"		=> 'Se ha creado exitosamente',
		];
		echo json_encode($datos);
	}
}

?>