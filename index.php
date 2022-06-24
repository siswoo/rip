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

<div class="container">
	<input type="hidden" id="condicion" name="condicion" value="login1">
    <div class="seccion1" style="margin-top: 3rem;">
      <div class="row">
        <div class="container">
          <div class="col-12 text-center" class="text-center">
          	<a href="folders1.php">
            	<button type="button" class="btn btn-info">FOLDERS</button>
            </a>
          </div>
        </div>
      </div>
    </div>
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