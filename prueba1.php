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

<div class="container" style="margin-top: 2rem;">
	<div class="row">
		<div class="col-6">
			<label for="usuario">Usuario</label>
			<input type="text" id="usuario" name="usuario" class="form-control" required value="admin">
		</div>
		<div class="col-6">
			<label for="clave">Password</label>
			<input type="text" id="clave" name="clave" class="form-control" required value="789456">
		</div>
		<div class="col-6">
			<label for="url">URL</label>
			<input type="text" id="url" name="url" class="form-control" required value="https://filebin.net/zhj5oy538pg33zjr/LBL000003.pdf">
		</div>
		<div class="col-6">
			<label for="folder">Folder</label>
			<input type="text" id="folder" name="folder" class="form-control" required value="hola1">
		</div>
		<div class="col-12">
			<label for="trand_id">Trand ID</label>
			<input type="text" id="trand_id" name="trand_id" class="form-control" required value="FIC 9055">
		</div>
		<div class="col-12 text-center mt-3">
			<button type="button" class="btn btn-success" id="submit1" name="submit1" onclick="guardar1();">Guardar</button>
		</div>
		<div class="col-12 text-center" style="margin-top: 5rem;">
			<textarea class="form-control" id="respuesta1" name="respuesta1"></textarea>
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
		//
	});

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

	function guardar1(){
		var usuario = $('#usuario').val();
		var clave = $('#clave').val();
		var url = $('#url').val();
		var folder = $('#folder').val();
		var trand_id = $('#trand_id').val();
		$.ajax({
			type: 'POST',
			url: 'post_s1.php',
			dataType: "JSON",
			data: {
				"user": usuario,
				"password": clave,
				"url": url,
				"folder": folder,
				"trand_id": trand_id,
				"condition": "create_tiff",
			},

			success: function(respuesta) {
				console.log(respuesta);
				$('#respuesta1').val(respuesta["msg"]);
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