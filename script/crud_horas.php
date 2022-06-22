<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_inicio = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
$responsable = $_SESSION['tkosales1_id'];

if($condicion=='table1'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];
	$sede = $_POST["sede"];
	$desde = $_POST["desde"];
	$hasta = $_POST["hasta"];
	$fechas = ' ';

	$horas_resultantes = 0;
	$minutos_resultantes = 0;

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (usua.nombre LIKE "%'.$filtrado.'%" or horas.desde LIKE "%'.$filtrado.'%" or horas.hasta LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and usua.id = "'.$sede.'"';
	}

	if($desde!='' or $hasta!=''){
		$fechas .= ' and horas.desde BETWEEN "'.$desde.'" AND "'.$hasta.'" and horas.hasta BETWEEN "'.$desde.'" AND "'.$hasta.'"';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT horas.id as horas_id, horas.desde as horas_desde, horas.hasta as horas_hasta, horas.responsable as horas_responsable, horas.fecha_inicio as horas_fecha_inicio, usua.id as usua_id, usua.nombre as usua_nombre FROM horas1 horas 
	INNER JOIN usuarios usua 
	ON usua.id = horas.responsable
	WHERE horas.id != 0 ".$filtrado." ".$sede." ".$fechas;

	$sql2 = "SELECT horas.id as horas_id, horas.desde as horas_desde, horas.hasta as horas_hasta, horas.responsable as horas_responsable, horas.fecha_inicio as horas_fecha_inicio, usua.id as usua_id, usua.nombre as usua_nombre FROM horas1 horas 
	INNER JOIN usuarios usua 
	ON usua.id = horas.responsable
	WHERE horas.id != 0 ".$filtrado." ".$sede." ".$fechas." ORDER BY horas.id ASC LIMIT ".$limit." OFFSET ".$offset."";

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
	                <th class="text-center">Desde</th>
	                <th class="text-center">Hasta</th>
	                <th class="text-center">Diferencia</th>
	                <th class="text-center">Responsable</th>
	                <th class="text-center">Fecha Registro</th>
	                <th class="text-center">Options</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$id = $row2["horas_id"];
			$desde = $row2["horas_desde"];
			$hasta = $row2["horas_hasta"];
			$usuario_nombre = $row2["usua_nombre"];
			$fecha_inicio = $row2["horas_fecha_inicio"];
			$diferencia = 0;

			$datetime1 = new DateTime($desde);
			$datetime2 = new DateTime($hasta);
			$interval = $datetime1->diff($datetime2);
			//echo $interval->format('%r%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
			$diferencia = $interval->format("%h horas | %i minutos");
			$horas_resultantes = $horas_resultantes+$interval->format("%h");
			$minutos_resultantes = $minutos_resultantes+$interval->format("%i");

			$html .= '
		                <tr id="tr_'.$row2["horas_id"].'">
		                    <td style="text-align:center;" id="indice_'.$row2["horas_id"].'">'.$row2["horas_id"].'</td>
		                    <td style="text-align:center;">'.$desde.'</td>
		                    <td style="text-align:center;">'.$hasta.'</td>
		                    <td style="text-align:center;">'.$diferencia.'</td>
		                    <td style="text-align:center;">'.$usuario_nombre.'</td>
		                    <td style="text-align:center;">'.$fecha_inicio.'</td>
		                    <td style="text-align:center;">
		                    	<button type="button" class="btn btn-danger" onclick="eliminar1('.$id.');">DELETE</button>
		                    </td>
		                </tr>
			';
		}
	}else{
		$html .= '<tr><td colspan="7" class="text-center" style="font-weight:bold;font-size:20px;">Without results</td></tr>';
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
				<div class="col-12 text-center">
					<p style="font-weight: bold;">Total Diferencia: '.$horas_resultantes.' Horas | '.$minutos_resultantes.' Minutos</p>
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
	$url = $_POST['url'];
	$rol = $_POST['rol'];
	$estatus = $_POST["estatus"];

	$sql1 = "SELECT * FROM funciones WHERE nombre = '$nombre' and id_roles = ".$rol;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1>=1){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Ya existe una funcion similar!",
		];
		echo json_encode($datos);
	}else{
		$sql2 = "INSERT INTO funciones (nombre,url,id_roles,estatus,responsable,fecha_inicio) VALUES ('$nombre','$url',$rol,$estatus,$responsable,'$fecha_inicio')";
		$proceso2 = mysqli_query($conexion,$sql2);
		$datos = [
			"estatus"	=> "ok",
			"msg"	=> "Se ha registrado satisfactoriamente!",
		];
		echo json_encode($datos);
	}
}

if($condicion=='consultar1'){
	$id = $_POST['id'];

	$sql1 = "SELECT * FROM funciones WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	$contador1 = mysqli_num_rows($proceso1);
	if($contador1==0){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Registro no existente!",
		];
		echo json_encode($datos);
	}else{
		while($row1=mysqli_fetch_array($proceso1)){
			$nombre = $row1["nombre"];
			$url = $row1["url"];
			$id_roles = $row1["id_roles"];
			$estatus = $row1["estatus"];
		}
		$datos = [
			"estatus"	=> "ok",
			"nombre"	=> $nombre,
			"url"	=> $url,
			"id_roles"	=> $id_roles,
			"estatus2"	=> $estatus,
			"id"		=> $id,
		];
		echo json_encode($datos);
	}
}

if($condicion=='editar1'){
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$url = $_POST['url'];
	$id_roles = $_POST['id_roles'];
	$estatus = $_POST['estatus'];

	$sql1 = "UPDATE funciones SET nombre = '$nombre', url = '$url', id_roles = $id_roles, estatus = $estatus WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"msg"		=> "Se ha actualizado correctamente!",
	];
	echo json_encode($datos);
}

if($condicion=='guardar1'){
	$fecha_desde = $_POST['fecha_desde'];
	$hora_desde = $_POST['hora_desde'];
	$fecha_hasta = $_POST['fecha_hasta'];
	$hora_hasta = $_POST['hora_hasta'];

	$desde = $fecha_desde." ".$hora_desde;
	$hasta = $fecha_hasta." ".$hora_hasta;

	/*
	$datetime1 = new DateTime($desde);
	$datetime2 = new DateTime($hasta);
	$interval = $datetime1->diff($datetime2);
	echo $interval->format('%r%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
	*/

	$sql1 = "INSERT INTO horas1 (desde,hasta,responsable,fecha_inicio) VALUES ('$desde','$hasta',$responsable,'$fecha_inicio')";
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"msg"		=> "Registro guardado exitosamente!",
	];
	echo json_encode($datos);
}

if($condicion=='eliminar1'){
	$id = $_POST['id'];

	$sql1 = "DELETE FROM horas1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"msg"		=> "Se ha eliminado correctamente!",
	];
	echo json_encode($datos);
}

if($condicion=='table2'){
	$pagina = $_POST["pagina"];
	$consultasporpagina = $_POST["consultasporpagina"];
	$filtrado = $_POST["filtrado"];
	$sede = $_POST["sede"];
	$desde = $_POST["desde"];
	$hasta = $_POST["hasta"];
	$fechas = ' ';

	$horas_resultantes = 0;
	$minutos_resultantes = 0;

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (usua.nombre LIKE "%'.$filtrado.'%" or horas.desde LIKE "%'.$filtrado.'%" or horas.hasta LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and usua.id = "'.$sede.'"';
	}

	if($desde!='' or $hasta!=''){
		$fechas .= ' and horas.desde BETWEEN "'.$desde.'" AND "'.$hasta.'" and horas.hasta BETWEEN "'.$desde.'" AND "'.$hasta.'"';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT horas.id as horas_id, horas.desde as horas_desde, horas.hasta as horas_hasta, horas.responsable as horas_responsable, horas.fecha_inicio as horas_fecha_inicio, usua.id as usua_id, usua.nombre as usua_nombre FROM horas1 horas 
	INNER JOIN usuarios usua 
	ON usua.id = horas.responsable
	WHERE horas.responsable = ".$responsable." ".$filtrado." ".$sede." ".$fechas;

	$sql2 = "SELECT horas.id as horas_id, horas.desde as horas_desde, horas.hasta as horas_hasta, horas.responsable as horas_responsable, horas.fecha_inicio as horas_fecha_inicio, usua.id as usua_id, usua.nombre as usua_nombre FROM horas1 horas 
	INNER JOIN usuarios usua 
	ON usua.id = horas.responsable
	WHERE horas.responsable = ".$responsable." ".$filtrado." ".$sede." ".$fechas." ORDER BY horas.id ASC LIMIT ".$limit." OFFSET ".$offset."";

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
	                <th class="text-center">Desde</th>
	                <th class="text-center">Hasta</th>
	                <th class="text-center">Diferencia</th>
	                <th class="text-center">Responsable</th>
	                <th class="text-center">Fecha Registro</th>
	                <th class="text-center">Options</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$id = $row2["horas_id"];
			$desde = $row2["horas_desde"];
			$hasta = $row2["horas_hasta"];
			$usuario_nombre = $row2["usua_nombre"];
			$fecha_inicio = $row2["horas_fecha_inicio"];
			$diferencia = 0;

			$datetime1 = new DateTime($desde);
			$datetime2 = new DateTime($hasta);
			$interval = $datetime1->diff($datetime2);
			//echo $interval->format('%r%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
			$diferencia = $interval->format("%h horas | %i minutos");
			$horas_resultantes = $horas_resultantes+$interval->format("%h");
			$minutos_resultantes = $minutos_resultantes+$interval->format("%i");

			$html .= '
		                <tr id="tr_'.$row2["horas_id"].'">
		                    <td style="text-align:center;" id="indice_'.$row2["horas_id"].'">'.$row2["horas_id"].'</td>
		                    <td style="text-align:center;">'.$desde.'</td>
		                    <td style="text-align:center;">'.$hasta.'</td>
		                    <td style="text-align:center;">'.$diferencia.'</td>
		                    <td style="text-align:center;">'.$usuario_nombre.'</td>
		                    <td style="text-align:center;">'.$fecha_inicio.'</td>
		                    <td style="text-align:center;">
		                    	<button type="button" class="btn btn-danger" onclick="eliminar1('.$id.');">DELETE</button>
		                    </td>
		                </tr>
			';
		}
	}else{
		$html .= '<tr><td colspan="7" class="text-center" style="font-weight:bold;font-size:20px;">Without results</td></tr>';
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
				<div class="col-12 text-center">
					<p style="font-weight: bold;">Total Diferencia: '.$horas_resultantes.' Horas | '.$minutos_resultantes.' Minutos</p>
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

?>