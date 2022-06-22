<?php
@session_start();
include('conexion.php');
$condicion = $_POST["condicion"];
$datetime = date('Y-m-d H:i:s');
$fecha_creacion = date('Y-m-d');
$fecha_modificacion = date('Y-m-d');
$responsable = $_SESSION['tkosales1_id'];

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
		$filtrado = ' and (ord.external_id LIKE "%'.$filtrado.'%" or ord.trand_id LIKE "%'.$filtrado.'%" or ord.job_id LIKE "%'.$filtrado.'%" or ord.item LIKE "%'.$filtrado.'%" or usu.nombre LIKE "%'.$filtrado.'%" or desp.nombre LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and ord.item_estatus = '.$sede;
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql3 = "SELECT * FROM usuarios WHERE id = ".$responsable;
	$proceso3 = mysqli_query($conexion,$sql3);
	while($row3 = mysqli_fetch_array($proceso3)) {
		$id_roles = $row3["id_roles"];
		$id_roles2 = $row3["id_roles2"];
		$id_roles3 = $row3["id_roles3"];
	}

	if($id_roles==1 or $id_roles2==1 or $id_roles3==1){
		$roles = ' WHERE ord.id != 0 ';
	}else{
		$roles = ' WHERE ord.responsable = $responsable ';
	}

	$sql1 = "SELECT ord.id as ord_id, ord.external_id as ord_external_id, ord.trand_id as ord_trand_id, ord.job_id as ord_job_id, ord.item_line as ord_item_line, ord.item as ord_item, ord.item_estatus as ord_item_estatus, ord.unicaid as ord_unicaid, ord.fecha_inicio as ord_fecha_inicio, ord.url1 as ord_url1, usu.id as usu_id, usu.unicaid as usu_unicaid, usu.nombre as usu_nombre, desp.id as desp_id, desp.nombre as desp_nombre FROM ordenes ord 
		INNER JOIN usuarios usu 
		ON ord.unicaid = usu.unicaid 
		INNER JOIN desplegable1 desp 
		ON desp.id = ord.item_estatus 
		".$roles." 
		".$filtrado." 
		".$sede." 
	";

	$sql2 = "SELECT ord.id as ord_id, ord.external_id as ord_external_id, ord.trand_id as ord_trand_id, ord.job_id as ord_job_id, ord.item_line as ord_item_line, ord.item as ord_item, ord.item_estatus as ord_item_estatus, ord.unicaid as ord_unicaid, ord.fecha_inicio as ord_fecha_inicio, ord.url1 as ord_url1, usu.id as usu_id, usu.unicaid as usu_unicaid, usu.nombre as usu_nombre, desp.id as desp_id, desp.nombre as desp_nombre FROM ordenes ord 
		INNER JOIN usuarios usu 
		ON ord.unicaid = usu.unicaid 
		INNER JOIN desplegable1 desp 
		ON desp.id = ord.item_estatus 
		".$roles." 
		".$filtrado." 
		".$sede." 
		ORDER BY ord.id ASC LIMIT ".$limit." OFFSET ".$offset."
	";

	$proceso1 = mysqli_query($conexion,$sql1);
	$proceso2 = mysqli_query($conexion,$sql2);
	$conteo1 = mysqli_num_rows($proceso1);
	$paginas = ceil($conteo1 / $consultasporpagina);

	$html = '';

	$html .= '
		<div class="col-12">
	        <table class="table table-bordered">
	            <thead>
	            <tr>
	                <th class="text-center">external id</th>
	                <th class="text-center">trand id</th>
	                <th class="text-center">Job id</th>
	                <th class="text-center">Item line</th>
	                <th class="text-center">Item</th>
	                <th class="text-center">Item status</th>
	                <th class="text-center">User</th>
	                <th class="text-center">Fecha inicio</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {

			$html .= '
		                <tr id="tr_'.$row2["ord_id"].'">
		                    <td style="text-align:center;">'.$row2["ord_external_id"].'</td>
		                    <td style="text-align:center;">
		                    	<a href="'.$row2["ord_url1"].'" target="_blank" style="color:blue; font-weight: bold;">
		                    		'.$row2["ord_trand_id"].'
		                    	</a>
		                    </td>
		                    <td style="text-align:center;">'.$row2["ord_job_id"].'</td>
		                    <td style="text-align:center;">'.$row2["ord_item_line"].'</td>
		                    <td style="text-align:center;">'.$row2["ord_item"].'</td>
		                    <td style="text-align:center;">'.$row2["desp_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["usu_nombre"].'</td>
		                    <td style="text-align:center;">'.$row2["ord_fecha_inicio"].'</td>
		                </tr>
			';
		}
	}else{
		$html .= '<tr><td colspan="10" class="text-center" style="font-weight:bold;font-size:20px;">Sin Resultados</td></tr>';
	}

	$html .= '
	            </tbody>
	        </table>
	        <nav>
	            <div class="row">
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Mostrando '.$consultasporpagina.' de '.$conteo1.' Datos disponibles</p>
	                </div>
	                <div class="col-xs-12 col-sm-4 text-center">
	                    <p>Página '.$pagina.' de '.$paginas.' </p>
	                </div> 
	                <div class="col-xs-12 col-sm-4">
			            <nav aria-label="Page navigation" style="float:right; padding-right:2rem;">
							<ul class="pagination">
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

?>