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

	if($pagina==0 or $pagina==''){
		$pagina = 1;
	}

	if($consultasporpagina==0 or $consultasporpagina==''){
		$consultasporpagina = 10;
	}

	if($filtrado!=''){
		$filtrado = ' and (nombre LIKE "%'.$filtrado.'%" or url LIKE "%'.$filtrado.'%")';
	}

	if($sede!=''){
		$sede = ' and formato = "'.$sede.'"';
	}

	$limit = $consultasporpagina;
	$offset = ($pagina - 1) * $consultasporpagina;

	$sql1 = "SELECT * FROM artes_subidas1 WHERE estatus = 1 and responsable = $responsable ".$filtrado." ".$sede." ";
	$sql2 = "SELECT * FROM artes_subidas1 WHERE estatus = 1 and responsable = $responsable ".$filtrado." ".$sede." ORDER BY id ASC LIMIT ".$limit." OFFSET ".$offset."";

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
	                <th class="text-center">Format</th>
	                <th class="text-center">IMG</th>
	                <th class="text-center">Name File</th>
	                <th class="text-center">Additional images</th>
	                <th class="text-center">Additional PDF</th>
	                <th class="text-center">Item Status</th>
	                <th class="text-center">Set Comments</th>
	                <th class="text-center">Coments</th>
	            </tr>
	            </thead>
	            <tbody>
	';
	if($conteo1>=1){
		while($row2 = mysqli_fetch_array($proceso2)) {
			$id = $row2["id"];
			$url = $row2["url"];
			$formato = $row2["formato"];
			$nombre_archivo = $row2["nombre"];
			$seleccionable1 = $row2["desplegable1"];
			$seleccionable2 = $row2["desplegable2"];
			$comentarios = $row2["comentarios"];

			$sql3 = "SELECT * FROM extras1 WHERE arte = '$nombre_archivo'";
			$proceso3 = mysqli_query($conexion,$sql3);
			$contador3 = mysqli_num_rows($proceso3);

			$sql4 = "SELECT * FROM desplegable1 WHERE estatus = 1";
			$proceso4 = mysqli_query($conexion,$sql4);

			$sql5 = "SELECT * FROM pdfs WHERE arte = '$nombre_archivo'";
			$proceso5 = mysqli_query($conexion,$sql5);
			$contador5 = mysqli_num_rows($proceso5);

			$html .= '
		                <tr id="tr_'.$row2["id"].'">
		                    <td style="text-align:center;" id="indice_'.$row2["id"].'">'.$row2["id"].'</td>
		                    <td style="text-align:center;">'.$row2["formato"].'</td>
		                    <td style="text-align:center;">
		                    	<img src="'.$url.'" class="img-fluid" style="width:100px;">
		                    </td>
		                    <td style="text-align:center;"  id="nombre_'.$row2["id"].'">'.$row2["nombre"].'</td>
		                    <td style="text-align:center;">
		                    	<button type="button" class="btn btn-rojo1" onclick="extras1('.$id.');" data-toggle="modal" data-target="#modal_extras1">'.$contador3.'</button>
		                    </td>
		                    <td style="text-align:center;">
		                    	<button type="button" class="btn btn-rojo1" onclick="pdf1('.$id.');" data-toggle="modal" data-target="#modal_pdf1">'.$contador5.'</button>
		                    </td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<select class="form-control" name="seleccionable1_'.$id.'" id="seleccionable1_'.$id.'" onchange="seleccionable1(value,'.$id.');">
		                    		<option value="">Seleccione</option>
		    ';
		    					while($row4=mysqli_fetch_array($proceso4)){
		    						if($seleccionable1==$row4["id"]){
			    						$html.='
			    							<option value="'.$row4["id"].'" selected>'.$row4["nombre"].'</option>
			    						';
		    						}else{
		    							$html.='
			    							<option value="'.$row4["id"].'">'.$row4["nombre"].'</option>
			    						';
		    						}
		    					}

		    $html .= '
		                    	</select>
		                    </td>
		                    <td class="text-center" nowrap="nowrap">
		                    	<select class="form-control" name="seleccionable2_'.$id.'" id="seleccionable2_'.$id.'" onchange="seleccionable2(value,'.$id.');" style="width: 200px; margin-right:-3rem;">
		                    		<option value="">Seleccione</option>
		   	';
		   						$sql5 = "SELECT * FROM desplegable2 WHERE id_desplegable1 = ".$seleccionable1;
		   						$proceso5 = mysqli_query($conexion,$sql5);
		   						$contador5 = mysqli_num_rows($proceso5);
		   						if($contador5>=1){
			   						while($row5=mysqli_fetch_array($proceso5)){
				   						if($seleccionable2==$row5["id"]){
				    						$html.='<option value="'.$row5["id"].'" selected>'.$row5["texto"].'</option>';	
				    					}else{
				    						$html.='<option value="'.$row5["id"].'">'.$row5["texto"].'</option>';	
				    					}
			    					}
		   						}
		    $html .= '
		                    	</select>
		                    </td>
		                    <td><input type="text" class="form-control" value="'.$comentarios.'" id="comentarios_'.$id.'" name="comentarios_'.$id.'" onkeyup="comentarios1('.$id.',value);" maxlength="40"></td>
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

if($condicion=='seleccionable1'){
	$id = $_POST['id'];
	$value = $_POST["value"];
	$html = '';

	if($value==''){
		$sql4 = "UPDATE artes_subidas1 SET desplegable1 = 0 WHERE id = ".$id;
		$proceso4 = mysqli_query($conexion,$sql4);
		$html .= '<option value="">To Select</option>';
		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
		echo json_encode($datos);
		exit;
	}

	$sql1 = "SELECT * FROM artes_subidas1 WHERE id = $id";
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1=mysqli_fetch_array($proceso1)){
		$item = $row1["item"];
	}

	$item_final = explode("-",$item);
	$item_final = $item_final[0];

	$sql2 = "SELECT * FROM tipos_artes WHERE diminutivo = '$item_final'";
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);
	if($contador2>=1){
		while($row2=mysqli_fetch_array($proceso2)){
			$id_tipos_artes = $row2["id"];
		}
		$sql3 = "SELECT * FROM desplegable2 WHERE id_tipos_artes = $id_tipos_artes and id_desplegable1 = $value and estatus = 1";
		$proceso3 = mysqli_query($conexion,$sql3);
		$contador3 = mysqli_num_rows($proceso3);
		if($contador3>=1){
			$html .= '<option value="">To Select</option>';
			while($row3=mysqli_fetch_array($proceso3)){
				$html .= '<option value="'.$row3["id"].'">'.$row3["texto"].'</option>';
			}
		}else{
			$html .= '<option value="">To Select</option>';
		}
	}else{
		$html .= '<option value="">To Select</option>';
	}

	$sql4 = "UPDATE artes_subidas1 SET desplegable1 = ".$value." WHERE id = ".$id;
	$proceso4 = mysqli_query($conexion,$sql4);

	$datos = [
		"estatus"	=> "ok",
		"html"	=> $html,
	];
	echo json_encode($datos);
}

if($condicion=='seleccionable2'){
	$id = $_POST['id'];
	$value = $_POST["value"];
	$sql1 = "UPDATE artes_subidas1 SET desplegable2 = '$value' WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
		"sql1"	=> $sql1,
	];
	echo json_encode($datos);
}

if($condicion=='guardar1'){
	$extras1_url_final = '';

	$sql5 = "SELECT * FROM artes_subidas1 WHERE estatus = 1 and responsable = $responsable and desplegable1 = 0";
	$proceso5 = mysqli_query($conexion,$sql5);
	$contador5 = mysqli_num_rows($proceso5);
	if($contador5>=1){
		$datos = [
			"estatus"	=> "error",
			"msg"	=> "Aun quedan artes con Item Status sin elegir",
		];
		echo json_encode($datos);
		exit;
	}

	$sql1 = "SELECT * FROM artes_subidas1 WHERE estatus = 1 and responsable = $responsable ";
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1 = mysqli_fetch_array($proceso1)) {
		$id = $row1["id"];
		$nombre = $row1["nombre"];
		$item_line = $row1["item_line"];
		$external_id = $row1["external_id"];
		$job_id = $row1["job_id"];
		$item = $row1["item"];
		$url = $row1["url"];
		$comentarios = $row1["comentarios"];
		$desplegable1 = $row1["desplegable1"];
		$desplegable2 = $row1["desplegable2"];

		if($desplegable2=='' or $desplegable2==0){
			$desplegable2 = 41;
		}

		$url = "https://artwork.tkosales.com/".$url;

		$sql2 = "SELECT * FROM pdfs WHERE arte = '$nombre'";
		$proceso2 = mysqli_query($conexion,$sql2);
		while($row2 = mysqli_fetch_array($proceso2)) {
			$pdfs_url = $row2["url"];
			$pdfs_url = "https://artwork.tkosales.com/".$pdfs_url;
		}

		$sql3 = "SELECT * FROM extras1 WHERE arte = '$nombre'";
		$proceso3 = mysqli_query($conexion,$sql3);
		while($row3 = mysqli_fetch_array($proceso3)) {
			$extras1_url = $row3["url"];
			$extras1_url_final .= "https://artwork.tkosales.com/".$extras1_url.",";
		}

		$extras1_url_final = substr($extras1_url_final,0,-1);

		$sql4 = "INSERT INTO job1 (nombre,external_id,item_line,custcol_item_status,custcol_final_artwork_list_comment,custcol_final_artwork_free_comment,custcol_final_artwork_url,custcol_final_artwork_preview_url,custcol_final_artwork_extras_url,comentarios,estatus,responsable,fecha_inicio) VALUES ('$nombre','$external_id','$item_line','$desplegable1','$desplegable2','$comentarios','$pdfs_url','$url','$extras1_url_final','$comentarios',1,$responsable,'$fecha_inicio')";
		$proceso4 = mysqli_query($conexion,$sql4);

		$sql6 = "UPDATE artes_subidas1 SET estatus = 2 WHERE id = ".$id;
		$proceso6 = mysqli_query($conexion,$sql6);
	}

	$datos = [
		"estatus"	=> "ok",
		"msg"	=> "Se han mandado las ordenes!",
		"sql4" => $sql4,
	];
	echo json_encode($datos);
}

if($condicion=='extras1'){
	$id = $_POST["id"];
	$html = '';
	$sql1 = "SELECT * FROM artes_subidas1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	
	while($row1=mysqli_fetch_array($proceso1)){
		$nombre = $row1["nombre"];
	}
		
	$sql2 = "SELECT * FROM extras1 WHERE arte = '".$nombre."'";
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);
	if($contador2>=1){
		while($row2=mysqli_fetch_array($proceso2)){
			$extra_id = $row2["id"];
			$nombre = $row2["nombre"];
			$formato = $row2["formato"];
			$url = $row2["url"];
			$html .= '
				<div class="col-12 text-center">
					<img src="'.$url.'" class="img-fluid" style="width:150px; max-width: 150px; max-height: 150px;">
					<p class="mt-1" style="font-size: 20px; font-weight: bold;">'.$nombre.".".$formato.'</p>
					<p>
						<a href="descargar1.php?archivo='.$url.'" target="_blank">
							<button type="button" class="btn btn-primary" value="'.$url.'">Descargar</button>
						</a>
						<button type="button" class="btn btn-danger" value="'.$url.'" onclick="eliminarextra1('.$extra_id.');">Eliminar</button>
					</p>
				</div>
				<div class="col-12"><hr style="width:100%;font-size:2px;color:black;"></div>
			';
		}
		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
	}else{
		$html = '
			<div class="col-12 text-center" style="font-size: 20px; font-weight: bold;">Este arte no tiene extras</div>
		';
		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
	}

	echo json_encode($datos);

}

if($condicion=='pdf1'){
	$id = $_POST["id"];
	$html = '';
	$sql1 = "SELECT * FROM artes_subidas1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	
	while($row1=mysqli_fetch_array($proceso1)){
		$nombre = $row1["nombre"];
	}
		
	$sql2 = "SELECT * FROM pdfs WHERE arte = '".$nombre."'";
	$proceso2 = mysqli_query($conexion,$sql2);
	$contador2 = mysqli_num_rows($proceso2);
	if($contador2>=1){
		while($row2=mysqli_fetch_array($proceso2)){
			$nombre = $row2["arte"];
			$formato = $row2["formato"];
			$url = $row2["url"];
			$html .= '
				<div class="col-12 text-center">
					<embed src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="300px" height="200px" />
					<p class="mt-3" style="font-size: 20px; font-weight: bold;">'.$nombre.".".$formato.'</p>
					<p>
						<a href="descargar1.php?archivo='.$url.'" target="_blank">
							<button type="button" class="btn btn-primary mt-1" value="'.$url.'">Descargar</button>
						</a>
					</p>
				</div>
			';
		}
		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
	}else{
		$html = '
			<div class="col-12 text-center" style="font-size: 20px; font-weight: bold;">Este arte no tiene extras</div>
		';
		$datos = [
			"estatus"	=> "ok",
			"html"	=> $html,
		];
	}

	echo json_encode($datos);

}

if($condicion=='comentarios1'){
	$id = $_POST['id'];
	$value = $_POST["value"];

	$sql1 = "UPDATE artes_subidas1 SET comentarios = '$value' WHERE id = $id";
	$proceso1 = mysqli_query($conexion,$sql1);

	$datos = [
		"estatus"	=> "ok",
	];
	echo json_encode($datos);
}

if($condicion=='eliminarextra1'){
	$id = $_POST['id'];

	$sql1 = "SELECT * FROM extras1 WHERE id = ".$id;
	$proceso1 = mysqli_query($conexion,$sql1);
	while($row1=mysqli_fetch_array($proceso1)){
		$url = $row1["url"];
	}

	$sql2 = "DELETE FROM extras2 WHERE id = ".$id;
	$proceso2 = mysqli_query($conexion,$sql2);

	unlink("../".$url);

	$datos = [
		"estatus"	=> "ok",
		"msg" => "Se ha eliminado exitosamente!"
	];
	echo json_encode($datos);
}

?>