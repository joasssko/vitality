<?php
/*
Template Name: Log Out
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-logout');

}else{?>


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
			// redireccionamos al area privada
			header('Location: /seleccion-de-paciente/'); 
			echo "Loggin válido";
		
		}
	}
?>

<?php get_header();?>
<div id="main">
	<div class="container">
		<form class="form-signin ingresar" role="form" method='post'>
			<h2 class="form-signin-heading">Inicie sesión</h2>
			<input name = "user" type="text" class="form-control" placeholder="Rut Cliente" required="" autofocus="">
			<input name = "pass" type="password" class="form-control" placeholder="Contraseña" required="">
			<label class="checkbox">
			  <input type="checkbox" value="remember-me"> Recordar
			</label>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesión</button>
			<button class="btn btn-default btn-block reccon">Recuperar contraseña</button>
		</form>
		
		<div class="recuperarcontrasena form-signin">
			<h3 class="form-signin-heading">Recuperar contraseña</h3>
			<?php //echo do_shortcode('[contact-form-7 id="90" title="Recuperación contraseña"]')?>
		</div>
		
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('.reccon').click(function(event) {
					$('.ingresar').fadeOut('fast', function() {
						$('.recuperarcontrasena').fadeIn('slow')
					});		
				});
			});
		</script>
		
	</div>
</div>


<?php get_footer();?>

<?php }?>
