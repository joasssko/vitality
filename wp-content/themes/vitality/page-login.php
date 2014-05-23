<?php
/*
Template Name: Log In
*/?>
<?php
include 'mobiledetector.php';
$detect = new Mobile_Detect;

if ($detect->isMobile() && !$detect->isTablet()){

	get_template_part('mobile/page-login');

}else{?>


<?php 
	session_start();

	if (isset($_SESSION['rut'])){
		echo "Session activa:" .  $_SESSION['rut'];
		header('Location: /seleccion-de-paciente/'); // comentado para poder probar ya que no hay boton de logout
		
	} else if (isset($_POST['user'])){
		require_once('lib/nusoap.php');
		$rut = $_POST['user'];
		$pass = $_POST['pass'];
		$client = new nusoap_client('http://200.75.18.219:5877/core/servlet/aws_vitality_valida_contratante?wsdl', true);
		$result = $client->call('Execute', array("Contratante_rut" => $rut , 'Contratante_contrasenia' => $pass));

		if ($result['Resultado'] == '0'){
			$_SESSION['rut'] = $rut;
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
			<?php if(!$result['Resultado'] == '0'){;?>
				<div class="alert alert-danger">Contraseña erronea</div>
			<?php }?>
			<input name = "user" type="text" class="form-control" placeholder="Rut Cliente" required="" autofocus="">
			<input name = "pass" type="password" class="form-control" placeholder="Contraseña" required="">
			
			<button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesión</button>
			<a href="<?php echo get_page_link(105)?>" class="btn btn-default btn-block reccon">Recuperar contraseña</a>
		</form>
		
	</div>
</div>


<?php get_footer();?>

<?php }?>
