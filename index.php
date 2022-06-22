<?php
//session_start();
//session_destroy();
?>
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

<div class="container" style="display:none;">
	<input type="hidden" id="condicion" name="condicion" value="login1">
    <div class="seccion1" style="margin-top: 3rem;">
      <div class="row">
        <div class="container">
          <div class="col-12" class="text-center">
            <p class="text-center" style="font-weight: bold; font-size: 35px; text-transform: uppercase;">Datos de Ingreso</p>
          </div>
          <div class="form-group form-check">
            <label for="usuario" style="font-weight: bold;">Usuario</label>
            <input type="text" class="form-control" name="usuario" id="usuario" placeholder="" value="" autocomplete="off" required>
            <div class="ml-1 mt-1" id="error1" style="display: none; font-size: 12px; font-weight: bold; color: red;">Este campo no debe estar vacio.</div>
            </div>
          <div class="form-group form-check">
            <label for="password" style="font-weight: bold;">Clave</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="" value="" autocomplete="off" required>
            <div class="ml-1 mt-1" id="error2" style="display: none; font-size: 12px; font-weight: bold; color: red;">Este campo no debe estar vacio.</div>
            <small id="emailHelp" class="form-text text-muted">Los datos de ingreso son totalmente confidenciales.</small>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <button type="button" id="submit" class="btn btn-success" onclick="login1();">INGRESAR</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="container">
	<div class="col-12" style=""></div>
</div>

</body>
</html>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script type="text/javascript">
	$(document).ready(function(){
		//
	});

	function login1(){
		var usuario = $('#usuario').val();
		var password = $('#password').val();
		$('#error1').hide("slow");
		$('#error2').hide("slow");

		if(usuario=='' && password==''){
			$('#error1').show("slow");
			$('#error2').show("slow");
			return false;
		}else if(usuario==''){
			$('#error1').show("slow");
			return false;
		}else if(password==''){
			$('#error2').show("slow");
			return false;
		}

		$.ajax({
			type: 'POST',
			url: 'script/crud_usuarios.php',
			dataType: "JSON",
			data: {
				"usuario": usuario,
				"password": password,
				"condicion": "login1",
			},

	        beforeSend: function(){},

	        success: function(respuesta){
				console.log(respuesta);
				if(respuesta["estatus"]=="ok"){
					window.location = respuesta["action"];
				}else if(respuesta["estatus"]=="error"){
					Swal.fire({
						position: 'center',
						icon: 'error',
						title: respuesta["msg"],
						showConfirmButton: true,
						timer: 3000
					})
					return false;
				}
	        },

	        error:function(respuesta){
				console.log(respuesta["responseText"]);
	        },
	    });
	}

</script>