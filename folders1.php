<!DOCTYPE html>
<html lang="es">
<html>
<head>
	<title>RIP</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="resources/fontawesome/css/all.css" rel="stylesheet">
</head>
<body>

<div class="container">
	<div class="row ml-3 mr-3" style="margin-top: 4rem;">
		<a href="index.php">
			<button type="button" class="btn btn-info">Volver</button>
		</a>
	</div>
	<div class="seccion1" id="seccion1" style="margin-top: 3rem;">
		<div class="row ml-3 mr-3" style="margin-top: 4rem;">
			<div class="col-12 text-center" style="font-weight: bold; font-size: 30px; text-transform: uppercase;">CRUD Folders</div>
			<input type="hidden" name="datatables" id="datatables" data-pagina="1" data-consultasporpagina="10" data-filtrado="" data-sede="">
			<div class="col-4 form-group form-check">
				<label for="consultasporpagina" style="color:black; font-weight: bold;">Resultados por página</label>
				<select class="form-control" id="consultasporpagina" name="consultasporpagina">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>
			</div>
			<div class="col-4 form-group form-check">
				<label for="buscarfiltro" style="color:black; font-weight: bold;">Buscar</label>
				<input type="text" class="form-control" id="buscarfiltro" name="buscarfiltro">
			</div>
			<input type="hidden" name="consultaporsede" id="consultaporsede" value="">
			<div class="col-4">
				<br>
				<button type="button" class="btn btn-info mt-2" onclick="filtrar1();">Filtrar</button>
				<button type="button" class="btn btn-success mt-2" data-toggle="modal" data-target="#modal_nuevo1">Nuevo Registro</button>
			</div>
			<div class="col-12" style="background-color: white; border-radius: 1rem; padding: 20px 1px 1px 1px;" id="resultado_table1"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_nuevo1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<form action="#" method="POST" id="form_modal_nuevo1" style="">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Nuevo Registro</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12 form-group form-check">
							<label for="nuevo_nombre">Nombre</label>
							<input type="text" id="nuevo_nombre" name="nuevo_nombre" class="form-control" autocomplete="off" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-success">Guardar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modal_modificar1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<form action="#" method="POST" id="form_modal_modificar1" style="">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Nuevo Registro</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12 form-group form-check">
							<label for="edit_nombre">Nombre</label>
							<input type="text" id="edit_nombre" name="edit_nombre" class="form-control" autocomplete="off" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-success">Guardar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<input type="hidden" name="hidden_id" id="hidden_id" value="">

</body>
</html>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">
	$(document).ready(function() {
		filtrar1();
	});

	function filtrar1(){
		var input_consultasporpagina = $('#consultasporpagina').val();
		var input_buscarfiltro = $('#buscarfiltro').val();
		var input_consultaporsede = $('#consultaporsede').val();
        
		$('#datatables').attr({'data-consultasporpagina':input_consultasporpagina})
		$('#datatables').attr({'data-filtrado':input_buscarfiltro})
		$('#datatables').attr({'data-sede':input_consultaporsede})

		var pagina = $('#datatables').attr('data-pagina');
		var consultasporpagina = $('#datatables').attr('data-consultasporpagina');
		var sede = $('#datatables').attr('data-sede');
		var filtrado = $('#datatables').attr('data-filtrado');

		$.ajax({
			type: 'POST',
			url: 'script/crud_folders1.php',
			dataType: "JSON",
			data: {
				"pagina": pagina,
				"consultasporpagina": consultasporpagina,
				"sede": sede,
				"filtrado": filtrado,
				"condicion": "table1",
			},

			success: function(respuesta) {
				if(respuesta["estatus"]=="ok"){
					$('#resultado_table1').html(respuesta["html"]);
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}
		});
	}

	function paginacion1(value){
		$('#datatables').attr({'data-pagina':value})
		filtrar1();
	}

	$("#form_modal_nuevo1").on("submit", function(e){
		e.preventDefault();
		var nombre = $('#nuevo_nombre').val();

		$.ajax({
			type: 'POST',
			url: 'script/crud_folders1.php',
			dataType: "JSON",
			data: {
				"nombre": nombre,
				"condicion": "nuevo1",
			},

			success: function(respuesta) {
				console.log(respuesta);
				filtrar1();
				$('#nuevo_nombre').val("");
				if(respuesta["estatus"]=="ok"){
					Swal.fire({
				 		title: 'Ok',
				 		text: respuesta["msg"],
				 		icon: 'success',
				 		position: 'center',
				 		timer: 2000,
					});
				}else{
					Swal.fire({
				 		title: 'Error',
				 		text: respuesta["msg"],
				 		icon: 'error',
				 		position: 'center',
				 		timer: 2000,
					});
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}

		});
	});

	function consultar1(id){
		$('#hidden_id').val(id);
		$.ajax({
			type: 'POST',
			url: 'script/crud_folders1.php',
			dataType: "JSON",
			data: {
				"id": id,
				"condicion": "consultar1",
			},

			success: function(respuesta) {
				console.log(respuesta);
				if(respuesta["estatus"]=="ok"){
					$('#edit_nombre').val(respuesta["nombre"]);
				}else if(respuesta["estatus"]=="error"){
					Swal.fire({
				 		title: 'Error',
				 		text: respuesta["msg"],
				 		icon: 'error',
				 		position: 'center',
				 		timer: 2000,
					});
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}

		});
	}

	$("#form_modal_modificar1").on("submit", function(e){
		e.preventDefault();
		var id = $('#hidden_id').val();
		var nombre = $('#edit_nombre').val();

		$.ajax({
			type: 'POST',
			url: 'script/crud_folders1.php',
			dataType: "JSON",
			data: {
				"id": id,
				"nombre": nombre,
				"condicion": "modificar1",
			},

			success: function(respuesta) {
				console.log(respuesta);
				filtrar1();
				if(respuesta["estatus"]=="ok"){
					Swal.fire({
				 		title: 'Ok',
				 		text: respuesta["msg"],
				 		icon: 'success',
				 		position: 'center',
				 		timer: 2000,
					});
				}else if(respuesta["estatus"]=="error"){
					Swal.fire({
				 		title: 'Error',
				 		text: respuesta["msg"],
				 		icon: 'error',
				 		position: 'center',
				 		timer: 2000,
					});
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}

		});
	});

	function eliminar1(id){
		$.ajax({
			type: 'POST',
			url: 'script/crud_folders1.php',
			dataType: "JSON",
			data: {
				"id": id,
				"condicion": "eliminar1",
			},

			success: function(respuesta) {
				console.log(respuesta);
				filtrar1();
				if(respuesta["estatus"]=="ok"){
					Swal.fire({
				 		title: 'Success',
				 		text: respuesta["msg"],
				 		icon: 'success',
				 		position: 'center',
				 		timer: 2000,
					});
				}else if(respuesta["estatus"]=="error"){
					Swal.fire({
				 		title: 'Error',
				 		text: respuesta["msg"],
				 		icon: 'error',
				 		position: 'center',
				 		timer: 2000,
					});
				}
			},

			error: function(respuesta) {
				console.log(respuesta['responseText']);
			}

		});
	}

</script>