<?php 

	session_start();
	unset($_SESSION['rut']);
	
	if(isset($_COOKIE['beneficiario'])) {
	  unset($_COOKIE['beneficiario']);
	  setcookie('beneficiario', '', time()-3600 , '/'); 
	  unset($_COOKIE['alertas']);
	  setcookie('alertas', '', time()-3600 , '/'); 
	}

	header('Location: /log-in/'); 

	if (isset($_POST['user'])){
		require_once('lib/nusoap.php');
		$rut = $_POST['user'];
		$pass = $_POST['pass'];
		$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_valida_contratante?wsdl', true);
		$result = $client->call('Execute', array("Contratante_rut" => $rut , 'Contratante_contrasenia' => $pass));
		if ($result['Resultado'] == '0'){
			// login correcto! Almacenamos $rut en la sessión
			$_SESSION['rut'] = $rut;
			header('Location: /seleccion-de-paciente/'); 
			echo "Loggin válido";
		}else{
			echo "Contraseña incorrecta";
		};
};?>

<?php get_template_part('mobile/header')?>
<div class="separator"></div>

<div class="main">
	<div class="container">
		<div class="row">
        	<div class="inside">
					<form class="form-signin ingresar" role="form" method='post'>
						<h4 class="form-signin-heading">Inicie sesión</h4>
						<input name = "user" type="text" class="form-control" placeholder="Rut Cliente" required="" autofocus="">
						<input name = "pass" type="password" class="form-control" placeholder="Contraseña" required="">
						<button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesión</button>
						<button class="btn btn-default btn-block reccon">Recuperar contraseña</button>
					</form>
			</div>
		</div>
	</div>
</div>




<?php get_template_part('mobile/footer')?>