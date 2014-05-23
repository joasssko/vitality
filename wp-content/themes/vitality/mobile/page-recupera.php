<?php 
	session_start();

	if (isset($_SESSION['rut'])){
		
	} else if (isset($_POST['user'])){
		require_once('lib/nusoap.php');
		$rut = $_POST['user'];
		$pass = $_POST['pass'];
		$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_resetea_contratante?wsdl', true);
		$result = $client->call('Execute', array("Contratante_rut" => $rut));

		if ($result['Resultado'] == '0'){ ?>
			
				<div class="container">
					<div class="row"><div class="inside"><div class="alert alert-success topalert"><h4>Se ha solicitado exitosamente el la contraseña, le enviaremos un correo con la información para la recuperación. Revise en su bandeja de entrada o en la carpeta de SPAM.</h4></div></div>
				</div>
			</div>
		<?php }else{?>
			
				<div class="container">
					<div class="row"><div class="inside"><div class="alert alert-danger topalert"><h4>Hubo un error en los datos ingresados, favor intente nuevamente.</h4></div></div>
				</div>
			</div>
		<?php
		}
	}
?>

<?php get_template_part('mobile/header')?>
<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
				<form class="form-signin ingresar" role="form" method='post'>
					<h4 class="form-signin-heading">Recuperar contraseña</h4>
					<input name = "user" type="text" class="form-control" placeholder="Rut Cliente" required="" autofocus="">			
					<button class="btn btn-lg btn-primary btn-block" type="submit">Recuperar</button>
				</form>
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>